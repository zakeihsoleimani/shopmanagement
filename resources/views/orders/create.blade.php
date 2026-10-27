@extends('layouts.master')

@section('title', 'ثبت سفارش جدید')
@section('header_title', 'ثبت سفارش جدید')
@section('header_description', 'مشتری را انتخاب کنید یا مشتری جدید بسازید.')

@section('content')

<div class="order-page">

    {{-- عنوان صفحه --}}
    <div class="order-heading">
        <div>
            <h1>ایجاد سفارش جدید</h1>
            <p>ابتدا مشتری سفارش را انتخاب کنید.</p>
        </div>

        <a href="{{ route('orders.index') }}" class="back-button">
            بازگشت به سفارش‌ها
        </a>
    </div>

    {{-- فرم اصلی ثبت سفارش --}}
    <form
        id="customer-order-form"
        action="{{ route('orders.store') }}"
        method="POST"
    >
        @csrf

        <div class="form-group customer-search-field"
             id="customer-search-container">

            <label for="customer_search">
                جستجو و انتخاب مشتری <span>*</span>
            </label>

            <input
                type="search"
                id="customer_search"
                placeholder="نام یا شماره موبایل مشتری را وارد کنید..."
                autocomplete="off"
                aria-autocomplete="list"
                aria-controls="customer_results"
                value=""
            >

            {{-- شناسه مشتری انتخاب‌شده به کنترلر ارسال می‌شود --}}
            <input
                type="hidden"
                name="customer_id"
                id="customer_id"
                value="{{ old('customer_id') }}"
            >

            {{-- فهرست مشتری‌ها --}}
            <div
                id="customer_dropdown"
                class="customer-dropdown"
                hidden
            >
                <div
                    id="customer_search_message"
                    class="customer-search-message"
                >
                    برای انتخاب، روی نام مشتری کلیک کنید.
                </div>

                <div id="customer_results">

                    @foreach ($customers as $customer)

                        <button
                            type="button"
                            class="customer-result-button"
                            data-id="{{ $customer->id }}"
                            data-mobile="{{ $customer->mobile ?? '' }}"
                            data-search="{{ $customer->name }} {{ $customer->mobile ?? '' }}"
                            data-label="{{ $customer->name }}{{ $customer->mobile ? ' - ' . $customer->mobile : '' }}"
                        >
                            <span class="customer-result-name">
                                {{ $customer->name }}
                            </span>

                            @if ($customer->mobile)
                                <span class="customer-result-mobile">
                                    {{ $customer->mobile }}
                                </span>
                            @endif
                        </button>

                    @endforeach

                </div>
            </div>

            {{-- نمایش مشتری انتخاب‌شده --}}
            <div
                id="selected_customer_info"
                class="selected-customer-info"
                hidden
            ></div>

            <small>
                برای دیدن همه مشتری‌ها روی کادر کلیک کنید.
            </small>

            @error('customer_id')
                <div class="error-message">
                    {{ $message }}
                </div>
            @enderror

        </div>

        {{-- افزودن مشتری جدید --}}
        <div class="new-customer-section">

            <button
                type="button"
                id="toggle-new-customer"
                class="back-button"
            >
                ＋ افزودن مشتری جدید
            </button>

            <div
                id="new-customer-panel"
                class="new-customer-panel"
                data-store-url="{{ route('customers.quick-store') }}"
                hidden
            >
                <h3>ثبت مشتری جدید</h3>

                <div class="form-group">
                    <label for="new_customer_name">
                        نام و نام خانوادگی *
                    </label>

                    <input
                        type="text"
                        id="new_customer_name"
                        placeholder="نام مشتری"
                        maxlength="255"
                        autocomplete="name"
                    >
                </div>

                <div class="form-group">
                    <label for="new_customer_mobile">
                        شماره موبایل *
                    </label>

                    <input
                        type="tel"
                        id="new_customer_mobile"
                        placeholder="شماره موبایل مشتری"
                        maxlength="20"
                        autocomplete="tel"
                    >
                </div>

                <div class="form-group">
                    <label for="new_customer_email">
                        ایمیل
                    </label>

                    <input
                        type="email"
                        id="new_customer_email"
                        placeholder="ایمیل مشتری (اختیاری)"
                        autocomplete="email"
                    >
                </div>

                <div class="form-group">
                    <label for="new_customer_address">
                        آدرس
                    </label>

                    <textarea
                        id="new_customer_address"
                        rows="2"
                        placeholder="آدرس مشتری (اختیاری)"
                    ></textarea>
                </div>

                <div
                    id="new_customer_message"
                    role="status"
                    aria-live="polite"
                ></div>

                <button
                    type="button"
                    id="save-new-customer"
                    class="submit-button"
                >
                    ذخیره و انتخاب مشتری
                </button>

            </div>
        </div>

        {{-- دکمه‌های فرم اصلی --}}
        <div class="order-actions">

            <button
                type="button"
                id="clear-customer"
                class="back-button"
            >
                پاک کردن انتخاب
            </button>

            <button
                type="submit"
                class="submit-button"
            >
                ایجاد سفارش و افزودن اقلام
            </button>

        </div>

    </form>

