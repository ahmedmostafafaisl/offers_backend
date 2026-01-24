@php
    $title = 'Edit Subscription';
    $backUrl = route('dashboard.subscriptions.index');
    $action = route('dashboard.subscriptions.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.subscriptions.fields', ['row' => $row, 'users' => $users, 'plans' => $plans])->render();
@endphp
@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
