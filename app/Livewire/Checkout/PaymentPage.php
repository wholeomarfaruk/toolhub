<?php

namespace App\Livewire\Checkout;

use App\Models\Payment;
use App\Services\NOWPaymentsService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PaymentPage extends Component
{
    public Payment $payment;
    public int $pollingAttempts = 0;
    public int $maxPollingAttempts = 120; // Check for 10 minutes

    public function mount(Payment $payment)
    {
        // Verify user owns this payment
        if ($payment->user_id !== auth()->id()) {
            abort(403);
        }

        $this->payment = $payment;
    }

    public function checkPaymentStatus()
    {
        try {
            $nowPayments = app(NOWPaymentsService::class);
            $status = $nowPayments->getInvoiceStatus($this->payment->nowpayments_invoice_id);

            // Check if payment is confirmed
            if (isset($status['status']) && $status['status'] === 'finished') {
                // Mark as completed and activate subscription
                $this->payment->update(['status' => 'completed']);

                // Activate subscription
                if ($this->payment->subscription) {
                    $billingPeriod = $this->payment->billing_period ?? 'monthly';
                    $endDate = $billingPeriod === 'yearly'
                        ? now()->addYears(1)
                        : now()->addMonths(1);

                    $this->payment->subscription->update([
                        'status' => 'active',
                        'current_period_start' => now(),
                        'current_period_end' => $endDate,
                    ]);
                }

                $this->dispatch('notify', message: 'Payment confirmed! Subscription activated.');
                return redirect()->route('dashboard.billing')
                    ->with('success', 'Welcome to ' . $this->payment->plan->name . ' plan!');
            }

            // If still pending, poll again
            if ($this->pollingAttempts < $this->maxPollingAttempts) {
                $this->pollingAttempts++;
                $this->dispatch('notify', message: 'Checking payment status... (attempt ' . $this->pollingAttempts . ')');
                return;
            }

            // Timeout
            $this->dispatch('notify', message: 'Payment check timed out. Try again later.');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error checking payment: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.checkout.payment');
    }
}
