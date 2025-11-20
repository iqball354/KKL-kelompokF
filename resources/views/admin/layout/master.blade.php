<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- CSS global -->
    <link rel="stylesheet" href="{{ asset('css/darkmode.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>

    {{-- Sidebar otomatis tampil --}}
    @include('partials.sidebar')

    {{-- Konten tiap halaman --}}
    <div class="content">
        @yield('content')
    </div>

    <!-- JS Darkmode -->
    <script src="{{ asset('js/darkmode.js') }}"></script>
</body>

</html>