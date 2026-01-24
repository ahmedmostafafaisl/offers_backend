@php
    $title = 'Create Pending Verification';
    $backUrl = route('dashboard.pending-profile-verifications.index');
    $action = route('dashboard.pending-profile-verifications.store');
    $method = 'POST';
    $fields = view('dashboard.pending_verifications.fields', ['row' => null, 'users' => $users])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
