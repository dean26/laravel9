<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutomatedTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //wardobe
        DB::table('automated_tasks')->insert([
            'item_id' => 1,
            'content' => 'Double check for custom order materials, or flag any questions',
            'days' => 7,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 1,
            'content' => 'Cut cleats',
            'days' => 4,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 1,
            'content' => 'Cut tracks',
            'days' => 4,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 1,
            'content' => 'Cut glass',
            'days' => 2,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 1,
            'content' => 'Cut aluminium',
            'days' => 2,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 1,
            'content' => 'Build doors',
            'days' => 1,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 1,
            'content' => 'Prepare on trolley for installer',
            'days' => 1,
        ]);

        //showerscreen
        DB::table('automated_tasks')->insert([
            'item_id' => 2,
            'content' => 'Cut Aluminium',
            'days' => 1,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 2,
            'content' => 'Assemble screen',
            'days' => 1,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 2,
            'content' => 'Prepare receivers and door packs',
            'days' => 1,
        ]);

        //mirror
        DB::table('automated_tasks')->insert([
            'item_id' => 3,
            'content' => 'Cut & polish',
            'days' => 1,
        ]);

        DB::table('automated_tasks')->insert([
            'item_id' => 3,
            'content' => 'Let directors know when mirror has arrived',
            'days' => 1,
        ]);

        //splashbacks
        DB::table('automated_tasks')->insert([
            'item_id' => 4,
            'content' => 'Let directors know splashback has arrived',
            'days' => 1,
        ]);
    }
}
