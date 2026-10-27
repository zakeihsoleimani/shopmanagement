@extends('layouts.master')

@section('title', 'سفارش‌های ثبت‌شده')
@section('header_title', 'نمایش سفارش‌های ثبت‌شده')

@section('content')

<div class="order-page">

    <div class="order-card">
        <h2>فهرست سفارش‌ها</h2>

        @if ($orders->isEmpty())
            <p>هنوز سفارشی ثبت نشده است.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>شناسه</th>
                        <th>شماره سفارش</th>
                        <th>مشتری</th>
                        <th>وضعیت</th>
                        <th>مبلغ کل</th>
                        <th>تاریخ ثبت</th>
                        <th>جزییات سفارش</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>

                            <td>{{ $order->order_number }}</td>

                            <td>
                                {{ $order->customer?->name ?? 'نامشخص' }}
                            </td>

                            <td>{{ $order->status }}</td>

                            <td>
                                {{ number_format($order->total_amount) }}
                                تومان
                            </td>

                            <td>
                                {{ $order->created_at->format('Y/m/d') }}
                            </td>
                            <td>
                               <a href="{{route('orders.show',$order->id)}}">
                                نمایش جزییات
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>

@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/orders_index.css') }}">
@endpush