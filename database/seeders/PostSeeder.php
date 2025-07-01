<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some test users if they don't exist
        $users = User::all();
        
        if ($users->count() === 0) {
            $users = User::factory(3)->create();
        }

        // Create 20 posts with random users
        Post::factory(20)
            ->recycle($users)
            ->create();

        // Create 5 specifically published posts
        Post::factory(5)
            ->published()
            ->recycle($users)
            ->create();

        // Create 3 draft posts
        Post::factory(3)
            ->draft()
            ->recycle($users)
            ->create();
    }
} 