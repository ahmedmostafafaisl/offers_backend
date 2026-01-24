@php
    $title = 'Edit Permission';
    $backUrl = route('dashboard.permissions.index');
    $action = route('dashboard.permissions.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.permissions.fields', ['row' => $row])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
