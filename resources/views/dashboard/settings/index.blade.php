{{-- resources/views/dashboard/settings/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', __('site.site_settings'))
@section('page_title', __('site.site_settings'))

@section('content')
    @php
        $tabs = [
            'branding' => 'bi-image',
            'contact'  => 'bi-telephone',
            'social'   => 'bi-share',
            'support'  => 'bi-headset',
            'privacy'  => 'bi-shield-check',
            'featured' => 'bi-star',
        ];
    @endphp

    <div class="d-flex flex-wrap align-items-start justify-content-between gap-2 mb-3">
        <div>
            <h3 class="mb-1">{{ __('site.site_settings') }}</h3>
            <div class="text-muted small">{{ __('site.site_settings_subtitle') }}</div>
        </div>
        <a href="{{ route('landing') }}" target="_blank" class="btn btn-light border">
            <i class="bi bi-box-arrow-up-right"></i> {{ __('site.view_website') }}
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs --}}
    <ul class="nav nav-pills gap-1 mb-3 flex-wrap">
        @foreach ($tabs as $key => $icon)
            <li class="nav-item">
                <a class="nav-link {{ $tab === $key ? 'active' : '' }}"
                   href="{{ route('dashboard.settings.index', ['tab' => $key]) }}">
                    <i class="bi {{ $icon }} me-1"></i> {{ __('site.tab_' . $key) }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="card-soft p-4">

        {{-- ====================== BRANDING ====================== --}}
        @if ($tab === 'branding')
            @php $logo = $site->logoUrl(); @endphp
            <form method="POST" action="{{ route('dashboard.settings.update', 'branding') }}" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-12">
                    <label class="form-label fw-semibold">{{ __('site.logo') }}</label>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="border rounded-3 p-2 bg-white d-flex align-items-center justify-content-center" style="min-width:90px;height:90px;">
                            @if ($logo)
                                <img src="{{ $logo }}" alt="logo" style="max-height:74px;max-width:200px;">
                            @else
                                <span class="text-muted small"><i class="bi bi-stars"></i> {{ __('site.default_icon') }}</span>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="logo" accept="image/png,image/jpeg,image/webp" class="form-control">
                            <div class="text-muted small mt-1">{{ __('site.logo_hint') }}</div>
                            @if ($logo)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="removeLogo">
                                    <label class="form-check-label" for="removeLogo">{{ __('site.remove_logo') }}</label>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @foreach (['name' => 'brand_name', 'site_name' => 'site_name', 'app_name' => 'app_name'] as $field => $label)
                    <div class="col-md-6">
                        <label class="form-label">{{ __('site.' . $label) }} — {{ __('site.arabic') }}</label>
                        <input name="{{ $field }}_ar" dir="rtl" class="form-control" maxlength="255"
                               value="{{ old($field . '_ar', $site->get('brand.' . $field . '_ar')) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ __('site.' . $label) }} — {{ __('site.english') }}</label>
                        <input name="{{ $field }}_en" dir="ltr" class="form-control" maxlength="255"
                               value="{{ old($field . '_en', $site->get('brand.' . $field . '_en')) }}">
                    </div>
                @endforeach
                <div class="col-12 text-muted small">{{ __('site.blank_default') }}</div>

                <div class="col-12">
                    <button class="btn btn-dark"><i class="bi bi-check2"></i> {{ __('site.save') }}</button>
                </div>
            </form>

        {{-- ====================== CONTACT ====================== --}}
        @elseif ($tab === 'contact')
            <form method="POST" action="{{ route('dashboard.settings.update', 'contact') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">{{ __('site.email') }}</label>
                    <input type="email" name="email" dir="ltr" class="form-control" maxlength="255"
                           value="{{ old('email', $site->get('contact.email')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('site.phone') }}</label>
                    <input name="phone" dir="ltr" class="form-control" maxlength="30"
                           value="{{ old('phone', $site->get('contact.phone')) }}">
                </div>
                <div class="col-12 text-muted small">{{ __('site.hidden_if_blank') }}</div>
                <div class="col-12">
                    <button class="btn btn-dark"><i class="bi bi-check2"></i> {{ __('site.save') }}</button>
                </div>
            </form>

        {{-- ====================== SOCIAL ====================== --}}
        @elseif ($tab === 'social')
            <form method="POST" action="{{ route('dashboard.settings.update', 'social') }}" class="row g-3">
                @csrf
                @foreach ($platforms as $platform => $meta)
                    <div class="col-md-6">
                        <label class="form-label"><i class="bi {{ $meta['icon'] }} me-1"></i> {{ $meta['label'] }}</label>
                        <input name="{{ $platform }}" dir="ltr" class="form-control" maxlength="255" placeholder="https://"
                               value="{{ old($platform, $site->get('social.' . $platform)) }}">
                    </div>
                @endforeach
                <div class="col-12 text-muted small">{{ __('site.social_hint') }}</div>
                <div class="col-12">
                    <button class="btn btn-dark"><i class="bi bi-check2"></i> {{ __('site.save') }}</button>
                </div>
            </form>

        {{-- ====================== SUPPORT ====================== --}}
        @elseif ($tab === 'support')
            @php $faqRows = old('faq', $faq); @endphp
            <form method="POST" action="{{ route('dashboard.settings.update', 'support') }}" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">{{ __('site.support_intro') }} — {{ __('site.arabic') }}</label>
                    <textarea name="intro_ar" dir="rtl" rows="5" class="form-control">{{ old('intro_ar', $site->get('support.intro_ar')) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('site.support_intro') }} — {{ __('site.english') }}</label>
                    <textarea name="intro_en" dir="ltr" rows="5" class="form-control">{{ old('intro_en', $site->get('support.intro_en')) }}</textarea>
                </div>
                <div class="col-12 text-muted small">{{ __('site.markdown_help') }} {{ __('site.blank_default') }}</div>

                <div class="col-12"><hr class="my-1"></div>

                <div class="col-12 d-flex justify-content-between align-items-center">
                    <div class="fw-semibold">{{ __('site.faq') }}</div>
                    <button type="button" class="btn btn-light border btn-sm" id="addFaq">
                        <i class="bi bi-plus-lg"></i> {{ __('site.add_faq') }}
                    </button>
                </div>

                <div class="col-12" id="faqList">
                    @foreach ($faqRows as $i => $row)
                        <div class="card-soft p-3 mb-2 faqRow">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label class="form-label small">{{ __('site.faq_question') }} — {{ __('site.arabic') }}</label>
                                    <input name="faq[{{ $i }}][q_ar]" dir="rtl" class="form-control" value="{{ $row['q_ar'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">{{ __('site.faq_question') }} — {{ __('site.english') }}</label>
                                    <input name="faq[{{ $i }}][q_en]" dir="ltr" class="form-control" value="{{ $row['q_en'] ?? '' }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">{{ __('site.faq_answer') }} — {{ __('site.arabic') }}</label>
                                    <textarea name="faq[{{ $i }}][a_ar]" dir="rtl" rows="3" class="form-control">{{ $row['a_ar'] ?? '' }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">{{ __('site.faq_answer') }} — {{ __('site.english') }}</label>
                                    <textarea name="faq[{{ $i }}][a_en]" dir="ltr" rows="3" class="form-control">{{ $row['a_en'] ?? '' }}</textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm removeFaq">
                                        <i class="bi bi-x-lg"></i> {{ __('site.remove') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-muted small {{ count($faqRows) ? 'd-none' : '' }}" id="faqEmpty">{{ __('site.faq_empty_hint') }}</div>
                </div>

                <div class="col-12 text-muted small">{{ __('site.last_updated_auto') }}</div>
                <div class="col-12">
                    <button class="btn btn-dark"><i class="bi bi-check2"></i> {{ __('site.save') }}</button>
                </div>
            </form>

            {{-- template for new FAQ rows --}}
            <template id="faqTemplate">
                <div class="card-soft p-3 mb-2 faqRow">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small">{{ __('site.faq_question') }} — {{ __('site.arabic') }}</label>
                            <input name="faq[__I__][q_ar]" dir="rtl" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">{{ __('site.faq_question') }} — {{ __('site.english') }}</label>
                            <input name="faq[__I__][q_en]" dir="ltr" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">{{ __('site.faq_answer') }} — {{ __('site.arabic') }}</label>
                            <textarea name="faq[__I__][a_ar]" dir="rtl" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">{{ __('site.faq_answer') }} — {{ __('site.english') }}</label>
                            <textarea name="faq[__I__][a_en]" dir="ltr" rows="3" class="form-control"></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm removeFaq">
                                <i class="bi bi-x-lg"></i> {{ __('site.remove') }}
                            </button>
                        </div>
                    </div>
                </div>
            </template>

        {{-- ====================== PRIVACY ====================== --}}
        @elseif ($tab === 'privacy')
            <form method="POST" action="{{ route('dashboard.settings.update', 'privacy') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">{{ __('site.privacy_body') }} — {{ __('site.arabic') }}</label>
                    <textarea name="body_ar" dir="rtl" rows="16" class="form-control font-monospace">{{ old('body_ar', $site->get('privacy.body_ar')) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="form-label">{{ __('site.privacy_body') }} — {{ __('site.english') }}</label>
                    <textarea name="body_en" dir="ltr" rows="16" class="form-control font-monospace">{{ old('body_en', $site->get('privacy.body_en')) }}</textarea>
                </div>
                <div class="col-12 text-muted small">{{ __('site.markdown_help') }} {{ __('site.blank_default') }}</div>
                <div class="col-12 text-muted small">{{ __('site.last_updated_auto') }}</div>
                <div class="col-12">
                    <button class="btn btn-dark"><i class="bi bi-check2"></i> {{ __('site.save') }}</button>
                </div>
            </form>

        {{-- ====================== FEATURED ====================== --}}
        @else
            <div class="text-muted mb-3">{{ __('site.featured_tab_hint') }}</div>

            <div class="row g-4">
                @foreach ([
                    ['title' => __('site.featured_offers'), 'rows' => $featuredOffers, 'route' => 'dashboard.featured.offers', 'manage' => route('dashboard.offers.index'), 'manageLabel' => __('site.manage_offers')],
                    ['title' => __('site.featured_categories'), 'rows' => $featuredCategories, 'route' => 'dashboard.featured.categories', 'manage' => route('dashboard.categories.index'), 'manageLabel' => __('site.manage_categories')],
                ] as $box)
                    <div class="col-lg-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="fw-semibold">{{ $box['title'] }} <span class="badge text-bg-light border">{{ $box['rows']->count() }}</span></div>
                            <a href="{{ $box['manage'] }}" class="btn btn-light border btn-sm">{{ $box['manageLabel'] }}</a>
                        </div>

                        @forelse ($box['rows'] as $r)
                            <form method="POST" action="{{ route($box['route'], $r->id) }}"
                                  class="d-flex align-items-center gap-2 border rounded-3 p-2 mb-2">
                                @csrf
                                <div class="flex-grow-1 text-truncate">{{ $r->name }}</div>
                                <input type="number" name="featured_order" min="0" max="65535" value="{{ $r->featured_order }}"
                                       class="form-control form-control-sm" style="width:80px;" title="{{ __('site.order') }}">
                                <button name="is_featured" value="1" class="btn btn-sm btn-outline-secondary">{{ __('site.save') }}</button>
                                <button name="is_featured" value="0" class="btn btn-sm btn-outline-danger">{{ __('site.unfeature') }}</button>
                            </form>
                        @empty
                            <div class="text-muted small">{{ __('site.featured_none') }}</div>
                        @endforelse
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Restore defaults (not for the Featured tab) --}}
        @if ($tab !== 'featured')
            <form method="POST" action="{{ route('dashboard.settings.reset', $tab) }}" class="mt-3 pt-3 border-top"
                  onsubmit="return confirm(@json(__('site.confirm_restore')))">
                @csrf
                <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-counterclockwise"></i> {{ __('site.restore_defaults') }}</button>
            </form>
        @endif
    </div>
@endsection

@if ($tab === 'support')
    @push('scripts')
        <script>
            (function () {
                const list = document.getElementById('faqList');
                const tpl = document.getElementById('faqTemplate');
                const empty = document.getElementById('faqEmpty');
                let next = list.querySelectorAll('.faqRow').length + 1000; // unique indexes for new rows

                function syncEmpty() {
                    empty.classList.toggle('d-none', list.querySelectorAll('.faqRow').length > 0);
                }

                document.getElementById('addFaq').addEventListener('click', function () {
                    const html = tpl.innerHTML.replaceAll('__I__', next++);
                    list.insertAdjacentHTML('afterbegin', html);
                    syncEmpty();
                });

                list.addEventListener('click', function (e) {
                    const btn = e.target.closest('.removeFaq');
                    if (!btn) return;
                    btn.closest('.faqRow').remove();
                    syncEmpty();
                });
            })();
        </script>
    @endpush
@endif
