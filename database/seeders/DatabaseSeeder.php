<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
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
        // Generate 10 categories using the factory
        Category::factory(10)->create()->each(function ($category) {
            // For each category, generate 5 products
            Product::factory(5)->create(['category_id' => $category->id]);
        });

        // User::factory(10)->create();
        
        /* 
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

         // generate 10 categories and products using seeders
        $this->call([
            CategoryTableSeeder::class,
            ProductTableSeeder::class,
        ]);*/
    }
}
