@php
    $title = 'Edit Slider';
    $backUrl = route('dashboard.sliders.index');
    $action = route('dashboard.sliders.update', $row->id);
    $method = 'PUT';
    $fields = view('dashboard.sliders.fields', ['row' => $row])->render();
@endphp

@include('dashboard.crud.form', compact('title', 'backUrl', 'action', 'method', 'fields'))
