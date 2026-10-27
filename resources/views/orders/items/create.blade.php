@extends('layouts.master')

@section('title', 'افزودن اقلام سفارش')
@section('header_title', 'افزودن اقلام سفارش')
@section('header_description', 'جستجو، اسکن و افزودن کالا به سفارش')

@section('content')

<div class="order-page" dir="rtl">

    {{-- هدر صفحه --}}
    <div class="order-heading">
        <div>
            <h1>افزودن اقلام سفارش</h1>
            <p>کالاها را اسکن یا جستجو کنید و به سفارش اضافه کنید.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="back-button outline-btn">
            بازگشت به سفارش‌ها
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="order-layout">
        {{-- ستون راست: جستجو و لیست اقلام --}}
        <div class="order-main-col">
            
            {{-- اطلاعات سفارش --}}
            <div class="order-card">
                <h2>اطلاعات سفارش</h2>
                <div class="order-info-grid">
                    <div class="order-info-item">
                        <span class="info-label">شماره سفارش</span>
                        <span class="info-value">{{ $order->order_number }}</span>
                    </div>
                    <div class="order-info-item">
                        <span class="info-label">مشتری</span>
                        <span class="info-value">{{ $order->customer?->name ?? 'نامشخص' }}</span>
                    </div>
                    <div class="order-info-item">
                        <span class="info-label">وضعیت</span>
                        <span class="info-value">{{ $order->status }}</span>
                    </div>
                </div>
            </div>

            {{-- جستجوی کالا --}}
                        {{-- جستجوی کالا --}}
            <div class="order-card">
                <h2>جستجو و انتخاب کالا</h2>
                <div id="product-search" data-search-url="{{ route('products.search') }}">
                    
                   
                    <div class="search-row">

                        <div class="form-group product-search-field" style="flex: 1;">
                            <label for="product_search">جستجوی کالا یا اسکن بارکد</label>

                            <input
                                type="search"
                                id="product_search"
                                placeholder="برای نمایش کالاها کلیک کنید یا جستجو کنید..."
                                autocomplete="off"
                            >

                            <div id="product_dropdown" class="product-dropdown" hidden>
                                <div id="search_message" role="status" aria-live="polite"></div>
                                <div id="product_results"></div>
                            </div>
                        </div>

                        <div class="form-group" style="flex: 0 0 200px;">
                            <label for="category_id">فیلتر دسته‌بندی</label>

                            <select id="category_id">
                                <option value="">همه دسته‌بندی‌ها</option>

                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                </div>
            </div>

            {{-- فرم افزودن کالای انتخاب‌شده --}}
            <div class="order-card" id="selected-product-card" style="display: none;">
                <h2>افزودن کالای انتخاب‌شده</h2>
                <div id="selected_product" class="selected-product-info"></div>
                <form action="{{ route('orders.items.store', ['order' => $order->id]) }}" method="POST" id="add_item_form">
                    @csrf
                    <input type="hidden" name="product_id" id="selected_product_id" required>
                    <div class="form-group" style="max-width: 200px;">
                        <label for="quantity">تعداد <span>*</span></label>
                        <input type="number" name="quantity" id="quantity" min="1" value="1" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="submit-button">افزودن به سفارش</button>
                    </div>
                </form>
            </div>

            {{-- اقلام اضافه‌شده (لیست سبد خرید) --}}
            <div class="order-card">
                <h2>اقلام سفارش</h2>
                
                <div class="cart-items-list">
                    @forelse ($order->items as $item)
                        <div class="cart-item">
                            <div class="cart-item-img">
                                {{-- اگر عکس محصول دارید اینجا قرار دهید، فعلا یک پلیس هولدر --}}
                                <div style="background: #eee; width: 60px; height: 80px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">تصویر</div>
                            </div>
                            <div class="cart-item-details">
                                <h3>{{ $item->product?->name ?? 'محصول حذف شده' }}</h3>
                                <p class="cart-item-price">
                                    @if($item->product && $item->product->old_price)
                                        <span class="old-price">{{ number_format($item->product->old_price) }}</span>
                                    @endif
                                    <span class="current-price">{{ number_format($item->unit_price) }} تومان</span>
                                </p>
                            </div>
                            <div class="cart-item-actions">
                                {{-- دکمه‌های افزایش/کاهش تعداد --}}
                                <div class="quantity-control">
                                    <button type="button" class="qty-btn minus">-</button>
                                    <span class="qty-value">{{ $item->quantity }}</span>
                                    <button type="button" class="qty-btn plus">+</button>
                                </div>
                                {{-- دکمه حذف --}}
                                <form action="{{ route('orders.items.destroy', ['order' => $order->id, 'item' => $item->id]) }}" method="POST" onsubmit="return confirm('این کالا از سفارش حذف شود؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn" title="حذف">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="empty-cart">
                            هنوز کالایی به سفارش اضافه نشده است.
                        </div>
                    @endforelse
                </div>

                @if($order->items->count() > 0)
                <div class="cart-actions-bottom">
                    <button type="button" class="clear-cart-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        حذف همه
                    </button>
                </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orderitem.css') }}">
