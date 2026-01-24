@extends('layouts.dashboard')
@section('title', $title)
@section('page_title', $title)

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1">{{ $title }}</h3>
            <div class="text-muted small">{{ $breadcrumb ?? $title }}</div>
        </div>
        <a class="btn btn-light border" href="{{ $backUrl }}"><i class="bi bi-arrow-left"></i> Back</a>
    </div>

    <div class="card-soft p-4">
        <form method="POST" action="{{ $action }}" enctype="multipart/form-data">
            @csrf
            @if($method !== 'POST') @method($method) @endif

            <div class="row g-3">
                {!! $fields !!}
            </div>

            <div class="mt-4 d-flex gap-2">
                <button class="btn btn-primary"><i class="bi bi-check2"></i> Save</button>
                <a class="btn btn-outline-secondary" href="{{ $backUrl }}">Cancel</a>
            </div>
        </form>
    </div>
@endsection
