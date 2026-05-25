<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CoverageType;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach (['Super Admin', 'Admin', 'Agent', 'Customer', 'Claims Officer'] as $role) {
            Role::findOrCreate($role);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@travelinsurance.local'],
            [
                'name' => 'Travel Insurance Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Super Admin');

        Country::upsert([
            ['name' => 'India', 'iso_code' => 'IND', 'region' => 'Asia', 'is_active' => true],
            ['name' => 'United States', 'iso_code' => 'USA', 'region' => 'North America', 'is_active' => true],
            ['name' => 'United Kingdom', 'iso_code' => 'GBR', 'region' => 'Europe', 'is_active' => true],
        ], ['iso_code'], ['name', 'region', 'is_active']);

        CoverageType::upsert([
            ['name' => 'Medical Coverage', 'code' => 'MEDICAL', 'description' => 'Emergency medical expenses', 'default_limit' => 100000, 'is_add_on' => false, 'is_active' => true],
            ['name' => 'Lost Baggage', 'code' => 'BAGGAGE', 'description' => 'Lost baggage protection', 'default_limit' => 1000, 'is_add_on' => true, 'is_active' => true],
            ['name' => 'Flight Delay', 'code' => 'FLIGHT_DELAY', 'description' => 'Flight delay compensation', 'default_limit' => 500, 'is_add_on' => true, 'is_active' => true],
        ], ['code'], ['name', 'description', 'default_limit', 'is_add_on', 'is_active']);

        Plan::firstOrCreate([
            'code' => 'FAMILY-INTL-BASIC',
        ], [
            'name' => 'Family International Basic',
            'policy_type' => 'Family Travel',
            'base_premium' => 2500,
            'coverage_amount' => 50000,
            'min_age' => 0,
            'max_age' => 70,
            'max_family_members' => 6,
            'covered_countries' => ['USA', 'GBR', 'IND'],
            'benefits' => ['Medical emergency', 'Lost baggage', 'Trip cancellation'],
            'add_ons' => ['Adventure sports', 'Flight delay', 'COVID coverage'],
            'is_active' => true,
        ]);
    }
}
