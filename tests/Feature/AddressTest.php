<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    private User $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = User::factory()->client()->create();
    }

    /**
     * Testa que client pode criar endereço.
     */
    public function test_client_can_create_address(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('addresses.store'), [
                'label' => 'Casa',
                'street' => 'Rua das Flores',
                'number' => '123',
                'complement' => 'Apto 10',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310-100',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('addresses', [
            'user_id' => $this->client->id,
            'label' => 'Casa',
            'street' => 'Rua das Flores',
        ]);
    }

    /**
     * Testa que client pode editar endereço.
     */
    public function test_client_can_update_address(): void
    {
        $address = Address::factory()->create(['user_id' => $this->client->id]);

        $response = $this->actingAs($this->client)
            ->put(route('addresses.update', $address), [
                'label' => 'Trabalho',
                'street' => 'Av. Paulista',
                'number' => '1000',
                'neighborhood' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310-200',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Trabalho',
        ]);
    }

    /**
     * Testa que client pode definir endereço padrão.
     */
    public function test_client_can_set_default_address(): void
    {
        $address1 = Address::factory()->default()->create(['user_id' => $this->client->id]);
        $address2 = Address::factory()->create(['user_id' => $this->client->id]);

        $response = $this->actingAs($this->client)
            ->patch(route('addresses.set-default', $address2));

        $response->assertRedirect();
        $this->assertTrue($address2->fresh()->is_default);
    }

    /**
     * Testa que client pode excluir endereço.
     */
    public function test_client_can_delete_address(): void
    {
        // Criar dois endereços para poder excluir um
        Address::factory()->default()->create(['user_id' => $this->client->id]);
        $address2 = Address::factory()->create(['user_id' => $this->client->id]);

        $response = $this->actingAs($this->client)
            ->delete(route('addresses.destroy', $address2));

        $response->assertRedirect();
        $this->assertDatabaseMissing('addresses', ['id' => $address2->id]);
    }

    /**
     * Testa validação: rua é obrigatória.
     */
    public function test_address_street_is_required(): void
    {
        $response = $this->actingAs($this->client)
            ->post(route('addresses.store'), [
                'label' => 'Casa',
                'street' => '',
                'number' => '123',
                'neighborhood' => 'Centro',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01310-100',
            ]);

        $response->assertSessionHasErrors('street');
    }
}
