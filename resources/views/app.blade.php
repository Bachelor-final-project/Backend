<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- <link rel="manifest" href="/site.webmanifest"> -->

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes()
        @vite(["resources/js/app.js", "resources/js/Pages/{$page['component']}.vue", "resources/js/plugins/my.js"])
        @inertiaHead
    </head>
    <body class="dark:bg-gray-900 bg-gray-100 min-h-screen " dir="{{ Cookie::get('locale') == 'en' ? 'ltr' : 'rtl' }}">
        @inertia
    </body>
</html>
