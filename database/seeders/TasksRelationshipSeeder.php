<?php

namespace Database\Seeders;

use App\Models\Tasks;
use App\Models\TasksRelationship;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TasksRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);

        TasksRelationship::factory()->create([
            'task_id' => Tasks::all()->random(),
            'user_id' => User::all()->random()
        ]);
    }
}
