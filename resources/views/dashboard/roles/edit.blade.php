@php
    $title = 'Edit Role';
    $backUrl = route('dashboard.roles.index');
    $action = route('dashboard.roles.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.roles.fields', ['row' => $row, 'permissions' => $permissions, 'selected' => $selected])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
