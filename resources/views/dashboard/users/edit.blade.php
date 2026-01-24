@php
    $title = 'Edit User';
    $backUrl = route('dashboard.users.index');
    $action = route('dashboard.users.update', $row->id);
    $method = 'PUT';

    $fields = view('dashboard.users.fields', ['row' => $row, 'roles' => $roles])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
