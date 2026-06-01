<?php

namespace Database\Seeders;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    public function run(): void
    {
        // NOWPayments - Crypto
        PaymentGateway::updateOrCreate(
            ['slug' => 'nowpayments'],
            [
                'name' => 'NOWPayments',
                'description' => 'Cryptocurrency payments (Bitcoin, Ethereum, USDT, and 200+ coins)',
                'is_active' => true,
                'environment' => 'sandbox',
                'config' => [
                    'api_key' => '',
                    'webhook_secret' => '',
                ],
                'icon_url' => 'https://nowpayments.io/images/favicon.png',
                'sort_order' => 1,
            ]
        );

        // SSL Commerz - Bangladesh Payment Gateway
        PaymentGateway::updateOrCreate(
            ['slug' => 'ssl_commerz'],
            [
                'name' => 'SSL Commerz',
                'description' => 'Bangladesh payment gateway (Bkash, Nagad, Card, Bank Transfer)',
                'is_active' => false,
                'environment' => 'sandbox',
                'config' => [
                    'store_id' => '',
                    'store_password' => '',
                ],
                'icon_url' => null,
                'sort_order' => 2,
            ]
        );

        // Stripe - Future
        PaymentGateway::updateOrCreate(
            ['slug' => 'stripe'],
            [
                'name' => 'Stripe',
                'description' => 'Global payment processor (Cards, Apple Pay, Google Pay)',
                'is_active' => false,
                'environment' => 'sandbox',
                'config' => [
                    'api_key' => '',
                    'webhook_secret' => '',
                ],
                'icon_url' => null,
                'sort_order' => 3,
            ]
        );
    }
}
