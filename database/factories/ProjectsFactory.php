<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use \Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Projects>
 */
class ProjectsFactory extends Factory
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
            'title'         => fake()->sentence,
            'description'   => fake()->text(350),
            'user_id'       => User::all()->random(),
            'conclusion_at' => Carbon::now()->format('Y-m-d')
        ];
    }
}
