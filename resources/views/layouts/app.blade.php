<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Geografis')</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Tambahkan CSS Aplikasi Anda -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('css')
    <!-- Stack untuk CSS tambahan -->
</head>

<body>
    <div class="container">
        @yield('content')
        <!-- Konten dinamis dari halaman lain -->
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Tambahkan JS Aplikasi Anda -->
    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')
    <!-- Stack untuk JavaScript tambahan -->
</body>

</html>