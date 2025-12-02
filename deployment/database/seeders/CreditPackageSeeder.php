<?php

namespace Database\Seeders;

use App\Models\CreditPackage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreditPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Starter',
                'credits' => 5,
                'price' => 1000,
                'discount_percentage' => 0,
                'is_popular' => false,
                'is_active' => true,
                'description' => 'Perfect for beginners'
            ],
            [
                'name' => 'Standard',
                'credits' => 10,
                'price' => 2000,
                'discount_percentage' => 10,
                'is_popular' => true,
                'is_active' => true,
                'description' => 'Most popular choice'
            ],
            [
                'name' => 'Premium',
                'credits' => 20,
                'price' => 4000,
                'discount_percentage' => 15,
                'is_popular' => false,
                'is_active' => true,
                'description' => 'Best value for teams'
            ],
            [
                'name' => 'Business',
                'credits' => 50,
                'price' => 10000,
                'discount_percentage' => 20,
                'is_popular' => false,
                'is_active' => true,
                'description' => 'For business users'
            ],
            [
                'name' => 'Enterprise',
                'credits' => 100,
                'price' => 20000,
                'discount_percentage' => 25,
                'is_popular' => false,
                'is_active' => true,
                'description' => 'Maximum savings'
            ]
        ];

        foreach ($packages as $package) {
            CreditPackage::create($package);
        }
    }
}
