@php
    $title = 'Create User Profile';
    $backUrl = route('dashboard.user-profiles.index');
    $action = route('dashboard.user-profiles.store');
    $method = 'POST';
    $fields = view('dashboard.user_profiles.fields', ['row' => null, 'users' => $users])->render();
@endphp
@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
