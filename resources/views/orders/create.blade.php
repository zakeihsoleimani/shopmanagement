
@extends('layouts.master')

@section('title', 'ثبت سفارش جدید')
@section('header_title', 'ثبت سفارش جدید')
@section('header_description', 'ابتدا مشتری سفارش را انتخاب کنید')

@section('content')

<div class="order-page">

    <div class="order-heading">
        <div>
            <h1>ایجاد سفارش جدید</h1>
            <p>مشتری موردنظر را جستجو و انتخاب کنید.</p>
        </div>

        <a href="{{ route('orders.index') }}" class="back-button">
            بازگشت به سفارش‌ها
        </a>
    </div>

 
<div class="form-group customer-search-field" id="customer-search-container">

    <label for="customer_search">
        جستجو و انتخاب مشتری <span>*</span>
    </label>

    <input
        type="search"
        id="customer_search"
        placeholder="برای دیدن همه مشتری‌ها کلیک کنید..."
        autocomplete="off"
        value=""
    >

    <input
        type="hidden"
        name="customer_id"
        id="customer_id"
        value="{{ old('customer_id') }}"
    >

    <div id="customer_dropdown" class="customer-dropdown" hidden>
        <div id="customer_search_message" class="customer-search-message">
            در حال نمایش مشتری‌ها...
        </div>

        <div id="customer_results">
            @foreach ($customers as $customer)
                @php
                    $mobile = $customer->mobile
                        ?? $customer->mobile_number
                        ?? $customer->phone
                        ?? $customer->phone_number
                        ?? '';
                @endphp

                <button
                    type="button"
                    class="customer-result-button"
                    data-id="{{ $customer->id }}"
                    data-search="{{ $customer->name }} {{ $mobile }}"
                    data-label="{{ $customer->name }}{{ $mobile ? ' - ' . $mobile : '' }}"
                >
                    <span class="customer-result-name">
                        {{ $customer->name }}
                    </span>

                    @if ($mobile)
                        <span class="customer-result-mobile">
                            {{ $mobile }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <div id="selected_customer_info" class="selected-customer-info" hidden></div>

    <small>با کلیک روی کادر، همه مشتری‌ها نمایش داده می‌شوند.</small>
</div>


</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('customer-search-container');
    const searchInput = document.getElementById('customer_search');
    const customerIdInput = document.getElementById('customer_id');
    const dropdown = document.getElementById('customer_dropdown');
    const results = document.getElementById('customer_results');
    const message = document.getElementById('customer_search_message');
    const selectedInfo = document.getElementById('selected_customer_info');
    const form = searchInput?.closest('form');

    if (!container || !searchInput || !customerIdInput ||
        !dropdown || !results || !message || !selectedInfo) {
        console.error('عناصر جستجوی مشتری پیدا نشدند.');
        return;
    }

    const buttons = Array.from(
        results.querySelectorAll('.customer-result-button')
    );

    function normalize(value) {
        return String(value ?? '')
            .toLocaleLowerCase()
            .replace(/[۰-۹]/g, digit => String('۰۱۲۳۴۵۶۷۸۹'.indexOf(digit)))
            .replace(/[٠-٩]/g, digit => String('٠١٢٣٤٥٦٧٨٩'.indexOf(digit)))
            .trim();
    }

    function filterCustomers() {
        const term = normalize(searchInput.value);
        let count = 0;

        buttons.forEach(function (button) {
            const searchableText = normalize(button.dataset.search);
            const matches = searchableText.includes(term);

            button.hidden = !matches;

            if (matches) {
                count++;
            }
        });

        message.textContent = count
            ? `${count} مشتری پیدا شد. برای انتخاب کلیک کنید.`
            : 'مشتری موردنظر پیدا نشد.';

        dropdown.hidden = false;
    }

    // کلیک روی کادر، فهرست مشتری‌ها را باز می‌کند.
    searchInput.addEventListener('focus', function () {
        if (
            customerIdInput.value &&
            buttons.some(button =>
                button.dataset.id === customerIdInput.value
            ) &&
            searchInput.value
        ) {
            searchInput.value = '';
            customerIdInput.value = '';
            selectedInfo.hidden = true;
        }

        filterCustomers();
    });

    // با تایپ، مشتری‌ها فیلتر می‌شوند.
    searchInput.addEventListener('input', function () {
        customerIdInput.value = '';
        selectedInfo.hidden = true;
        searchInput.setCustomValidity('');

        filterCustomers();
    });

    // انتخاب مشتری از فهرست
    results.addEventListener('click', function (event) {
        const button = event.target.closest('.customer-result-button');

        if (!button) {
            return;
        }

        customerIdInput.value = button.dataset.id;
        searchInput.value = button.dataset.label;

        selectedInfo.textContent =
            `مشتری انتخاب‌شده: ${button.dataset.label}`;

        selectedInfo.hidden = false;
        dropdown.hidden = true;
        searchInput.setCustomValidity('');
    });

    // کلیک بیرون از جستجو، فهرست را می‌بندد.
    document.addEventListener('click', function (event) {
        if (!container.contains(event.target)) {
            dropdown.hidden = true;
        }
    });

    // جلوگیری از ثبت سفارش بدون انتخاب واقعی مشتری
    if (form) {
        form.addEventListener('submit', function (event) {
            if (!customerIdInput.value) {
                event.preventDefault();

                searchInput.setCustomValidity(
                    'لطفاً یک مشتری از فهرست انتخاب کنید.'
                );

                searchInput.reportValidity();
                searchInput.focus();
                dropdown.hidden = false;
                filterCustomers();
            }
        });
    }

    // بازیابی انتخاب قبلی، مثلاً بعد از خطای اعتبارسنجی
    const oldCustomer = buttons.find(
        button => button.dataset.id === customerIdInput.value
    );

    if (oldCustomer) {
        searchInput.value = oldCustomer.dataset.label;
        selectedInfo.textContent =
            `مشتری انتخاب‌شده: ${oldCustomer.dataset.label}`;
        selectedInfo.hidden = false;
    }
});

</script>
@endpush
