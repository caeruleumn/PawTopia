<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Cat Supplies
        $catSupplies = [
            ['name' => 'Cat Litter Box with Hood', 'price' => 250000, 'description' => 'Hooded litter box with carbon filter for odor control'],
            ['name' => 'Cat Scratching Post Tower', 'price' => 450000, 'description' => 'Multi-level scratching post with sisal rope and hanging toys'],
            ['name' => 'Cat Carrier Backpack', 'price' => 350000, 'description' => 'Bubble window cat carrier backpack for travel'],
            ['name' => 'Cat Grooming Brush Set', 'price' => 85000, 'description' => 'Complete grooming set with slicker brush and comb'],
            ['name' => 'Cat Interactive Laser Toy', 'price' => 125000, 'description' => 'Automatic laser toy to keep your cat entertained'],
            ['name' => 'Cat Ceramic Food Bowl Set', 'price' => 95000, 'description' => 'Non-slip ceramic bowl set for food and water'],
            ['name' => 'Cat Tunnel Play Toy', 'price' => 180000, 'description' => 'Collapsible tunnel toy with hanging balls'],
            ['name' => 'Cat Nail Clipper Professional', 'price' => 65000, 'description' => 'Stainless steel nail clipper with safety guard'],
        ];

        // Dog Supplies
        $dogSupplies = [
            ['name' => 'Dog Leash and Collar Set', 'price' => 150000, 'description' => 'Durable nylon leash and collar with reflective strips'],
            ['name' => 'Dog Training Pads 100pcs', 'price' => 275000, 'description' => 'Super absorbent training pads with leak-proof backing'],
            ['name' => 'Dog Chew Toys Rubber Set', 'price' => 135000, 'description' => 'Durable rubber chew toys for dental health'],
            ['name' => 'Dog Grooming Kit Professional', 'price' => 420000, 'description' => 'Complete grooming kit with clipper, scissors, and brush'],
            ['name' => 'Dog Water Bottle Portable', 'price' => 85000, 'description' => 'Leak-proof portable water bottle for walks'],
            ['name' => 'Dog Bed Orthopedic Large', 'price' => 550000, 'description' => 'Memory foam orthopedic bed with removable cover'],
            ['name' => 'Dog Harness No-Pull', 'price' => 175000, 'description' => 'Adjustable no-pull harness with padded chest'],
            ['name' => 'Dog Poop Bag Dispenser', 'price' => 45000, 'description' => 'Dispenser with 120 biodegradable poop bags'],
        ];

        // Cat Vitamins
        $catVitamins = [
            ['name' => 'Cat Multivitamin Tablets 60pcs', 'price' => 185000, 'description' => 'Complete multivitamin formula for overall cat health'],
            ['name' => 'Cat Omega-3 Fish Oil', 'price' => 225000, 'description' => 'Premium fish oil for healthy skin and coat'],
            ['name' => 'Cat Hairball Control Supplement', 'price' => 165000, 'description' => 'Natural fiber supplement to reduce hairballs'],
            ['name' => 'Cat Immune Booster Powder', 'price' => 195000, 'description' => 'Immune system support with vitamins and probiotics'],
            ['name' => 'Cat Joint Support Chewable', 'price' => 245000, 'description' => 'Glucosamine and chondroitin for joint health'],
            ['name' => 'Cat Probiotic Digestive Aid', 'price' => 175000, 'description' => 'Probiotic supplement for digestive health'],
        ];

        // Dog Vitamins
        $dogVitamins = [
            ['name' => 'Dog Multivitamin Chewable 90pcs', 'price' => 215000, 'description' => 'Tasty chewable multivitamin for dogs of all sizes'],
            ['name' => 'Dog Hip and Joint Supplement', 'price' => 285000, 'description' => 'Advanced formula with glucosamine for joint support'],
            ['name' => 'Dog Omega-3 Soft Chews', 'price' => 235000, 'description' => 'Omega-3 soft chews for skin and coat health'],
            ['name' => 'Dog Probiotic Powder', 'price' => 195000, 'description' => 'Probiotic powder to support digestive health'],
            ['name' => 'Dog Immune Support Tablets', 'price' => 205000, 'description' => 'Immune system boost with antioxidants'],
            ['name' => 'Dog Calming Supplement', 'price' => 225000, 'description' => 'Natural calming supplement for anxiety relief'],
            ['name' => 'Dog Dental Health Chews', 'price' => 165000, 'description' => 'Dental chews with vitamins for oral health'],
        ];

        // Insert Cat Supplies
        foreach ($catSupplies as $item) {
            Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'category' => 'Cat Supplies',
                'sku' => 'CS-' . strtoupper(Str::random(6)),
                'price' => $item['price'],
                'stock' => rand(10, 100),
                'status' => 'available',
                'description' => $item['description'],
                'image_path' => null,
            ]);
        }

        // Insert Dog Supplies
        foreach ($dogSupplies as $item) {
            Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'category' => 'Dog Supplies',
                'sku' => 'DS-' . strtoupper(Str::random(6)),
                'price' => $item['price'],
                'stock' => rand(10, 100),
                'status' => 'available',
                'description' => $item['description'],
                'image_path' => null,
            ]);
        }

        // Insert Cat Vitamins
        foreach ($catVitamins as $item) {
            Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'category' => 'Cat Vitamin',
                'sku' => 'CV-' . strtoupper(Str::random(6)),
                'price' => $item['price'],
                'stock' => rand(20, 150),
                'status' => 'available',
                'description' => $item['description'],
                'image_path' => null,
            ]);
        }

        // Insert Dog Vitamins
        foreach ($dogVitamins as $item) {
            Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'category' => 'Dog Vitamin',
                'sku' => 'DV-' . strtoupper(Str::random(6)),
                'price' => $item['price'],
                'stock' => rand(20, 150),
                'status' => 'available',
                'description' => $item['description'],
                'image_path' => null,
            ]);
        }

        // Generate additional random products for variety (Cat Food & Dog Food)
        Product::factory()->count(20)->state(function () {
            return [
                'category' => fake()->randomElement(['Cat Food', 'Dog Food']),
            ];
        })->create();
    }
}
