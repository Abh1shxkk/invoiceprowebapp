<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    /** @test */
    public function authenticated_user_can_view_clients_list()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('user.clients.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.clients.index');
    }

    /** @test */
    public function unauthenticated_user_cannot_view_clients()
    {
        $response = $this->get(route('user.clients.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function user_can_create_client()
    {
        $this->actingAs($this->user);

        $clientData = [
            'name' => 'Test Client',
            'email' => 'test@example.com',
            'phone' => '1234567890',
            'company' => 'Test Company',
            'address' => '123 Test St',
            'city' => 'Test City',
            'state' => 'TS',
            'zip' => '12345',
            'country' => 'Test Country',
        ];

        $response = $this->post(route('user.clients.store'), $clientData);

        $response->assertRedirect(route('user.clients.index'));
        $this->assertDatabaseHas('clients', [
            'name' => 'Test Client',
            'email' => 'test@example.com',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function client_name_is_required()
    {
        $this->actingAs($this->user);

        $response = $this->post(route('user.clients.store'), [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function user_can_update_their_own_client()
    {
        $this->actingAs($this->user);

        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->put(route('user.clients.update', $client), [
            'name' => 'Updated Client Name',
            'email' => $client->email,
        ]);

        $response->assertRedirect(route('user.clients.index'));
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'name' => 'Updated Client Name',
        ]);
    }

    /** @test */
    public function user_cannot_update_another_users_client()
    {
        $this->actingAs($this->user);

        $otherUser = User::factory()->create(['role' => 'user']);
        $client = Client::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->put(route('user.clients.update', $client), [
            'name' => 'Hacked Name',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_delete_their_own_client()
    {
        $this->actingAs($this->user);

        $client = Client::factory()->create(['user_id' => $this->user->id]);

        $response = $this->delete(route('user.clients.destroy', $client));

        $response->assertRedirect(route('user.clients.index'));
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }

    /** @test */
    public function user_cannot_delete_another_users_client()
    {
        $this->actingAs($this->user);

        $otherUser = User::factory()->create(['role' => 'user']);
        $client = Client::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->delete(route('user.clients.destroy', $client));

        $response->assertStatus(403);
        $this->assertDatabaseHas('clients', ['id' => $client->id]);
    }

    /** @test */
    public function user_can_search_clients()
    {
        $this->actingAs($this->user);

        Client::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'John Doe',
        ]);

        Client::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Jane Smith',
        ]);

        $response = $this->get(route('user.clients.index', ['search' => 'John']));

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }
}
