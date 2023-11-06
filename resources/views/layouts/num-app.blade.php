<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<title>NUMBER</title>
<head>
    <x-header></x-header>
</head>
<body class="v-light vertical-nav fix-header fix-sidebar">
<x-pre-loader></x-pre-loader>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <x-footer></x-footer>
</body>
</html>
