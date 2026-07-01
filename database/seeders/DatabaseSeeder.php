<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@thrift.id'],
            [
                'name' => 'Admin Thrift',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Partner users + stores
        $partners = [
            [
                'name' => 'Rina',
                'store_name' => 'Vintage Vault',
                'store_slug' => 'vintage-vault',
                'description' => 'Thrift premium sejak 2021. Khusus hoodie & jaket limited edition.',
                'location' => 'Bandung',
                'whatsapp' => '6281212345678',
                'tier' => 'gold',
            ],
            [
                'name' => 'Dimas',
                'store_name' => 'Street Collective',
                'store_slug' => 'street-collective',
                'description' => 'Streetwear & sneakers original second. Semua sudah di cuci & disetrika.',
                'location' => 'Jakarta',
                'whatsapp' => '6281312345678',
                'tier' => 'platinum',
            ],
            [
                'name' => 'Sari',
                'store_name' => 'Thrift & Co',
                'store_slug' => 'thrift-and-co',
                'description' => 'Koleksi preloved wanita & pria — gaya casual, kantoran, dan date night.',
                'location' => 'Yogyakarta',
                'whatsapp' => '6281412345678',
                'tier' => 'silver',
            ],
        ];

        foreach ($partners as $data) {
            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '', $data['store_name'])) . '@thrift.id'],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('partner123'),
                    'role' => 'partner',
                    'email_verified_at' => now(),
                ]
            );

            Partner::firstOrCreate(
                ['store_slug' => $data['store_slug']],
                [
                    'user_id' => $user->id,
                    'store_name' => $data['store_name'],
                    'description' => $data['description'],
                    'location' => $data['location'],
                    'whatsapp' => $data['whatsapp'],
                    'status' => 'approved',
                    'is_verified' => true,
                    'approved_at' => now(),
                    'tier' => $data['tier'],
                ]
            );
        }

        $this->call(ProductSeeder::class);
    }
}