</div>

@endsection


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // -----------------------------------------
    // عناصر جستجو و انتخاب مشتری
    // -----------------------------------------

    const container = document.getElementById('customer-search-container');
    const searchInput = document.getElementById('customer_search');
    const customerIdInput = document.getElementById('customer_id');
    const dropdown = document.getElementById('customer_dropdown');
    const results = document.getElementById('customer_results');
    const searchMessage = document.getElementById('customer_search_message');
    const selectedInfo = document.getElementById('selected_customer_info');
    const orderForm = document.getElementById('customer-order-form');
    const clearButton = document.getElementById('clear-customer');

    // عناصر افزودن مشتری جدید
    const toggleNewCustomer = document.getElementById('toggle-new-customer');
    const newCustomerPanel = document.getElementById('new-customer-panel');
    const saveNewCustomer = document.getElementById('save-new-customer');
    const newCustomerMessage = document.getElementById('new_customer_message');

    const newName = document.getElementById('new_customer_name');
    const newMobile = document.getElementById('new_customer_mobile');
    const newEmail = document.getElementById('new_customer_email');
    const newAddress = document.getElementById('new_customer_address');

    if (
        !container ||
        !searchInput ||
        !customerIdInput ||
        !dropdown ||
        !results ||
        !searchMessage ||
        !selectedInfo ||
        !orderForm
    ) {
        console.error('عناصر ضروری فرم انتخاب مشتری پیدا نشدند.');
        return;
    }

    // -----------------------------------------
    // یکسان‌سازی ارقام و حروف فارسی و عربی
    // -----------------------------------------

    function normalizeDigits(value) {
        return String(value ?? '')
            .replace(/[۰-۹]/g, function (digit) {
                return String('۰۱۲۳۴۵۶۷۸۹'.indexOf(digit));
            })
            .replace(/[٠-٩]/g, function (digit) {
                return String('٠١٢٣٤٥٦٧٨٩'.indexOf(digit));
            });
    }

    function normalizeText(value) {
        return normalizeDigits(value)
            .toLocaleLowerCase()
            .replace(/ي/g, 'ی')
            .replace(/ى/g, 'ی')
            .replace(/ك/g, 'ک')
            .replace(/\s+/g, ' ')
            .trim();
    }

    function digitsOnly(value) {
        return normalizeDigits(value).replace(/\D/g, '');
    }

    // همه مشتری‌ها، از جمله مشتری‌های تازه‌ساخته‌شده
    function getCustomerButtons() {
        return Array.from(
            results.querySelectorAll('.customer-result-button')
        );
    }

    // -----------------------------------------
    // جستجو با نام یا شماره موبایل
    // -----------------------------------------

    function filterCustomers() {
        const rawTerm = searchInput.value.trim();
        const term = normalizeText(rawTerm);
        const searchedDigits = digitsOnly(rawTerm);

        let count = 0;

        getCustomerButtons().forEach(function (button) {
            const searchableText = normalizeText(
                (button.dataset.search || '') + ' ' +
                (button.dataset.label || '') + ' ' +
                (button.dataset.mobile || '')
            );

            const customerMobile = digitsOnly(
                button.dataset.mobile || ''
            );

            const matchesNameOrText =
                term === '' || searchableText.includes(term);

            const matchesMobile =
                searchedDigits.length > 0 &&
                customerMobile.includes(searchedDigits);

            const matches =
                matchesNameOrText || matchesMobile;

            button.hidden = !matches;

            if (matches) {
                count++;
            }
        });

        if (count > 0) {
            searchMessage.textContent =
                count + ' مشتری پیدا شد؛ برای انتخاب کلیک کنید.';
        } else {
            searchMessage.textContent =
                'مشتری پیدا نشد. می‌توانید مشتری جدید اضافه کنید.';
        }

        dropdown.hidden = false;
    }

    // -----------------------------------------
    // انتخاب مشتری
    // -----------------------------------------

    function selectCustomer(button) {
        customerIdInput.value = button.dataset.id;
        searchInput.value = button.dataset.label;

        selectedInfo.textContent =
            'مشتری انتخاب‌شده: ' + button.dataset.label;

        selectedInfo.hidden = false;
        dropdown.hidden = true;

        searchInput.setCustomValidity('');
    }

    searchInput.addEventListener('focus', filterCustomers);
    searchInput.addEventListener('click', filterCustomers);

    searchInput.addEventListener('input', function () {
        // با تغییر عبارت، انتخاب قبلی دیگر معتبر نیست.
        customerIdInput.value = '';
        selectedInfo.hidden = true;
        searchInput.setCustomValidity('');

        filterCustomers();
    });

    // انتخاب نتیجه با کلیک
    results.addEventListener('click', function (event) {
        const button = event.target.closest('.customer-result-button');

        if (!button || button.hidden) {
            return;
        }

        selectCustomer(button);
    });

    // بستن فهرست هنگام کلیک در بیرون
    document.addEventListener('click', function (event) {
        if (!container.contains(event.target)) {
            dropdown.hidden = true;
        }
    });

    // -----------------------------------------
    // پاک کردن انتخاب مشتری
    // -----------------------------------------

    if (clearButton) {
        clearButton.addEventListener('click', function () {
            customerIdInput.value = '';
            searchInput.value = '';
            selectedInfo.hidden = true;
            dropdown.hidden = true;

            searchInput.setCustomValidity('');

            getCustomerButtons().forEach(function (button) {
                button.hidden = false;
            });
        });
    }

    // -----------------------------------------
    // بررسی انتخاب مشتری قبل از ثبت سفارش
    // -----------------------------------------

    orderForm.addEventListener('submit', function (event) {
        if (!customerIdInput.value) {
            event.preventDefault();

            searchInput.setCustomValidity(
                'لطفاً یک مشتری را از فهرست انتخاب کنید.'
            );

            searchInput.reportValidity();
            searchInput.focus();
            filterCustomers();
        }
    });

    // بازیابی مشتری انتخاب‌شده در صورت خطای اعتبارسنجی
    const oldCustomer = getCustomerButtons().find(function (button) {
        return button.dataset.id === customerIdInput.value;
    });

    if (oldCustomer) {
        selectCustomer(oldCustomer);
    }

    // -----------------------------------------
    // باز و بسته کردن بخش مشتری جدید
    // -----------------------------------------

    if (
        toggleNewCustomer &&
        newCustomerPanel &&
        saveNewCustomer &&
        newCustomerMessage &&
        newName &&
        newMobile &&
        newEmail &&
        newAddress
    ) {
        toggleNewCustomer.addEventListener('click', function () {
            newCustomerPanel.hidden = !newCustomerPanel.hidden;

            if (!newCustomerPanel.hidden) {
                newName.focus();
            }
        });

        // جلوگیری از ثبت ناخواسته سفارش با Enter
        [newName, newMobile, newEmail, newAddress].forEach(function (input) {
            input.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && input.tagName !== 'TEXTAREA') {
                    event.preventDefault();
                }
            });
        });

        // -----------------------------------------
        // ذخیره مشتری جدید با درخواست AJAX
        // -----------------------------------------

        saveNewCustomer.addEventListener('click', async function () {
            const name = newName.value.trim();
            const mobile = normalizeDigits(newMobile.value).trim();
            const email = newEmail.value.trim();
            const address = newAddress.value.trim();

            newCustomerMessage.textContent = '';

            if (!name || !mobile) {
                newCustomerMessage.textContent =
                    'نام و شماره موبایل مشتری الزامی است.';
                return;
            }

            const token = orderForm.querySelector(
                'input[name="_token"]'
            )?.value;

            if (!token) {
                newCustomerMessage.textContent =
                    'توکن امنیتی فرم پیدا نشد. صفحه را بررسی کنید.';
                return;
            }

            const storeUrl = newCustomerPanel.dataset.storeUrl;

            if (!storeUrl) {
                newCustomerMessage.textContent =
                    'آدرس ذخیره مشتری مشخص نشده است.';
                return;
            }

            saveNewCustomer.disabled = true;

            const originalButtonText = saveNewCustomer.textContent;
            saveNewCustomer.textContent = 'در حال ذخیره...';

            try {
                const response = await fetch(storeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        name: name,
                        mobile: mobile,
                        email: email || null,
                        address: address || null
                    })
                });

                const data = await response.json();

                if (!response.ok) {
                    const validationErrors = data.errors
                        ? Object.values(data.errors).flat().join(' ')
                        : '';

                    newCustomerMessage.textContent =
                        validationErrors ||
                        data.message ||
                        'ثبت مشتری ناموفق بود.';

                    return;
                }

                const customer = data.customer;

                if (!customer || !customer.id) {
                    newCustomerMessage.textContent =
                        'پاسخ سرور معتبر نیست. متد ذخیره مشتری را بررسی کنید.';
                    return;
                }

                // اگر مشتری از قبل در فهرست باشد، گزینه تکراری نساز.
                let customerButton = getCustomerButtons().find(
                    function (button) {
                        return button.dataset.id === String(customer.id);
                    }
                );

                if (!customerButton) {
                    customerButton = document.createElement('button');
                    customerButton.type = 'button';
                    customerButton.className = 'customer-result-button';

                    const nameSpan = document.createElement('span');
                    nameSpan.className = 'customer-result-name';
                    nameSpan.textContent = customer.name;

                    const mobileSpan = document.createElement('span');
                    mobileSpan.className = 'customer-result-mobile';
                    mobileSpan.textContent = customer.mobile || '';

                    customerButton.append(nameSpan, mobileSpan);
                    results.appendChild(customerButton);
                }

                customerButton.dataset.id = String(customer.id);
                customerButton.dataset.mobile = customer.mobile || '';
                customerButton.dataset.search =
                    customer.name + ' ' + (customer.mobile || '');

                customerButton.dataset.label =
                    customer.name +
                    (customer.mobile ? ' - ' + customer.mobile : '');

                customerButton.hidden = false;

                // انتخاب خودکار مشتری تازه‌ساخته‌شده
                selectCustomer(customerButton);

                // بستن پنل و پاک کردن اطلاعات ورودی
                newCustomerPanel.hidden = true;

                newName.value = '';
                newMobile.value = '';
                newEmail.value = '';
                newAddress.value = '';

                newCustomerMessage.textContent = '';

            } catch (error) {
                console.error('خطا در ذخیره مشتری:', error);

                newCustomerMessage.textContent =
                    'ارتباط با سرور برقرار نشد. دوباره تلاش کنید.';
            } finally {
                saveNewCustomer.disabled = false;
                saveNewCustomer.textContent = originalButtonText;
            }
        });
    }

});
</script>
@endpush