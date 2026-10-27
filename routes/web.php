
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Models\Product;

// صفحه اصلی و داشبورد
Route::get('/', function () {
    return view('pages.dashboard');
})->name('pages.dashboard');


// -------------------------
// سفارش‌ها
// -------------------------

// فرم ایجاد سفارش جدید؛ انتخاب مشتری
Route::get('/orders/create', [OrderController::class, 'create'])
    ->name('orders.create');

// ذخیره سفارش و انتقال به صفحه افزودن اقلام
Route::post('/orders', [OrderController::class, 'store'])
    ->name('orders.store');

// فهرست سفارش‌ها
Route::get('/orders', [OrderController::class, 'index'])
    ->name('orders.index');

// صفحه افزودن اقلام به یک سفارش مشخص
Route::get('/orders/{order}/items/create', [
    OrderController::class,
    'createItems',
])->name('orders.items.create');

// جزئیات یک سفارش مشخص
Route::get('/orders/{order}', [OrderController::class, 'show'])
    ->name('orders.show');


// -------------------------
// محصولات و دسته‌بندی‌ها
// -------------------------

// دریافت همه محصولات فعال برای جستجو و انتخاب
Route::get('/products', function () {
    return Product::where('is_active', true)
        ->select('id', 'name', 'price')
        ->get();
})->name('products.index');

// دریافت محصولات یک دسته‌بندی
Route::get('/categories/{category}/products', [
    CategoryController::class,
    'products',
])->name('categories.products');
// جستجوی کالا با نام یا کد
Route::get('/products/search', [OrderController::class, 'searchProducts'])
    ->name('products.search');

// افزودن کالا به سفارش
Route::post('/orders/{order}/items', [OrderController::class, 'storeItem'])
    ->name('orders.items.store');

// حذف کالا از سفارش
Route::delete('/orders/{order}/items/{item}', [
    OrderController::class,
    'destroyItem',
])->name('orders.items.destroy');
//افزودن مشتری
Route::post('/customers/quick-store', [
    OrderController::class,
    'storeCustomer',
])->name('customers.quick-store');