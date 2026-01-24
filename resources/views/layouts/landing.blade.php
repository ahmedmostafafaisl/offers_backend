@php
$locale = app()->getLocale();
$isRtl = $locale === 'ar';
@endphp

<!doctype html>
<html lang="{{ $locale }}" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Offers Platform in Saudi Arabia')</title>

    @if($isRtl)
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --brand: #F4C430;
            /* ✅ Gold */
            --brand-2: #E0B422;
            /* darker gold */
            --brand-soft: #FFF4CC;
            /* light gold background */
            --text: #1b1b1b;
        }

        body {
            background: #fff;
            color: var(--text);
        }

        .btn-brand {
            background: var(--brand);
            color: #111;
            border: 0;
        }

        .btn-brand:hover {
            background: var(--brand-2);
            color: #111;
        }

        .hero {
            background: linear-gradient(180deg, var(--brand-soft) 0%, #ffffff 70%);
        }

        .badge-soft {
            background: var(--brand-soft);
            color: #7a5b00;
        }

        .card-soft {
            border: 1px solid #eee;
            border-radius: 16px;
        }

        .nav-link {
            color: #333;
        }

        .nav-link:hover {
            color: #7a5b00;
        }

        /* icon circles (cards) */
        .icon-bubble {
            width: 44px;
            height: 44px;
            background: var(--brand-soft);
            color: #7a5b00;
        }

        /* optional: make active link gold if you have active styles */
        .nav-link.active {
            background: var(--brand-soft);
            color: #7a5b00 !important;
            border-radius: 14px;
            font-weight: 600;
        }
    </style>

</head>

<body>
    @yield('body')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
