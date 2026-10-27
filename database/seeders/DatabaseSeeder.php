<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // دسته‌بندی‌ها
        $categories = [
            [
                'name' => 'لوازم جانبی',
                'slug' => 'accessories',
                'description' => 'لوازم جانبی عمومی',
                'is_active' => true,
            ],
            [
                'name' => 'لوازم اداری',
                'slug' => 'office-supplies',
                'description' => 'تجهیزات اداری',
                'is_active' => true,
            ],
            [
                'name' => 'تجهیزات دیجیتال',
                'slug' => 'digital-equipment',
                'description' => 'ابزارهای دیجیتال',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // مشتری‌ها
        $customers = [
            [
                'name' => 'علی رضایی',
                'mobile' => '09121000001',
                'email' => 'ali.rezaei@example.com',
                'address' => 'تهران، ولیعصر',
            ],
            [
                'name' => 'مریم احمدی',
                'mobile' => '09121000002',
                'email' => 'maryam.ahmadi@example.com',
                'address' => 'کرج، عظیمیه',
            ],
            [
                'name' => 'رضا محمدی',
                'mobile' => '09121000003',
                'email' => 'reza.mohammadi@example.com',
                'address' => 'مشهد، احمدآباد',
            ],
            [
                'name' => 'سارا کریمی',
                'mobile' => '09121000004',
                'email' => 'sara.karimi@example.com',
                'address' => 'اصفهان، مرداویج',
            ],
            [
                'name' => 'حسین جعفری',
                'mobile' => '09121000005',
                'email' => 'hossein.jafari@example.com',
                'address' => 'شیراز، معالی‌آباد',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::updateOrCreate(
                ['mobile' => $customer['mobile']],
                $customer
            );
        }

        // محصولات
        $products = [
            [
                'category' => 'accessories',
                'name' => 'ماوس بی‌سیم',
                'sku' => 'ACC-1001',
                'price' => 850000,
                'description' => 'ماوس بی‌سیم مشکی',
            ],
            [
                'category' => 'accessories',
                'name' => 'کیبورد سیمی',
                'sku' => 'ACC-1002',
                'price' => 620000,
                'description' => 'کیبورد فارسی USB',
            ],
            [
                'category' => 'office-supplies',
                'name' => 'دفتر ۱۰۰ برگ',
                'sku' => 'OFF-2001',
                'price' => 95000,
                'description' => 'دفتر خط‌دار ۱۰۰ برگ',
            ],
            [
                'category' => 'office-supplies',
                'name' => 'خودکار آبی',
                'sku' => 'OFF-2002',
                'price' => 25000,
                'description' => 'خودکار آبی',
            ],
            [
                'category' => 'digital-equipment',
                'name' => 'فلش مموری ۳۲ گیگابایت',
                'sku' => 'DIG-3001',
                'price' => 420000,
                'description' => 'فلش مموری USB',
            ],
        ];

        foreach ($products as $product) {
            $category = Category::where(
                'slug',
                $product['category']
            )->firstOrFail();

            Product::updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'sku' => $product['sku'],
                    'price' => $product['price'],
                    'description' => $product['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}


