<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
          'name' => 'Harvey J. Sprinkledoink',
          'email' => 'harvey@example.com',
          'password' => bcrypt('password')
        ]);

        Gig::factory(25)->create([
          'user_id' => $user->id
        ]);
    }
}
