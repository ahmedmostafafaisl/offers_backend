@php
    $title = 'Create Subscription';
    $backUrl = route('dashboard.subscriptions.index');
    $action = route('dashboard.subscriptions.store');
    $method = 'POST';
    $fields = view('dashboard.subscriptions.fields', ['row' => null, 'users' => $users, 'plans' => $plans])->render();
@endphp
@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
