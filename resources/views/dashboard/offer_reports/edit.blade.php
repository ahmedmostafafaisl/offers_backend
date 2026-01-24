@php
    $title = 'Edit Offer Report';
    $backUrl = route('dashboard.offer-reports.index');
    $action = route('dashboard.offer-reports.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.offer_reports.fields', ['row' => $row, 'users' => $users, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
