<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get test users
        $client = User::where('email', 'cliente@delivery-app.com')->first();
        $admin = User::where('email', 'admin@delivery-app.com')->first();

        if ($client) {
            // Client addresses
            Address::create([
                'user_id' => $client->id,
                'label' => 'Casa',
                'street' => 'Rua das Flores',
                'number' => '123',
                'complement' => 'Apto 45',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310-100',
                'is_default' => true,
            ]);

            Address::create([
                'user_id' => $client->id,
                'label' => 'Trabalho',
                'street' => 'Av. Paulista',
                'number' => '1000',
                'complement' => '5º andar',
                'neighborhood' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310-200',
                'is_default' => false,
            ]);
        }

        if ($admin) {
            // Admin address
            Address::create([
                'user_id' => $admin->id,
                'label' => 'Casa',
                'street' => 'Rua Augusta',
                'number' => '500',
                'complement' => null,
                'neighborhood' => 'Consolação',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01305-000',
                'is_default' => true,
            ]);
        }
    }
}
