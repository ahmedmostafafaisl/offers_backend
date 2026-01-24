@php
    $title = 'Create Offer Complaint';
    $backUrl = route('dashboard.offer-complaints.index');
    $action = route('dashboard.offer-complaints.store');
    $method = 'POST';
    $fields = view('dashboard.offer_complaints.fields', ['row' => null, 'users' => $users, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
