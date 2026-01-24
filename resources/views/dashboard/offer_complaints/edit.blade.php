@php
    $title = 'Edit Offer Complaint';
    $backUrl = route('dashboard.offer-complaints.index');
    $action = route('dashboard.offer-complaints.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.offer_complaints.fields', ['row' => $row, 'users' => $users, 'offers' => $offers])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
