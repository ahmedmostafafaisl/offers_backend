@php
    $title = 'Create Category';
    $backUrl = route('dashboard.categories.index');
    $action = route('dashboard.categories.store');
    $method = 'POST';
    $fields = view('dashboard.categories.fields', ['row' => null])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
