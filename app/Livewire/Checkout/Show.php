<?php

namespace App\Livewire\Checkout;

use App\Enums\SubscriptionStatus;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\PaymentGateway;
use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;

#[Layout('layouts.app')]
class Show extends Component
{
    public $plan;
    public $billingPeriod = 'monthly';
    public $paymentMethod = 'nowpayments';
    public $activeGateway;

    public function mount()
    {
        $planSlug = request()->route('plan');

        $this->plan = Plan::where('slug', $planSlug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get active payment gateway
        $this->activeGateway = PaymentGateway::where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        if ($this->activeGateway) {
            $this->paymentMethod = $this->activeGateway->slug;
        }
    }

    public function processCheckout()
    {
        // Verify active payment gateway exists
        if (!$this->activeGateway) {
            $this->addError('checkout', 'No payment gateway is currently active. Please contact support.');
            return;
        }

        $user = auth()->user();
        $amount = $this->billingPeriod === 'yearly'
            ? $this->plan->price_yearly
            : $this->plan->price_monthly;

        // Create subscription (inactive until payment confirmed)
        $subscription = Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan_id' => $this->plan->id,
                'status' => SubscriptionStatus::Active->value,
            ]
        );

        // Create payment record
        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'plan_id' => $this->plan->id,
            'amount' => $amount,
            'currency' => 'USD',
            'billing_period' => $this->billingPeriod,
            'status' => 'pending',
        ]);

        // Create NOWPayments invoice
        try {
            $nowPayments = app(\App\Services\NOWPaymentsService::class);
            $invoice = $nowPayments->createInvoice(
                $amount,
                "order_{$payment->id}",
                route('webhook.nowpayments'),
                [
                    'description' => "{$this->plan->name} Plan - {$this->billingPeriod}",
                    'success_url' => route('checkout.success'),
                    'cancel_url' => route('home'),
                ]
            );

            // Store invoice details
            $payment = $nowPayments->recordPayment($payment, $invoice);

            // Store checkout info in session
            session([
                'checkout' => [
                    'plan_id' => $this->plan->id,
                    'subscription_id' => $subscription->id,
                    'payment_id' => $payment->id,
                    'billing_period' => $this->billingPeriod,
                    'amount' => $amount,
                ],
            ]);

            // Redirect to payment page
            return $this->redirect(route('checkout.payment', ['payment' => $payment->id]));
        } catch (\Exception $e) {
            // Delete failed payment record
            $payment->delete();
            $subscription->delete();

            \Log::error('Checkout Error: ' . $e->getMessage(), ['exception' => $e]);
            $this->addError('checkout', 'Payment gateway error: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        return view('livewire.checkout.show');
    }
}
