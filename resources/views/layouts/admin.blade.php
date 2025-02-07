<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panel Administratora' }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body class="bg-gray-100">
<!-- Logo -->
<header class="bg-blue-800 text-white py-4" style="height: 80px;">
    <div class="container mx-auto px-4 flex justify-between items-center">
        @include('components.logo-admin')
    </div>
</header>


<!-- Główna zawartość -->
<main class="container mx-auto px-4 py-6">
    @yield('content')
</main>


<!-- Stopka -->
<footer class="text-center py-4">
    @include('components.footer') <!-- Dodanie stopki -->
</footer>
</body>
</html>
