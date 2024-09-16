<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::where('email', 'julianoeloi1@gmail.com')->first())
        {
            User::factory()->create([
                'name' => 'Juliano Gmail',
                'email' => 'julianoeloi1@gmail.com',
                'password' => bcrypt('password', ['rounds' => 12])
            ]);
        }

        if (!User::where('email', 'julianoeloi@yahoo.com.br')->first())
        {
            User::factory()->create([
                'name' => 'Juliano Yhaoo',
                'email' => 'julianoeloi@yahoo.com.br',
                'password' => bcrypt('password', ['rounds' => 12])
            ]);
        }
    }
}
