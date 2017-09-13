<?php

use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payload = [[
            'price' => 2.50,
            'description' => 'Soup de Jour (Cup)',
            'is_drink' => false
        ],[
            'price' => 3.50,
            'description' => 'Soup de Jour (Bowl)',
            'is_drink' => false
        ],[
            'price' => 3.50,
            'description' => 'Premium Soda',
            'is_drink' => true
        ],[
            'price' => 2.50,
            'description' => 'Premium Tea',
            'is_drink' => true
        ]];

        \DB::table('menu_items')->insert($payload);
    }
}
