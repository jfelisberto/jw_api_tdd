<?php

namespace Database\Seeders;

use App\Models\Projects;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \Carbon\Carbon;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Projects::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'conclusion_at'  => Carbon::now()->format('Y-m-d'),
        ]);
    }
}
