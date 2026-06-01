<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            PermissionSeeder::class,
            PanelSeeder::class,
            AssignPermissionSeeder::class,
            PlanSeeder::class,
            PlanFeatureSeeder::class,
            PaymentGatewaySeeder::class,
        ]);
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@gmail.com'],
            [
                'name'     => 'superadmin',
                'password' => bcrypt('password'),
            ]
        );
        $superadmin->syncRoles('superadmin');
        $superadmin->panels()->syncWithoutDetaching([1]); // admin panel
      
    }
}
