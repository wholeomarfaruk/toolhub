<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\NOWPaymentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Handle NOWPayments webhook for payment confirmations
     */
    public function handleNOWPayments(Request $request, NOWPaymentsService $nowPayments)
    {
        // Verify webhook signature
        $signature = $request->header('x-nowpayments-sig');
        if (!$signature || !$nowPayments->verifyWebhookSignature($request->all(), $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->all();

        // Handle different webhook events
        switch ($data['type'] ?? null) {
            case 'invoice.paid':
            case 'invoice.confirmed':
                return $this->handlePaymentConfirmed($data, $nowPayments);

            case 'invoice.expired':
                return $this->handlePaymentExpired($data);

            case 'invoice.underpaid':
                return $this->handlePaymentUnderpaid($data);

            default:
                return response()->json(['status' => 'ignored']);
        }
    }

    /**
     * Handle successful payment
     */
    private function handlePaymentConfirmed(array $data, NOWPaymentsService $nowPayments)
    {
        try {
            // Find payment record
            $payment = Payment::where('nowpayments_invoice_id', $data['invoice_id'])
                ->firstOrFail();

            // Update payment status
            $payment = $nowPayments->handlePaymentConfirmation($data);

            // Activate subscription if exists
            if ($payment->subscription_id) {
                $subscription = $payment->subscription;

                // Calculate period based on billing cycle from payment record
                $billingPeriod = $payment->billing_period ?? 'monthly';
                $endDate = $billingPeriod === 'yearly'
                    ? now()->addYears(1)
                    : now()->addMonths(1);

                // Update subscription
                $subscription->update([
                    'status' => \App\Enums\SubscriptionStatus::Active->value,
                    'current_period_start' => now(),
                    'current_period_end' => $endDate,
                ]);

                // Send confirmation email (when notifications are created)
                // $payment->user->notify(new \App\Notifications\PaymentConfirmedNotification($payment));
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Payment confirmation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle expired payment
     */
    private function handlePaymentExpired(array $data)
    {
        try {
            $payment = Payment::where('nowpayments_invoice_id', $data['invoice_id'])
                ->firstOrFail();

            $payment->update(['status' => 'expired']);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Payment expiration handling failed: ' . $e->getMessage());
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }

    /**
     * Handle underpaid invoice
     */
    private function handlePaymentUnderpaid(array $data)
    {
        try {
            $payment = Payment::where('nowpayments_invoice_id', $data['invoice_id'])
                ->firstOrFail();

            $payment->update([
                'status' => 'failed',
                'crypto_amount' => $data['actually_paid'] ?? null,
            ]);

            // Send notification to user
            $payment->user->notify(new \App\Notifications\PaymentUnderpaidNotification($payment));

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            \Log::error('Underpaid handling failed: ' . $e->getMessage());
            return response()->json(['error' => 'Processing failed'], 500);
        }
    }
}
