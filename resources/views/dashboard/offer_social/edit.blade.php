@php
    $title = 'Edit Offer Social Media';
    $backUrl = route('dashboard.offer-social-media.index');
    $action = route('dashboard.offer-social-media.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.offer_social.fields', ['row' => $row, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
