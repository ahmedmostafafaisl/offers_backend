@php
    $title = 'Create Permission';
    $backUrl = route('dashboard.permissions.index');
    $action = route('dashboard.permissions.store');
    $method = 'POST';
    $fields = view('dashboard.permissions.fields', ['row' => null])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
