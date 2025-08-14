<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Reaction;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(3)->create()->each(function (User $user) {
            Thread::factory()->count(2)
                ->for($user)
                ->has(Post::factory()->count(2)->has(Reaction::factory()->count(1)))
                ->create();
        });
    }
}
