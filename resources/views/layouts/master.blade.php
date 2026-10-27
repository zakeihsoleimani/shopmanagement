
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <title>@yield('title', 'پنل مدیریت فروشگاه')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Tahoma, Arial, sans-serif;
            background-color: #f5f6fa;
            color: #2d3436;
        }

        /* Header */
        .header {
            background-color: #ffffff;
            padding: 20px 25px;
            border-bottom: 1px solid #e5e7eb;
        }

        .header h2 {
            margin: 0;
            font-size: 20px;
        }

        .header p {
            margin: 8px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        /* Main content */
        .main-container {
            padding: 25px;
        }

        .page-title {
            margin-top: 0;
            margin-bottom: 20px;
        }

        .content {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        .app-layout {
            display: flex;
            align-items: stretch;
            min-height: calc(100vh - 75px);
        }

        .main-container {
            flex: 1;
            min-width: 0;
            padding: 25px;
        }
    </style>

    @stack('styles')
</head>
<script src="{{ asset('js/sidebar.js') }}" defer></script>
<body>
    @include('layouts.header')
     <div class="app-layout">
        @include('layouts.sidebar')

        <main class="main-container">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

</body>
</html>
