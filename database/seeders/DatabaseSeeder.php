<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criar usuário admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'admin',
            'is_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Criar usuário cliente teste
        User::factory()->create([
            'name' => 'Cliente Teste',
            'email' => 'cliente@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'client',
            'is_admin' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Criar usuário loja teste
        User::factory()->create([
            'name' => 'Loja Teste',
            'email' => 'loja@delivery-app.com',
            'password' => bcrypt('password'),
            'type' => 'shop',
            'is_admin' => false,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
