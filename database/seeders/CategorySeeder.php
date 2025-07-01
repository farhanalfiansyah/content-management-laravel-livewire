<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Create some default categories
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology'],
            ['name' => 'Web Development', 'slug' => 'web-development'],
            ['name' => 'Mobile Apps', 'slug' => 'mobile-apps'],
            ['name' => 'Digital Marketing', 'slug' => 'digital-marketing'],
            ['name' => 'Business Strategy', 'slug' => 'business-strategy'],
            ['name' => 'Design', 'slug' => 'design'],
            ['name' => 'Programming', 'slug' => 'programming'],
            ['name' => 'Laravel', 'slug' => 'laravel'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }

        // Attach random categories to existing posts
        $posts = Post::all();
        $categoryIds = Category::pluck('id')->toArray();

        foreach ($posts as $post) {
            // Attach 1-3 random categories to each post
            $randomCategories = array_rand(array_flip($categoryIds), rand(1, min(3, count($categoryIds))));
            if (!is_array($randomCategories)) {
                $randomCategories = [$randomCategories];
            }
            $post->categories()->sync($randomCategories);
        }
    }
} 