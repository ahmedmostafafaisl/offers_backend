@extends('layouts.landing')

@section('title', app()->getLocale() === 'ar' ? 'منصة العروض داخل السعودية' : 'Offers Platform in Saudi Arabia')

@section('body')
    {{-- Header --}}
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('landing') }}">
                <span class="rounded-circle d-inline-flex align-items-center justify-content-center"
                    style="width:34px;height:34px;background:#efeaff;color:#f4c430;">
                    <i class="bi bi-stars"></i>
                </span>
                <span>
                    {{ app()->getLocale() === 'ar'
        ? 'منصة عروض في المملكة العربية السعودية'
        : 'Offers Platform in Saudi Arabia' }}
                </span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLanding">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navLanding">
                <ul class="navbar-nav mx-auto gap-2">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            {{ app()->getLocale() === 'ar' ? 'من نحن' : 'About' }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#offers">
                            {{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">
                            {{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            {{ app()->getLocale() === 'ar' ? 'تواصل' : 'Contact' }}
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    {{-- Language --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-translate"></i> {{ strtoupper(app()->getLocale()) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                            <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                        </ul>
                    </div>

                    @auth
                        <a class="btn btn-brand btn-sm" href="{{ url('/dashboard') }}">
                            <i class="bi bi-grid"></i> {{ app()->getLocale() === 'ar' ? 'لوحة التحكم' : 'Dashboard' }}
                        </a>
                    @else
                        <a class="btn btn-brand btn-sm" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i>
                            {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero py-5" id="about">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">
                        {{ app()->getLocale() === 'ar' ? 'منصة عروض داخل السعودية' : 'Offers Platform in Saudi Arabia' }}
                    </span>

                    <h1 class="fw-bold mb-3" style="line-height:1.2">
                        {{ app()->getLocale() === 'ar'
        ? 'اعثر على أفضل العروض والخدمات بالقرب منك'
        : 'Find the best offers and services near you' }}
                    </h1>

                    <p class="text-muted mb-4">
                        {{ app()->getLocale() === 'ar'
        ? 'نقدّم عروضًا مميزة من مزودين موثوقين داخل المملكة العربية السعودية مع تجربة سهلة وسريعة.'
        : 'We provide curated offers from trusted providers across Saudi Arabia with a simple and fast experience.' }}
                    </p>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="#offers" class="btn btn-brand">
                            <i class="bi bi-bag"></i>
                            {{ app()->getLocale() === 'ar' ? 'استعرض العروض' : 'Browse Offers' }}
                        </a>
                        <a href="#contact" class="btn btn-outline-secondary">
                            {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                        </a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card-soft overflow-hidden">
                        @if($sliders->count())
                            <div id="landingSlider" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach($sliders as $k => $s)
                                        <div class="carousel-item {{ $k == 0 ? 'active' : '' }}">
                                            <img class="d-block w-100" style="height:320px;object-fit:contain;background:#fff;"
                                                src="{{ asset('storage/' . $s->image) }}" alt="slide">
                                        </div>
                                    @endforeach
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#landingSlider"
                                    data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#landingSlider"
                                    data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                        @else
                            <div class="p-5 text-center text-muted">
                                {{ app()->getLocale() === 'ar' ? 'لا توجد صور سلايدر بعد' : 'No slider images yet' }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ✅ About Section (Re-designed) --}}
    <section class="py-5 bg-light" id="about-platform">
        <div class="container">
            <div class="row g-4 align-items-stretch">

                {{-- Left: Text --}}
                <div class="col-lg-7">
                    <div class="card-soft p-4 h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge rounded-pill" style="background:#FFF4CC;color:#111;">
                                {{ app()->getLocale() === 'ar' ? 'حول' : 'About' }}
                            </span>
                            <span class="text-muted small">
                                {{ app()->getLocale() === 'ar' ? 'تعرف على فكرة المنصة ومميزاتها' : 'Learn what the platform offers' }}
                            </span>
                        </div>

                        <h3 class="fw-bold mb-3">
                            {{ app()->getLocale() === 'ar' ? 'كل العروض… في مكان واحد' : 'All offers… in one place' }}
                        </h3>

                        <p class="text-muted mb-3" style="line-height:1.9;">
                            {{ app()->getLocale() === 'ar'
        ? 'اكتشف أقوى العروض اليومية حولك من المتاجر والمطاعم والصوالين وأكثر — بسرعة وسهولة. بدون بحث طويل… افتح التطبيق وشوف عروض اليوم حسب موقعك.'
        : 'Discover the best daily deals around you from stores, restaurants, salons and more — quickly and easily. No long searching… open the app and browse today’s offers based on your location.' }}
                        </p>

                        {{-- Launch Offer --}}
                        <div class="p-3 rounded-4 border" style="background:#fff;">
                            <div class="fw-bold mb-1">
                                <i class="bi bi-megaphone"></i>
                                {{ app()->getLocale() === 'ar' ? 'عرض التدشين — لفترة محدودة' : 'Launch offer — limited time' }}
                            </div>
                            <div class="text-muted" style="line-height:1.9;">
                                {{ app()->getLocale() === 'ar'
        ? 'أول 3 أشهر مجانًا لأصحاب المتاجر والمطاعم — بدون بطاقة وبدون دفع.'
        : 'First 3 months FREE for stores & restaurants — no credit card, no payment.' }}
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            {{-- For users --}}
                            <div class="col-md-6">
                                <div class="fw-bold mb-2">
                                    <i class="bi bi-person-check"></i>
                                    {{ app()->getLocale() === 'ar' ? 'للمستخدمين' : 'For users' }}
                                </div>
                                <ul class="text-muted mb-0" style="line-height:1.9;">
                                    <li>{{ app()->getLocale() === 'ar' ? 'عروض حصرية يوميًا بالقرب منك' : 'Daily exclusive offers nearby' }}
                                    </li>
                                    <li>{{ app()->getLocale() === 'ar' ? 'اختيار المدينة والفئة بسهولة' : 'Easy city & category selection' }}
                                    </li>
                                    <li>{{ app()->getLocale() === 'ar' ? 'تنبيهات فورية عند نزول عروض جديدة' : 'Instant notifications for new offers' }}
                                    </li>
                                    <li>{{ app()->getLocale() === 'ar' ? 'تجربة سريعة وواجهة بسيطة' : 'Fast, simple experience' }}
                                    </li>
                                </ul>
                            </div>

                            {{-- For providers --}}
                            <div class="col-md-6">
                                <div class="fw-bold mb-2">
                                    <i class="bi bi-shop"></i>
                                    {{ app()->getLocale() === 'ar' ? 'لأصحاب المتاجر' : 'For stores' }}
                                </div>
                                <ul class="text-muted mb-0" style="line-height:1.9;">
                                    <li>{{ app()->getLocale() === 'ar' ? 'أوصل عروضك لآلاف العملاء في مدينتك' : 'Reach thousands of customers in your city' }}
                                    </li>
                                    <li>{{ app()->getLocale() === 'ar' ? 'لوحة تحكم سهلة لإضافة العروض' : 'Easy dashboard to publish offers' }}
                                    </li>
                                    <li>{{ app()->getLocale() === 'ar' ? 'إحصائيات مشاهدة وتفاعل' : 'Views & engagement analytics' }}
                                    </li>
                                    <li>{{ app()->getLocale() === 'ar' ? 'باقات مرنة: 3 / 6 أشهر / سنة' : 'Flexible plans: 3 / 6 months / 1 year' }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: How it works --}}
                <div class="col-lg-5">
                    <div class="card-soft p-4 h-100">
                        <h5 class="fw-bold mb-3">
                            {{ app()->getLocale() === 'ar' ? 'كيف يشتغل؟' : 'How it works' }}
                        </h5>

                        <div class="d-flex flex-column gap-3">
                            @php
                                $stepsAr = [
                                    ['icon' => 'bi-download', 't' => 'حمّل التطبيق', 'd' => 'ابدأ خلال دقائق.'],
                                    ['icon' => 'bi-geo-alt', 't' => 'اختر مدينتك', 'd' => 'عشان تشوف العروض القريبة منك.'],
                                    ['icon' => 'bi-grid', 't' => 'تصفح حسب التصنيف', 'd' => 'مطاعم، متاجر، صوالين… إلخ.'],
                                    ['icon' => 'bi-eye', 't' => 'شوف تفاصيل العرض', 'd' => 'السعر، المكان، ومدة العرض.'],
                                    ['icon' => 'bi-bag-check', 't' => 'زور المتجر ووفّر', 'd' => 'استمتع بالعرض مباشرة.'],
                                ];
                                $stepsEn = [
                                    ['icon' => 'bi-download', 't' => 'Download the app', 'd' => 'Get started in minutes.'],
                                    ['icon' => 'bi-geo-alt', 't' => 'Choose your city', 'd' => 'See nearby offers instantly.'],
                                    ['icon' => 'bi-grid', 't' => 'Browse by category', 'd' => 'Restaurants, stores, salons, etc.'],
                                    ['icon' => 'bi-eye', 't' => 'View offer details', 'd' => 'Price, location, and validity.'],
                                    ['icon' => 'bi-bag-check', 't' => 'Visit & save', 'd' => 'Enjoy the deal right away.'],
                                ];
                                $steps = app()->getLocale() === 'ar' ? $stepsAr : $stepsEn;
                            @endphp

                            @foreach($steps as $i => $st)
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="rounded-4 d-flex align-items-center justify-content-center flex-shrink-0"
                                        style="width:42px;height:42px;background:#FFF4CC;color:#111;">
                                        <i class="bi {{ $st['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $i + 1 }}. {{ $st['t'] }}</div>
                                        <div class="text-muted small" style="line-height:1.7;">{{ $st['d'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4">

                        <div class="d-flex flex-wrap gap-2">
                            <a href="#offers" class="btn btn-brand">
                                <i class="bi bi-bag"></i>
                                {{ app()->getLocale() === 'ar' ? 'استعرض العروض' : 'Browse offers' }}
                            </a>
                            <a href="#contact" class="btn btn-outline-secondary">
                                {{ app()->getLocale() === 'ar' ? 'تواصل معنا' : 'Contact' }}
                            </a>
                        </div>

                        <div class="mt-3 text-muted small">
                            <i class="bi bi-envelope"></i> Oroodapplication@gmail.com
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Featured Offers --}}
    <section class="py-5" id="offers">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between mb-3">
                <div>
                    <h3 class="fw-bold mb-1">{{ app()->getLocale() === 'ar' ? 'عروض مختارة' : 'Featured Offers' }}</h3>
                    <div class="text-muted">
                        {{ app()->getLocale() === 'ar' ? 'اخترنا لك أفضل العروض النشطة' : 'A curated list of active offers' }}
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @forelse($offers as $offer)
                    @php
                        $img = $offer->images->first()?->image;
                        $imgUrl = $img ? asset('storage/' . $img) : 'https://via.placeholder.com/600x400?text=Offer';
                        $priceAfter = $offer->price_after ?? $offer->price ?? null;
                        $city = app()->getLocale() === 'ar'
                            ? ($offer->city_ar ?? $offer->city ?? '')
                            : ($offer->city_en ?? $offer->city ?? '');
                    @endphp

                    <div class="col-md-6 col-lg-3">
                        <div class="card-soft h-100 overflow-hidden">
                            <img class="w-100 offer-img" src="{{ $imgUrl }}" alt="offer">
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-start gap-2">
                                    <div class="fw-semibold text-truncate" title="{{ $offer->name }}">{{ $offer->name }}</div>
                                    @if($offer->category)
                                        <span class="badge badge-soft">{{ $offer->category->name }}</span>
                                    @endif
                                </div>

                                <div class="text-muted small mt-1 text-truncate">
                                    {{ $city }}
                                </div>

                                <div class="mt-3 d-flex justify-content-between align-items-center">
                                    <div class="fw-bold">
                                        @if($priceAfter !== null)
                                            {{ number_format((float) $priceAfter, 2) }} SAR
                                        @else
                                            —
                                        @endif
                                    </div>
                                    <span class="text-muted small">
                                        <i class="bi bi-heart"></i> {{ $offer->likes ?? 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد عروض بعد.' : 'No offers yet.' }}
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="py-5 bg-light" id="categories">
        <div class="container">
            <div class="d-flex align-items-end justify-content-between mb-3">
                <div>
                    <h3 class="fw-bold mb-1">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</h3>
                    <div class="text-muted">{{ app()->getLocale() === 'ar' ? 'تصفح حسب التصنيف' : 'Browse by category' }}
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @forelse($categories as $cat)
                    @php
                        $imgUrl = $cat->image ? asset('storage/' . $cat->image) : 'https://via.placeholder.com/400x300?text=Category';
                    @endphp

                    <div class="col-6 col-md-3">
                        <div class="card-soft p-3 h-100">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $imgUrl }}" alt="cat"
                                    style="width:54px;height:54px;object-fit:cover;border-radius:14px;">
                                <div class="fw-semibold">{{ $cat->name }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد تصنيفات بعد.' : 'No categories yet.' }}
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-5" id="contact" style="background:#fff;color:#111;">
        <div class="container">
            <div class="row g-4 align-items-start">

                {{-- Right: About --}}
                <div class="col-md-4 text-md-end">
                    <div class="fw-bold mb-2" style="font-size:18px;">
                        {{ app()->getLocale() === 'ar'
        ? 'منصة عروض في المملكة العربية السعودية'
        : 'Offers Platform in Saudi Arabia' }}
                    </div>
                    <div class="text-muted" style="line-height:1.8;">
                        {{ app()->getLocale() === 'ar'
        ? 'منصة تساعدك على اكتشاف أفضل العروض والخدمات بالقرب منك.'
        : 'A platform that helps you discover the best offers and services near you.' }}
                    </div>
                </div>

                {{-- Middle: Links --}}
                <div class="col-md-4 text-center">
                    <div class="fw-bold mb-2">{{ app()->getLocale() === 'ar' ? 'روابط' : 'Links' }}</div>
                    <div class="d-flex flex-column gap-2">
                        <a href="#about" class="footer-link">{{ app()->getLocale() === 'ar' ? 'من نحن' : 'About' }}</a>
                        <a href="#offers" class="footer-link">{{ app()->getLocale() === 'ar' ? 'العروض' : 'Offers' }}</a>
                        <a href="#categories"
                            class="footer-link">{{ app()->getLocale() === 'ar' ? 'التصنيفات' : 'Categories' }}</a>
                    </div>
                </div>

                {{-- Left: Contact --}}
                <div class="col-md-4">
                    <div class="fw-bold mb-2">{{ app()->getLocale() === 'ar' ? 'تواصل' : 'Contact' }}</div>

                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="text-muted">support@offersplatform.sa</span>
                        <i class="bi bi-envelope"></i>
                    </div>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="text-muted">+966 50 000 0000</span>
                        <i class="bi bi-telephone"></i>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <a href="#" class="footer-icon" aria-label="X"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="footer-icon" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="footer-icon" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    </div>
                </div>
            </div>

            <hr class="my-4" style="border-color:#d7d7df;">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="text-muted d-flex align-items-center gap-2">
                    <span style="font-size:18px;">🇸🇦</span>
                    <span>{{ app()->getLocale() === 'ar' ? 'صُنع داخل السعودية' : 'Made in Saudi Arabia' }}</span>
                </div>

                <div class="text-muted text-md-end">
                    © {{ date('Y') }}
                    {{ app()->getLocale() === 'ar' ? 'منصة العروض داخل السعودية' : 'Offers Platform in Saudi Arabia' }}.
                    {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}
                </div>
            </div>
        </div>
    </footer>

    <style>
        .footer-link {
            color: var(--brand, #F4C430);
            text-decoration: underline;
            font-weight: 600;
            width: fit-content;
            margin: 0 auto;
        }

        .footer-link:hover {
            opacity: .85;
        }

        .footer-icon {
            color: var(--brand, #F4C430);
            font-size: 18px;
            text-decoration: none;
        }

        .footer-icon:hover {
            opacity: .85;
        }
    </style>
@endsection
