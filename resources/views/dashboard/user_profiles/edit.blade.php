@php
    $title = 'Edit User Profile';
    $backUrl = route('dashboard.user-profiles.index');
    $action = route('dashboard.user-profiles.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.user_profiles.fields', ['row' => $row, 'users' => $users])->render();
@endphp
@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
