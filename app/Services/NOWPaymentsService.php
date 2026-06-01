<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NOWPaymentsService
{
    private string $apiKey;
    private string $apiUrl = 'https://api.nowpayments.io/v1';

    public function __construct()
    {
        $this->apiKey = config('services.nowpayments.api_key') ?? env('NOWPAYMENTS_API_KEY');
    }

    /**
     * Create a payment invoice
     */
    public function createInvoice(
        int $amountUsd,
        string $orderId,
        string $callbackUrl,
        array $metadata = []
    ): array {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->post("{$this->apiUrl}/invoice", [
            'price_amount' => $amountUsd / 100,
            'price_currency' => 'USD',
            'order_id' => $orderId,
            'order_description' => $metadata['description'] ?? 'Plan Subscription',
            'ipn_callback_url' => $callbackUrl,
            'success_url' => $metadata['success_url'] ?? null,
            'cancel_url' => $metadata['cancel_url'] ?? null,
        ]);

        if (!$response->successful()) {
            throw new \Exception('NOWPayments API Error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Get invoice status
     */
    public function getInvoiceStatus(string $invoiceId): array
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->get("{$this->apiUrl}/invoice/{$invoiceId}");

        if (!$response->successful()) {
            throw new \Exception('NOWPayments API Error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Refund payment
     */
    public function refundPayment(string $invoiceId, string $refundAddress): array
    {
        $response = Http::withHeaders([
            'x-api-key' => $this->apiKey,
        ])->post("{$this->apiUrl}/invoice/{$invoiceId}/refund", [
            'refund_address' => $refundAddress,
        ]);

        if (!$response->successful()) {
            throw new \Exception('NOWPayments API Error: ' . $response->body());
        }

        return $response->json();
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature(array $payload, string $signature): bool
    {
        $webhookSecret = config('services.nowpayments.webhook_secret') ?? env('NOWPAYMENTS_WEBHOOK_SECRET');

        // Sort payload and create signature string
        ksort($payload);
        $signatureString = json_encode($payload);

        // Compute HMAC SHA512
        $computedSignature = hash_hmac('sha512', $signatureString, $webhookSecret);

        return hash_equals($computedSignature, $signature);
    }

    /**
     * Create or update payment record from invoice
     */
    public function recordPayment(Payment $payment, array $invoiceData): Payment
    {
        $payment->update([
            'nowpayments_invoice_id' => $invoiceData['id'],
            'payment_url' => $invoiceData['invoice_url'] ?? null,
            'payment_address' => $invoiceData['wallet_address'] ?? null,
            'crypto_currency' => $invoiceData['pay_currency'] ?? null,
            'crypto_amount' => $invoiceData['pay_amount'] ?? null,
            'expires_at' => isset($invoiceData['expire_by'])
                ? \Carbon\Carbon::parse($invoiceData['expire_by'])
                : now()->addHours(24),
        ]);

        return $payment;
    }

    /**
     * Handle payment confirmation from webhook
     */
    public function handlePaymentConfirmation(array $webhookData): Payment
    {
        $payment = Payment::where(
            'nowpayments_invoice_id',
            $webhookData['invoice_id']
        )->firstOrFail();

        $payment->update([
            'status' => 'completed',
            'crypto_currency' => $webhookData['actually_paid_coin_code'] ?? null,
            'crypto_amount' => $webhookData['actually_paid'] ?? null,
            'tx_hash' => $webhookData['tx_hash'] ?? null,
            'confirmed_at' => now(),
        ]);

        return $payment;
    }
}
