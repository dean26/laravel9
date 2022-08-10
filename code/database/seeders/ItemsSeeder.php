<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
            'name' => 'Wardrobe',
        ]);

        Item::create([
            'name' => 'Shower screen',
        ]);

        Item::create([
            'name' => 'Mirror',
        ]);

        Item::create([
            'name' => 'Splashback',
        ]);

        Item::create([
            'name' => 'other',
        ]);
    }
}
