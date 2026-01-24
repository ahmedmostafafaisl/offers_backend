@php
    $title = 'Create User';
    $backUrl = route('dashboard.users.index');
    $action = route('dashboard.users.store');
    $method = 'POST';

    $fields = view('dashboard.users.fields', ['row' => null, 'roles' => $roles])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
