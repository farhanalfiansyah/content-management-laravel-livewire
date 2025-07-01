<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $categories = [
            'Technology',
            'Web Development', 
            'Mobile Apps',
            'Digital Marketing',
            'SEO & SEM',
            'Business Strategy',
            'Entrepreneurship',
            'Design',
            'User Experience',
            'Programming',
            'Data Science',
            'Artificial Intelligence',
            'Cloud Computing',
            'Cybersecurity',
            'DevOps',
            'E-commerce',
            'Social Media',
            'Content Marketing',
            'WordPress',
            'Laravel',
        ];

        $name = $this->faker->unique()->randomElement($categories);
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
} 