<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockMonitoringTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_for_stock_monitoring_pages(): void
    {
        $this->get('/stocks')->assertRedirect(route('login'));
        $this->get('/stocks/minimum')->assertRedirect(route('login'));
    }

    public function test_user_can_view_warehouse_stock_list_with_availability_status_and_flows(): void
    {
        $user = User::factory()->create();

        $safe = Ingredient::create([
            'code' => 'BB-SAFE',
            'name' => 'Biji Kopi Arabika',
            'category' => 'Kopi',
            'unit' => 'kg',
            'minimum_stock' => 5,
            'current_stock' => 12,
        ]);

        $low = Ingredient::create([
            'code' => 'BB-LOW',
            'name' => 'Susu Oat',
            'category' => 'Dairy',
            'unit' => 'liter',
            'minimum_stock' => 10,
            'current_stock' => 4,
        ]);

        $out = Ingredient::create([
            'code' => 'BB-OUT',
            'name' => 'Sirup Hazelnut',
            'category' => 'Sirup',
            'unit' => 'botol',
            'minimum_stock' => 3,
            'current_stock' => 0,
        ]);

        StockIn::create([
            'ingredient_id' => $safe->id,
            'user_id' => $user->id,
            'quantity' => 15,
            'received_date' => '2026-11-20',
        ]);

        StockOut::create([
            'ingredient_id' => $safe->id,
            'user_id' => $user->id,
            'quantity' => 3,
            'issued_date' => '2026-11-21',
            'outlet_name' => 'Bar Utama',
        ]);

        $this->actingAs($user)
            ->get(route('stocks.index'))
            ->assertOk()
            ->assertSee('Daftar Stok')
            ->assertSee($safe->name)
            ->assertSee($low->name)
            ->assertSee($out->name)
            ->assertSee('12,00')
            ->assertSee('+15,00')
            ->assertSee('-3,00')
            ->assertSee('Aman')
            ->assertSee('Di bawah minimum')
            ->assertSee('Habis');
    }

    public function test_user_can_search_and_filter_warehouse_stock_list(): void
    {
        $user = User::factory()->create();

        Ingredient::create([
            'code' => 'KP-01',
            'name' => 'Biji Kopi Robusta',
            'category' => 'Kopi',
            'unit' => 'kg',
            'minimum_stock' => 5,
            'current_stock' => 10,
        ]);

        Ingredient::create([
            'code' => 'SY-01',
            'name' => 'Sirup Vanila',
            'category' => 'Sirup',
            'unit' => 'botol',
            'minimum_stock' => 8,
            'current_stock' => 2,
        ]);

        Ingredient::create([
            'code' => 'SH-01',
            'name' => 'Arang Shisha',
            'category' => 'Shisha',
            'unit' => 'pack',
            'minimum_stock' => 4,
            'current_stock' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('stocks.index', ['search' => 'Vanila']))
            ->assertOk()
            ->assertSee('Sirup Vanila')
            ->assertDontSee('Biji Kopi Robusta');

        $this->actingAs($user)
            ->get(route('stocks.index', ['category' => 'Kopi']))
            ->assertOk()
            ->assertSee('Biji Kopi Robusta')
            ->assertDontSee('Sirup Vanila');

        $this->actingAs($user)
            ->get(route('stocks.index', ['status' => 'safe']))
            ->assertOk()
            ->assertSee('Biji Kopi Robusta')
            ->assertDontSee('Sirup Vanila')
            ->assertDontSee('Arang Shisha');

        $this->actingAs($user)
            ->get(route('stocks.index', ['status' => 'low']))
            ->assertOk()
            ->assertSee('Sirup Vanila')
            ->assertSee('Arang Shisha')
            ->assertDontSee('Biji Kopi Robusta');

        $this->actingAs($user)
            ->get(route('stocks.index', ['status' => 'out']))
            ->assertOk()
            ->assertSee('Arang Shisha')
            ->assertDontSee('Sirup Vanila')
            ->assertDontSee('Biji Kopi Robusta');
    }

    public function test_minimum_stock_page_only_displays_ingredients_below_minimum_threshold(): void
    {
        $user = User::factory()->create();

        Ingredient::create([
            'code' => 'BB-AMAN',
            'name' => 'Gula Aren',
            'category' => 'Pemanis',
            'unit' => 'kg',
            'minimum_stock' => 5,
            'current_stock' => 5,
        ]);

        Ingredient::create([
            'code' => 'BB-TIPIS',
            'name' => 'Susu UHT',
            'category' => 'Dairy',
            'unit' => 'liter',
            'minimum_stock' => 10,
            'current_stock' => 3.5,
        ]);

        Ingredient::create([
            'code' => 'BB-HABIS',
            'name' => 'Tembakau Mint',
            'category' => 'Shisha',
            'unit' => 'gram',
            'minimum_stock' => 200,
            'current_stock' => 0,
        ]);

        $this->actingAs($user)
            ->get(route('stocks.minimum'))
            ->assertOk()
            ->assertSee('Bahan di Bawah Batas Minimum')
            ->assertSee('Susu UHT')
            ->assertSee('Tembakau Mint')
            ->assertSee('-6,50')
            ->assertSee('-200,00')
            ->assertDontSee('Gula Aren');

        $this->actingAs($user)
            ->get(route('stocks.minimum', ['search' => 'UHT']))
            ->assertOk()
            ->assertSee('Susu UHT')
            ->assertDontSee('Tembakau Mint');
    }

    public function test_minimum_stock_page_shows_safe_empty_state_when_all_stocks_meet_minimum(): void
    {
        $user = User::factory()->create();

        Ingredient::create([
            'code' => 'BB-001',
            'name' => 'Biji Kopi',
            'category' => 'Kopi',
            'unit' => 'kg',
            'minimum_stock' => 5,
            'current_stock' => 8,
        ]);

        $this->actingAs($user)
            ->get(route('stocks.minimum'))
            ->assertOk()
            ->assertSee('Tidak ada bahan di bawah batas minimum');
    }
}
