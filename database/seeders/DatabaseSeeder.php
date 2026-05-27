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
        $user = User::factory()->create();

        // $user = User::factory()->create([
        //     'name' => 'Herman J. Sprinkledoink',
        //     'email' => 'herman@example.com',
        //     'password' => 'photonic'
        // ]);

        // Gig::create([
        //   'title' => 'Laravel Senior Developer',
        //   'tags' => 'laravel, javascript',
        //   'company' => 'Acme Corp',
        //   'location' => 'Boston, MA',
        //   'email' => 'email1@email.com',
        //   'website' => 'https://www.acme.com',
        //   'description' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maxime doloribus similique quidem quaerat, expedita amet obcaecati quos perspiciatis commodi, voluptate in aliquid, aspernatur sed? Tempore adipisci molestias dolore voluptas excepturi!'
        // ]);

        // Gig::create([
        //   'title' => 'Full-Stack Developer',
        //   'tags' => 'laravel, backend, api',
        //   'company' => 'Stark Industries',
        //   'location' => 'New York, NY',
        //   'email' => 'email2@email.com',
        //   'website' => 'https://www.starkindustries.com',
        //   'description' => 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Maxime doloribus similique quidem quaerat, expedita amet obcaecati quos perspiciatis commodi, voluptate in aliquid, aspernatur sed? Tempore adipisci molestias dolore voluptas excepturi!'
        // ]);

        Gig::factory(15)->create([
          'user_id' => $user->id
        ]);
    }
}
