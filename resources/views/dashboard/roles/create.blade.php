@php
    $title = 'Create Role';
    $backUrl = route('dashboard.roles.index');
    $action = route('dashboard.roles.store');
    $method = 'POST';
    $fields = view('dashboard.roles.fields', ['row' => null, 'permissions' => $permissions, 'selected' => []])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
