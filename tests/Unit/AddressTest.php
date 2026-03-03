<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa atributo fullAddress retorna endereço formatado.
     */
    public function test_full_address_attribute(): void
    {
        $address = Address::factory()->create([
            'street' => 'Rua das Flores',
            'number' => '123',
            'complement' => 'Apto 45',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
        ]);

        $expected = 'Rua das Flores, 123, Apto 45, Centro, São Paulo, SP, 01310-100';
        $this->assertEquals($expected, $address->full_address);
    }

    /**
     * Testa fullAddress sem complemento (campo null é filtrado).
     */
    public function test_full_address_without_complement(): void
    {
        $address = Address::factory()->create([
            'street' => 'Av. Paulista',
            'number' => '1000',
            'complement' => null,
            'neighborhood' => 'Bela Vista',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-200',
        ]);

        $fullAddress = $address->full_address;

        $this->assertStringContainsString('Av. Paulista', $fullAddress);
        $this->assertStringContainsString('1000', $fullAddress);
        $this->assertStringNotContainsString(',,', $fullAddress);
    }

    /**
     * Testa relacionamento: address pertence a um user.
     */
    public function test_address_belongs_to_user(): void
    {
        $user = User::factory()->client()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $this->assertEquals($user->id, $address->user->id);
    }

    /**
     * Testa marcação de endereço padrão.
     */
    public function test_address_can_be_set_as_default(): void
    {
        $user = User::factory()->client()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'is_default' => false,
        ]);

        $address->is_default = true;
        $address->save();

        $this->assertTrue($address->fresh()->is_default);
    }
}
