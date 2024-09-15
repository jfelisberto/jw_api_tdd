<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use \Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tasks>
 */
class TasksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /**
         * Dados fakes para popular a base
         */
        return [
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'status'      => 'pending', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d'),
        ];
    }
}
