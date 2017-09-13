<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Nokios\Cafe\MenuItem;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCanAddMenuItem()
    {
        $price = 5.60;
        $description = 'My Food Item';
        $is_drink = false;

        $payload = compact('price', 'description', 'is_drink');

        $menuItem = MenuItem::create($payload);

        $this->assertTrue($menuItem->exists);
    }

    public function testCanFetchOnlyDrinkItems()
    {
        $payload = [[
            'price' => 2.50,
            'description' => 'food item 1',
            'is_drink' => false
        ],[
            'price' => 3.50,
            'description' => 'food item 2',
            'is_drink' => false
        ],[
            'price' => 3.50,
            'description' => 'drink item 1',
            'is_drink' => true
        ],[
            'price' => 3.50,
            'description' => 'drink item 2',
            'is_drink' => true
        ]];

        \DB::table('menu_items')->insert($payload);

        $this->assertEquals(4, MenuItem::count());
        $this->assertEquals(2, MenuItem::isDrink()->count());
    }

    public function testCanFetchOnlyFoodItems()
    {
        $payload = [[
            'price' => 2.50,
            'description' => 'food item 1',
            'is_drink' => false
        ],[
            'price' => 3.50,
            'description' => 'food item 2',
            'is_drink' => false
        ],[
            'price' => 3.50,
            'description' => 'drink item 1',
            'is_drink' => true
        ],[
            'price' => 3.50,
            'description' => 'drink item 2',
            'is_drink' => true
        ]];

        \DB::table('menu_items')->insert($payload);

        $this->assertEquals(4, MenuItem::count());
        $this->assertEquals(2, MenuItem::isNotDrink()->count());
    }

    public function testCanCreateThroughApi()
    {
        $payload = [
            'price' => 2.50,
            'description' => 'food item 1',
            'is_drink' => false
        ];

        $response = $this->postJson('/api/menu-item', $payload);

        $response->assertStatus(201)
            ->assertJson($payload);
    }

    public function testCanSeeMenuItemsThroughApi()
    {
        $this->seed(\MenuItemSeeder::class);
        $response = $this->getJson('/api/menu-item');

        $response->assertStatus(200);
    }
}
