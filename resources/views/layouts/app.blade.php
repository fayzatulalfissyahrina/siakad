<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIAKAD')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #0f3d2e;
            --accent: #f4b740;
            --bg: #f6f7fb;
        }
        body { background: var(--bg); }
        .sidebar { width: 260px; background: var(--brand); min-height: 100vh; color: #fff; }
        .sidebar a { color: #fff; text-decoration: none; }
        .content-wrap { min-height: 100vh; }
        .brand-badge { background: var(--accent); color: #1f1f1f; }
    </style>

    @stack('styles')
</head>
<body>
    @include('partials.header')

    <div class="d-flex">
        @include('partials.sidebar')

        <div class="flex-grow-1 content-wrap">
            @include('partials.navbar')

            <main class="container-fluid py-4">
                @yield('content')
            </main>

            @include('partials.footer')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>

    @stack('scripts')
</body>
</html>
