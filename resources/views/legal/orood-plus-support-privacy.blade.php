@extends('layouts.landing')

@section('title', $site->t('brand.app_name') . ' - ' . (app()->getLocale() === 'ar' ? 'الدعم وسياسة الخصوصية' : 'Support & Privacy Policy'))

@section('body')
    @php
        $isAr = app()->getLocale() === 'ar';
        $email = $site->get('contact.email');
        $phone = $site->get('contact.phone');
        $faq = $site->faq();
        $lastUpdated = $site->get('legal.last_updated');
    @endphp

    {{-- Simple header --}}
    <nav class="navbar navbar-expand-lg bg-white border-bottom py-3">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('landing') }}">
                @if ($site->logoUrl())
                    <img src="{{ $site->logoUrl() }}" alt="{{ $site->t('brand.app_name') }}" style="height:38px;width:auto;max-width:160px;object-fit:contain;">
                @else
                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center"
                        style="width:34px;height:34px;background:#efeaff;color:#f4c430;">
                        <i class="bi bi-stars"></i>
                    </span>
                @endif
                <span>{{ $site->t('brand.app_name') }}</span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-{{ $isAr ? 'end' : 'left' }}"></i>
                    {{ $isAr ? 'الرئيسية' : 'Home' }}
                </a>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-translate"></i> {{ strtoupper(app()->getLocale()) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="hero py-5">
        <div class="container">
            <h1 class="fw-bold mb-2">{{ $isAr ? 'الدعم وسياسة الخصوصية' : 'Support & Privacy Policy' }}</h1>
            <p class="text-muted mb-0">
                {{ $isAr ? 'تطبيق ' . $site->t('brand.app_name') : $site->t('brand.app_name') . ' Application' }} &middot;
                {{ $isAr ? 'آخر تحديث: ' : 'Last updated: ' }}<span dir="ltr">{{ $lastUpdated }}</span>
            </p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            {{-- Side navigation --}}
            <div class="col-lg-3 mb-4">
                <div class="list-group sticky-top" style="top: 20px;">
                    <a href="#support" class="list-group-item list-group-item-action">
                        <i class="bi bi-headset {{ $isAr ? 'ms-2' : 'me-2' }}"></i>{{ $isAr ? 'الدعم' : 'Support' }}
                    </a>
                    <a href="#privacy" class="list-group-item list-group-item-action">
                        <i class="bi bi-shield-check {{ $isAr ? 'ms-2' : 'me-2' }}"></i>{{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                    </a>
                </div>
            </div>

            {{-- Content --}}
            <div class="col-lg-9">

                {{-- ============ SUPPORT ============ --}}
                <section id="support" class="mb-5">
                    <h2 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-headset badge-soft rounded-circle p-2 {{ $isAr ? 'ms-2' : 'me-2' }}"></i>
                        {{ $isAr ? 'الدعم الفني' : 'Support' }}
                    </h2>

                    <div class="legal-content">{{ $site->richText('support.intro') }}</div>

                    @if ($email || $phone)
                        <div class="row g-3 my-3">
                            @if ($email)
                                <div class="col-md-6">
                                    <div class="card-soft p-3 h-100">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="icon-bubble rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <strong>{{ $isAr ? 'البريد الإلكتروني' : 'Email' }}</strong>
                                        </div>
                                        <a href="mailto:{{ $email }}" class="text-decoration-none">{{ $email }}</a>
                                    </div>
                                </div>
                            @endif
                            @if ($phone)
                                <div class="col-md-6">
                                    <div class="card-soft p-3 h-100">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <span class="icon-bubble rounded-circle d-inline-flex align-items-center justify-content-center">
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            <strong>{{ $isAr ? 'الهاتف / واتساب' : 'Phone / WhatsApp' }}</strong>
                                        </div>
                                        <span class="text-muted" dir="ltr">{{ $phone }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if (count($faq))
                        <h5 class="fw-bold mt-4">{{ $isAr ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}</h5>
                        <div class="accordion mt-2" id="faqAccordion">
                            @foreach ($faq as $i => $item)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                                            {{ $isAr ? $item['q_ar'] : $item['q_en'] }}
                                        </button>
                                    </h2>
                                    <div id="faq{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body legal-content">
                                            {{ $site->markdown($isAr ? $item['a_ar'] : $item['a_en']) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                {{-- ============ PRIVACY POLICY ============ --}}
                <section id="privacy">
                    <h2 class="fw-bold mb-3 pb-2 border-bottom">
                        <i class="bi bi-shield-check badge-soft rounded-circle p-2 {{ $isAr ? 'ms-2' : 'me-2' }}"></i>
                        {{ $isAr ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                    </h2>

                    <div class="legal-content">{{ $site->richText('privacy.body') }}</div>
                </section>
            </div>
        </div>
    </div>

    <style>
        .legal-content h1, .legal-content h2, .legal-content h3, .legal-content h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 1.5rem 0 .5rem;
        }
        .legal-content p { margin-bottom: .75rem; }
        .legal-content ul, .legal-content ol { margin-bottom: 1rem; }
        .legal-content a { word-break: break-word; }
        .legal-content > :first-child { margin-top: 0; }
    </style>
@endsection
