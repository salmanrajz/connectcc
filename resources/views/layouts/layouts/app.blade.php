<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <x-header></x-header>
</head>
<body>
<x-pre-loader></x-pre-loader>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <x-footer></x-footer>
</body>
</html>
