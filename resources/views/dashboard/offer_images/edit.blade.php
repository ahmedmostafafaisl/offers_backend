@php
    $title = 'Edit Offer Image';
    $backUrl = route('dashboard.offer-images.index');
    $action = route('dashboard.offer-images.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.offer_images.fields', ['row' => $row, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
