@php
    $title = 'Create Plan Feature';
    $backUrl = route('dashboard.plan-features.index');
    $action = route('dashboard.plan-features.store');
    $method = 'POST';
    $fields = view('dashboard.plan_features.fields', ['row' => null, 'plans' => $plans])->render();
@endphp
@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
