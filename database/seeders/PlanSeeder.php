<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(
            ['slug' => 'free'],
            [
                'name'           => 'Free',
                'description'    => 'Get started with essential tools',
                'price_monthly'  => 0,
                'is_active'      => true,
                'sort_order'     => 1,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'pro'],
            [
                'name'           => 'Pro',
                'description'    => 'Professional tools with PDF export and invoice saving',
                'price_monthly'  => 1200,        // $12.00
                'price_yearly'   => 11952,       // $119.52 annual (17% discount)
                'is_active'      => true,
                'sort_order'     => 2,
            ]
        );

        Plan::updateOrCreate(
            ['slug' => 'enterprise'],
            [
                'name'           => 'Enterprise',
                'description'    => 'Full platform access with team collaboration and API',
                'price_monthly'  => 1599,        // $15.99
                'price_yearly'   => 15926,       // $159.26 annual (17% discount)
                'is_active'      => true,
                'sort_order'     => 3,
            ]
        );
    }
}
