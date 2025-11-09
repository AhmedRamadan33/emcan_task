<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories for product assignment
        $smartphones = Category::where('name', 'Smartphones')->first();
        $laptops = Category::where('name', 'Laptops')->first();
        $tvs = Category::where('name', 'TVs')->first();
        $cameras = Category::where('name', 'Cameras')->first();
        $mensClothing = Category::where('name', 'Men\'s Clothing')->first();
        $womensClothing = Category::where('name', 'Women\'s Clothing')->first();
        $fitness = Category::where('name', 'Fitness')->first();
        $books = Category::where('name', 'Books')->first();

        $products = [
            // Smartphones
            [
                'name' => 'iPhone 15 Pro',
                'category_id' => $smartphones->id,
                'sku' => 'IP15P-256',
                'description' => 'Latest iPhone with advanced camera system and A17 Pro chip',
                'price' => 999.99,
                'quantity' => 50,
                'is_active' => true,
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'category_id' => $smartphones->id,
                'sku' => 'SGS24-256',
                'description' => 'Powerful Android smartphone with AI features',
                'price' => 849.99,
                'quantity' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Google Pixel 8',
                'category_id' => $smartphones->id,
                'sku' => 'GP8-128',
                'description' => 'Google\'s flagship phone with excellent camera',
                'price' => 699.99,
                'quantity' => 40,
                'is_active' => true,
            ],

            // Laptops
            [
                'name' => 'MacBook Pro 16"',
                'category_id' => $laptops->id,
                'sku' => 'MBP16-M3',
                'description' => 'Professional laptop with M3 chip for maximum performance',
                'price' => 2399.99,
                'quantity' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Dell XPS 15',
                'category_id' => $laptops->id,
                'sku' => 'DXPS15-2024',
                'description' => 'Premium Windows laptop with 4K display',
                'price' => 1799.99,
                'quantity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Lenovo ThinkPad X1',
                'category_id' => $laptops->id,
                'sku' => 'LTPX1-CARBON',
                'description' => 'Business laptop with excellent keyboard and durability',
                'price' => 1499.99,
                'quantity' => 35,
                'is_active' => true,
            ],

            // TVs
            [
                'name' => 'Samsung 65" QLED 4K TV',
                'category_id' => $tvs->id,
                'sku' => 'SAM-Q65-4K',
                'description' => 'Quantum dot LED TV with stunning picture quality',
                'price' => 1199.99,
                'quantity' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'LG 55" OLED TV',
                'category_id' => $tvs->id,
                'sku' => 'LG-OLED55-4K',
                'description' => 'OLED TV with perfect blacks and vibrant colors',
                'price' => 1299.99,
                'quantity' => 15,
                'is_active' => true,
            ],

            // Cameras
            [
                'name' => 'Canon EOS R5',
                'category_id' => $cameras->id,
                'sku' => 'CAN-R5-BODY',
                'description' => 'Professional mirrorless camera with 45MP sensor',
                'price' => 3899.99,
                'quantity' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Sony A7 IV',
                'category_id' => $cameras->id,
                'sku' => 'SON-A7IV-BODY',
                'description' => 'Full-frame mirrorless camera for professionals',
                'price' => 2499.99,
                'quantity' => 12,
                'is_active' => true,
            ],

            // Men's Clothing
            [
                'name' => 'Men\'s Casual Shirt',
                'category_id' => $mensClothing->id,
                'sku' => 'MCS-BLUE-M',
                'description' => 'Comfortable cotton casual shirt for men',
                'price' => 29.99,
                'quantity' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Men\'s Jeans',
                'category_id' => $mensClothing->id,
                'sku' => 'MJ-DENIM-32',
                'description' => 'Classic denim jeans for men',
                'price' => 49.99,
                'quantity' => 80,
                'is_active' => true,
            ],

            // Women's Clothing
            [
                'name' => 'Women\'s Summer Dress',
                'category_id' => $womensClothing->id,
                'sku' => 'WSD-FLORAL-M',
                'description' => 'Beautiful floral summer dress',
                'price' => 39.99,
                'quantity' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Women\'s Blouse',
                'category_id' => $womensClothing->id,
                'sku' => 'WB-WHITE-S',
                'description' => 'Elegant white blouse for office wear',
                'price' => 34.99,
                'quantity' => 70,
                'is_active' => true,
            ],

            // Fitness
            [
                'name' => 'Yoga Mat',
                'category_id' => $fitness->id,
                'sku' => 'YM-PRO-BLUE',
                'description' => 'Professional non-slip yoga mat',
                'price' => 24.99,
                'quantity' => 150,
                'is_active' => true,
            ],
            [
                'name' => 'Dumbbell Set',
                'category_id' => $fitness->id,
                'sku' => 'DS-20KG-SET',
                'description' => 'Adjustable dumbbell set for home workouts',
                'price' => 89.99,
                'quantity' => 40,
                'is_active' => true,
            ],

            // Books
            [
                'name' => 'The Great Gatsby',
                'category_id' => $books->id,
                'sku' => 'BOOK-GATSBY-01',
                'description' => 'Classic novel by F. Scott Fitzgerald',
                'price' => 12.99,
                'quantity' => 200,
                'is_active' => true,
            ],
            [
                'name' => 'Python Programming Guide',
                'category_id' => $books->id,
                'sku' => 'BOOK-PYTHON-22',
                'description' => 'Comprehensive guide to Python programming',
                'price' => 35.99,
                'quantity' => 75,
                'is_active' => true,
            ],
            [
                'name' => 'Cookbook: Mediterranean Diet',
                'category_id' => $books->id,
                'sku' => 'BOOK-COOK-MED',
                'description' => 'Healthy Mediterranean diet recipes',
                'price' => 24.99,
                'quantity' => 90,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            // Generate slug from name
            $product['slug'] = Str::slug($product['name']);
            
            Product::create($product);
        }

        $this->command->info('Products seeded successfully!');
        $this->command->info('Total products created: ' . count($products));
    }
}