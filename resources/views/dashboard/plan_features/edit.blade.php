@php
    $title = 'Edit Plan Feature';
    $backUrl = route('dashboard.plan-features.index');
    $action = route('dashboard.plan-features.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.plan_features.fields', ['row' => $row, 'plans' => $plans])->render();
@endphp
@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
