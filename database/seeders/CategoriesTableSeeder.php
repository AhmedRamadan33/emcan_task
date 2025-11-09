<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Main Categories
            [
                'name' => 'Electronics',
                'description' => 'Latest electronic gadgets and devices',
                'slug' => Str::slug('Electronics'),
                'is_active' => true,
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashionable clothing for all ages',
                'slug' => Str::slug('Clothing'),
                'is_active' => true,
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Everything for your home and garden',
                'slug' => Str::slug('Home & Garden'),
                'is_active' => true,
            ],
            [
                'name' => 'Sports',
                'description' => 'Sports equipment and accessories',
                'slug' => Str::slug('Sports'),
                'is_active' => true,
            ],
            [
                'name' => 'Books',
                'description' => 'Various books and educational materials',
                'slug' => Str::slug('Books'),
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Sub-categories for Electronics
        $electronics = Category::where('name', 'Electronics')->first();
        $electronicSubcategories = [
            [
                'name' => 'Smartphones',
                'parent_id' => $electronics->id,
                'description' => 'Latest smartphones and mobile devices',
                'slug' => Str::slug('Smartphones'),
                'is_active' => true,
            ],
            [
                'name' => 'Laptops',
                'parent_id' => $electronics->id,
                'description' => 'Laptops and computing devices',
                'slug' => Str::slug('Laptops'),
                'is_active' => true,
            ],
            [
                'name' => 'TVs',
                'parent_id' => $electronics->id,
                'description' => 'Televisions and home entertainment',
                'slug' => Str::slug('TVs'),
                'is_active' => true,
            ],
            [
                'name' => 'Cameras',
                'parent_id' => $electronics->id,
                'description' => 'Digital cameras and photography equipment',
                'slug' => Str::slug('Cameras'),
                'is_active' => true,
            ],
        ];

        foreach ($electronicSubcategories as $subcategory) {
            Category::create($subcategory);
        }

        // Sub-categories for Clothing
        $clothing = Category::where('name', 'Clothing')->first();
        $clothingSubcategories = [
            [
                'name' => 'Men\'s Clothing',
                'parent_id' => $clothing->id,
                'description' => 'Fashionable clothing for men',
                'slug' => Str::slug('Men\'s Clothing'),
                'is_active' => true,
            ],
            [
                'name' => 'Women\'s Clothing',
                'parent_id' => $clothing->id,
                'description' => 'Fashionable clothing for women',
                'slug' => Str::slug('Women\'s Clothing'),
                'is_active' => true,
            ],
            [
                'name' => 'Kids Clothing',
                'parent_id' => $clothing->id,
                'description' => 'Clothing for children and babies',
                'slug' => Str::slug('Kids Clothing'),
                'is_active' => true,
            ],
        ];

        foreach ($clothingSubcategories as $subcategory) {
            Category::create($subcategory);
        }

        // Sub-categories for Sports
        $sports = Category::where('name', 'Sports')->first();
        $sportsSubcategories = [
            [
                'name' => 'Fitness',
                'parent_id' => $sports->id,
                'description' => 'Fitness equipment and accessories',
                'slug' => Str::slug('Fitness'),
                'is_active' => true,
            ],
            [
                'name' => 'Outdoor Sports',
                'parent_id' => $sports->id,
                'description' => 'Equipment for outdoor sports activities',
                'slug' => Str::slug('Outdoor Sports'),
                'is_active' => true,
            ],
            [
                'name' => 'Team Sports',
                'parent_id' => $sports->id,
                'description' => 'Equipment for team sports',
                'slug' => Str::slug('Team Sports'),
                'is_active' => true,
            ],
        ];

        foreach ($sportsSubcategories as $subcategory) {
            Category::create($subcategory);
        }

        $this->command->info('Categories seeded successfully!');
    }
}