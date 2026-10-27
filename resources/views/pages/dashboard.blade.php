
@extends('layouts.master')

@section('title', 'داشبورد')

@section('header_title', 'داشبورد مدیریت')

@section('header_description', 'خوش آمدید به پنل مدیریت')

@section('content')
    <h1 class="page-title">داشبورد</h1>

    <div class="dashboard-grid">

        <a href="{{ route('orders.create') }}" class="dashboard-card">
            <div class="dashboard-card-icon">🛒</div>

            <div class="dashboard-card-info">
                <h3>ثبت سفارش</h3>
                <p>ایجاد سفارش جدید برای مشتریان</p>
            </div>

            <span class="dashboard-card-arrow">←</span>
        </a>
        <a href="{{ route('orders.index') }}" class="dashboard-card">
            <div class="dashboard-card-icon">🛒</div>

            <div class="dashboard-card-info">
                <h3>نمایش سفارش</h3>
              
            </div>

            <span class="dashboard-card-arrow">←</span>
        </a>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

