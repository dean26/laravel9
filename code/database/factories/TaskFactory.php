<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = User::factory()->create();
        $author = User::factory()->create();
        $job = Job::factory()->create();

        return [
            'user_id' => $user->id,
            'author_id' => $author->id,
            'job_id' => $job->id,
            'content' => $this->faker->text(),
            'deadline' => Carbon::today()->format('Y-m-d'),
        ];
    }
}
