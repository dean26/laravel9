<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::count() == 0 ? User::factory()->create() : User::all()->random(1)->first();
        $item = Item::count() == 0 ? Item::factory()->create() : Item::all()->random(1)->first();
        $customer = Customer::count() == 0 ? Customer::factory()->create() : Customer::all()->random(1)->first();

        //Job::unsetEventDispatcher();

        $possible_statuses = \App\Services\JobService::getPossibleStatuses();
        shuffle($possible_statuses);

        return [
            'status' => array_pop($possible_statuses),
            'user_id' => $user->id,
            'item_id' => $item->id,
            'customer_id' => $customer->id,
            'start_date' => Carbon::today()->format('Y-m-d'),
            'expected_installation_date' => Carbon::create()->today()->addDay(rand(1,20))->format('Y-m-d'),
        ];
    }
}