@endpush

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {
    const searchContainer = document.getElementById('product-search');
    const searchInput = document.getElementById('product_search');
    const categorySelect = document.getElementById('category_id');
    const dropdown = document.getElementById('product_dropdown');
    const resultsContainer = document.getElementById('product_results');
    const message = document.getElementById('search_message');

    const selectedCard = document.getElementById('selected-product-card');
    const selectedProductBox = document.getElementById('selected_product');
    const selectedProductId = document.getElementById('selected_product_id');

    if (
        !searchContainer ||
        !searchInput ||
        !categorySelect ||
        !dropdown ||
        !resultsContainer ||
        !message ||
        !selectedCard ||
        !selectedProductBox ||
        !selectedProductId
    ) {
        console.error('عناصر جستجوی کالا پیدا نشدند.');
        return;
    }

    const searchUrl = searchContainer.dataset.searchUrl;
    let timer = null;

    async function searchProducts() {
        const keyword = searchInput.value.trim();
        const categoryId = categorySelect.value;

        const url = new URL(searchUrl, window.location.href);

        if (keyword) {
            url.searchParams.set('q', keyword);
        } else {
            url.searchParams.delete('q');
        }

        if (categoryId) {
            url.searchParams.set('category_id', categoryId);
        } else {
            url.searchParams.delete('category_id');
        }

        dropdown.hidden = false;
        message.textContent = 'در حال جستجوی کالاها...';
        resultsContainer.replaceChildren();

        try {
            const response = await fetch(url.toString(), {
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`خطای سرور: ${response.status}`);
            }

            const products = await response.json();

            if (products.length === 0) {
                message.textContent = 'کالایی پیدا نشد.';
                return;
            }

            message.textContent =
                `${products.length} کالا پیدا شد. برای انتخاب کلیک کنید.`;

            products.forEach(function (product) {
                const button = document.createElement('button');

                button.type = 'button';
                button.className = 'product-result-button';

                button.textContent =
                    `${product.name} - ${Number(product.price).toLocaleString('fa-IR')} تومان`;

                button.addEventListener('click', function () {
                    selectedProductId.value = product.id;

                    selectedProductBox.textContent =
                        `${product.name} - ${Number(product.price).toLocaleString('fa-IR')} تومان`;

                    selectedCard.style.display = 'block';

                    dropdown.hidden = true;

                    const quantityInput = document.getElementById('quantity');

                    if (quantityInput) {
                        quantityInput.focus();
                    }
                });

                resultsContainer.appendChild(button);
            });

        } catch (error) {
            console.error('خطای جستجوی کالا:', error);
            message.textContent = 'دریافت کالاها ناموفق بود.';
        }
    }

    // کلیک روی کادر، منو را باز می‌کند.
    searchInput.addEventListener('click', function () {
        if (dropdown.hidden) {
            searchProducts();
        }
    });

    // هنگام تایپ، نتایج پس از 300 میلی‌ثانیه به‌روز می‌شوند.
    searchInput.addEventListener('input', function () {
        selectedProductId.value = '';
        selectedCard.style.display = 'none';

        clearTimeout(timer);

        timer = setTimeout(function () {
            searchProducts();
        }, 300);
    });

    // تغییر دسته‌بندی، فهرست را فیلتر می‌کند.
    categorySelect.addEventListener('change', function () {
        searchProducts();
    });

    // اسکنرهای بارکد معمولاً مقدار را وارد و Enter ارسال می‌کنند.
    searchInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            clearTimeout(timer);
            searchProducts();
        }
    });

    // کلیک خارج از بخش جستجو، منو را می‌بندد.
    document.addEventListener('click', function (event) {
        if (!searchContainer.contains(event.target)) {
            dropdown.hidden = true;
        }
    });

    // عمداً در زمان بارگذاری صفحه، searchProducts() اجرا نمی‌شود.
});

</script>
@endpush
