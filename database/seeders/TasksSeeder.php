<?php

namespace Database\Seeders;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \Carbon\Carbon;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'pending', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'pending', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'progress', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'pending', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'progress', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'pending', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'progress', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'conclusion', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'pending', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d')
        ]);

        Tasks::factory()->create([
            'title'       => fake()->sentence,
            'description' => fake()->text(350),
            'user_id'     => User::all()->random(),
            'status'      => 'conclusion', # 'pending', 'progress', 'conclusion'
            'duedate_at'  => Carbon::now()->format('Y-m-d'),
        ]);
    }
}
