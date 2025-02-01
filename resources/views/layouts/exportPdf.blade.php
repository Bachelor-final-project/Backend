<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>@yield('title', 'Export')</title>

   

    @stack('styles') <!-- Allows child views to push additional styles -->
</head>

<body>
    <div class="export-container">
        @yield('content')
    </div>
</body>

<script>
    window.print(); 
    </script>

</html>
