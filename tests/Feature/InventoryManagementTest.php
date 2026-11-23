<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_for_inventory_pages(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
        $this->get('/ingredients')->assertRedirect(route('login'));
        $this->get('/suppliers')->assertRedirect(route('login'));
    }

    public function test_user_can_sign_in_and_sign_out(): void
    {
        $user = User::factory()->create(['password' => 'correct-password']);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_user_can_create_and_search_for_suppliers(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('suppliers.create'))
            ->assertOk()
            ->assertSee('Nama supplier');

        $this->actingAs(User::factory()->create())
            ->post(route('suppliers.store'), [
                'name' => 'Pemasok Segar',
                'contact_person' => 'Dina',
                'phone' => '08123456789',
                'email' => 'dina@example.test',
                'address' => 'Bandung',
            ])
            ->assertRedirect(route('suppliers.index'));

        $this->assertDatabaseHas('suppliers', ['name' => 'Pemasok Segar']);

        $this->get(route('suppliers.index', ['search' => 'Dina']))
            ->assertOk()
            ->assertSee('Pemasok Segar');
    }

    public function test_user_can_create_and_update_ingredient_minimum_stock(): void
    {
        $this->actingAs(User::factory()->create())
            ->post(route('ingredients.store'), [
                'code' => 'BB-001',
                'name' => 'Susu UHT',
                'category' => 'Minuman',
                'unit' => 'liter',
                'minimum_stock' => '2.50',
                'description' => 'Susu full cream',
            ])
            ->assertRedirect(route('ingredients.index'));

        $ingredient = Ingredient::query()->where('code', 'BB-001')->firstOrFail();

        $this->actingAs(User::factory()->create())
            ->get(route('ingredients.edit', $ingredient))
            ->assertOk()
            ->assertSee('Batas minimum stok');

        $this->put(route('ingredients.update', $ingredient), [
            'code' => 'BB-001',
            'name' => 'Susu UHT',
            'category' => 'Minuman',
            'unit' => 'liter',
            'minimum_stock' => '5.00',
            'description' => 'Susu full cream',
        ])->assertRedirect(route('ingredients.index'));

        $this->get(route('ingredients.index'))
            ->assertOk()
            ->assertSee('Susu UHT');

        $this->assertDatabaseHas('ingredients', [
            'id' => $ingredient->id,
            'minimum_stock' => '5.00',
        ]);
    }

    public function test_ingredient_codes_must_be_unique(): void
    {
        $ingredient = Ingredient::create([
            'code' => 'BB-001',
            'name' => 'Susu UHT',
            'category' => 'Minuman',
            'unit' => 'liter',
            'minimum_stock' => 1,
        ]);

        $this->actingAs(User::factory()->create())
            ->post(route('ingredients.store'), [
                'code' => 'BB-001',
                'name' => 'Susu lain',
                'category' => 'Minuman',
                'unit' => 'liter',
                'minimum_stock' => '1.00',
            ])
            ->assertSessionHasErrors('code');

        $this->assertDatabaseCount('ingredients', 1);
        $this->assertSame('Susu UHT', $ingredient->fresh()->name);
    }
}
