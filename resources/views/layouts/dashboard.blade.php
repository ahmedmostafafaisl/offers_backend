@php
$locale = app()->getLocale();
$isRtl = $locale === 'ar';
@endphp

<!doctype html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap 5 --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    @if($isRtl)
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>@yield('title', 'Dashboard')</title>

<style>
 :root{
  --brand:#F4C430;
  --brand-soft:#FFF4CC;
}
.brand-pill{ background:var(--brand); color:#111; }
.nav-link.active{ background:var(--brand); color:#111 !important; }
.nav-link:hover{ background:var(--brand-soft); }

    body {
        background: #f6f7fb;
    }

    .dash-sidebar {
        flex: 0 0 270px;
        /* ✅ important */
        width: 270px;
        min-width: 270px;
        /* ✅ important */
        background: #fff;
        border-right: 1px solid #eee;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow: auto;
        flex-shrink: 0;
        /* ✅ important */
    }

    .brand-pill {
        background: var(--brand);
        color: #fff;
        border-radius: 14px;
        padding: 12px 14px;
    }

    .nav-link.active {
        background: var(--brand);
        color: #fff !important;
        border-radius: 14px;
    }

    .nav-link {
        color: #444;
        border-radius: 14px;
        padding: 10px 12px;
        white-space: nowrap;
    }

    .nav-link:hover {
        background: var(--brand-soft);
        color: #222;
    }

    .topbar {
        background: #fff;
        border-bottom: 1px solid #eee;
    }

    .card-soft {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 16px;
    }
</style>
<style>
    /* fix sidebar border direction */
    [dir="rtl"] .dash-sidebar {
        border-left: 1px solid #eee;
        border-right: 0;
    }

    [dir="ltr"] .dash-sidebar {
        border-right: 1px solid #eee;
        border-left: 0;
    }

    /* text alignment small helper */
    [dir="rtl"] .text-start {
        text-align: right !important;
    }

    [dir="rtl"] .text-end {
        text-align: left !important;
    }
</style>
<style>
    /* ✅ prevent any page from breaking width */
    .flex-grow-1 {
        min-width: 0;
    }

    main {
        min-width: 0;
        overflow-x: auto;
    }

    /* ✅ tables always responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
    }

    /* ✅ prevent any image/svg from forcing layout */
    img,
    svg,
    canvas {
        max-width: 100%;
        height: auto;
    }

    /* ✅ fix huge icons issue (like your big arrow) */
    .bi {
        font-size: 1rem !important;
        line-height: 1;
    }

    .btn .bi {
        font-size: 1rem !important;
    }

    .pagination .page-link {
        line-height: 1.2;
    }

    
</style>

</head>

<body>
<div class="d-flex align-items-stretch">
            @include('components.sidebar')

    <div class="flex-grow-1" style="min-width:0;">
            @include('components.topbar')

    <main class="p-4">
                @include('dashboard.partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>

</html>
