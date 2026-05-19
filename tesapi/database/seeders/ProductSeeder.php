<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->get()->keyBy('slug');

        $products = [
            [
                'name' => 'Novel Senja di Jakarta',
                'category_slug' => 'fiksi',
                'price' => 85000,
                'stock' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'Non-Fiksi: Manajemen Waktu',
                'category_slug' => 'non-fiksi',
                'price' => 98000,
                'stock' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Buku Pelajaran Matematika SMA',
                'category_slug' => 'buku-pelajaran',
                'price' => 120000,
                'stock' => 18,
                'is_active' => true,
            ],
            [
                'name' => 'Komik Petualangan Nusantara',
                'category_slug' => 'komik',
                'price' => 45000,
                'stock' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'Pulpen Gel Hitam 0.5',
                'category_slug' => 'alat-tulis',
                'price' => 6000,
                'stock' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'Pensil 2B Premium',
                'category_slug' => 'alat-tulis',
                'price' => 4500,
                'stock' => 120,
                'is_active' => true,
            ],
            [
                'name' => 'Buku Tulis 58 Lembar',
                'category_slug' => 'kertas-buku-tulis',
                'price' => 7500,
                'stock' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'Kertas A4 80gsm (500 lembar)',
                'category_slug' => 'kertas-buku-tulis',
                'price' => 56000,
                'stock' => 40,
                'is_active' => true,
            ],
            [
                'name' => 'Stapler Mini',
                'category_slug' => 'perlengkapan-kantor',
                'price' => 22000,
                'stock' => 35,
                'is_active' => true,
            ],
            [
                'name' => 'Stabilo Kuning',
                'category_slug' => 'alat-tulis',
                'price' => 9000,
                'stock' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'Map Plastik A4',
                'category_slug' => 'aksesoris',
                'price' => 4000,
                'stock' => 90,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            $category = $categories->get($product['category_slug']);
            if (! $category) {
                continue;
            }

            Product::updateOrCreate(
                ['name' => $product['name'], 'category_id' => $category->id],
                [
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'is_active' => $product['is_active'],
                ]
            );
        }

        if ($categories->isNotEmpty()) {
            Product::factory()
                ->count(8)
                ->state(fn () => ['category_id' => $categories->random()->id])
                ->create();
        }
    }
}
