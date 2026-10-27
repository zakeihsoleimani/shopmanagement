
@extends('layouts.master')

@section('title', 'جزئیات سفارش')
@section('header_title', 'جزئیات سفارش')
@section('header_description', 'مشاهده اطلاعات مشتری و محصولات سفارش')

@section('content')

<div class="order-page">

    <div class="order-heading">
        <div>
            <h1>جزئیات سفارش</h1>
            <p>اطلاعات سفارش ثبت‌شده را مشاهده کن.</p>
        </div>

        <a href="{{ route('orders.index') }}" class="back-button">
            بازگشت به فهرست سفارش‌ها
        </a>
    </div>

    <div class="order-card">

        <h2>اطلاعات سفارش</h2>

        <div class="order-info-grid">

            <div class="order-info-item">
                <span class="info-label">شماره سفارش</span>
                <span class="info-value">
                    {{ $order->order_number ?? 'ثبت نشده' }}
                </span>
            </div>

            <div class="order-info-item">
                <span class="info-label">نام مشتری</span>
                <span class="info-value">
                    {{ $order->customer?->name ?? 'نامشخص' }}
                </span>
            </div>

            <div class="order-info-item">
                <span class="info-label">تاریخ ثبت سفارش</span>
                <span class="info-value">
                    {{ $order->created_at?->format('Y/m/d H:i') ?? 'نامشخص' }}
                </span>
            </div>

            <div class="order-info-item">
                <span class="info-label">وضعیت سفارش</span>
                <span class="info-value">
                    @switch($order->status)
                        @case('pending')
                            در انتظار
                            @break

                        @case('completed')
                            تکمیل‌شده
                            @break

                        @case('cancelled')
                            لغوشده
                            @break

                        @default
                            {{ $order->status }}
                    @endswitch
                </span>
            </div>

            <div class="order-info-item">
                <span class="info-label">آدرس مشتری</span>
                <span class="info-value">
                    {{ $order->address ?: 'ثبت نشده' }}
                </span>
            </div>

        </div>

        <h3>محصولات سفارش</h3>

        <div class="table-wrapper">
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>نام محصول</th>
                        <th>تعداد</th>
                        <th>قیمت واحد</th>
                        <th>مبلغ کل</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($order->items as $item)
                        <tr>
                            <td>
                                {{ $item->product?->name ?? 'محصول پیدا نشد' }}
                            </td>

                            <td>{{ $item->quantity }}</td>

                            <td>
                                {{ number_format($item->unit_price) }} تومان
                            </td>

                            <td>
                                {{ number_format($item->subtotal) }} تومان
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                محصولی برای این سفارش ثبت نشده است.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="order-total">
            مبلغ کل سفارش:
            {{ number_format($order->total_amount) }} تومان
        </div>

    </div>

</div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/order_show.css') }}">
@endpush
