<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>



    <!-- Styles -->
    @include('partials.styles')
    @stack('styles')
</head>

<body>

    <main>
        @include('layouts.navbar')
        @yield('content')
    </main>

    @include('layouts.footer')

    <!-- Scripts -->
    @include('partials.scripts')
    @stack('scripts')
</body>

</html>