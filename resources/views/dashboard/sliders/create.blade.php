@php
    $title = 'Create Slider';
    $backUrl = route('dashboard.sliders.index');
    $action = route('dashboard.sliders.store');
    $method = 'POST';
    $fields = view('dashboard.sliders.fields', ['row' => null])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
