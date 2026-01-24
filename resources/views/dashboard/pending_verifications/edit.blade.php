@php
    $title = 'Edit Pending Verification';
    $backUrl = route('dashboard.pending-profile-verifications.index');
    $action = route('dashboard.pending-profile-verifications.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.pending_verifications.fields', ['row' => $row, 'users' => $users])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
