@php
    $title = 'Create Offer Report';
    $backUrl = route('dashboard.offer-reports.index');
    $action = route('dashboard.offer-reports.store');
    $method = 'POST';
    $fields = view('dashboard.offer_reports.fields', ['row' => null, 'users' => $users, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
