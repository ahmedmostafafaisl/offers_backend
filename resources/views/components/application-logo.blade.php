<img
    src="{{ asset('assets/img/logo2.png') }}"
    alt="{{ app()->getLocale() === 'ar' ? 'عروض' : 'Orood' }}"
    {{ $attributes->merge([
        'class' => 'img-fluid',
        'style' => 'height:100px;width:auto;object-fit:contain;',
        'loading' => 'lazy'
    ]) }}
/>
