@php
    $title = 'Edit Category';
    $backUrl = route('dashboard.categories.index');
    $action = route('dashboard.categories.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.categories.fields', ['row' => $row])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
