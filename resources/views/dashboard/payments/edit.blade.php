@extends('layouts.dashboard')

@section('title', __('dashboard.payments'))
@section('page_title', __('dashboard.edit') . ' - ' . __('dashboard.payments'))

@section('content')
    <div class="card-soft p-4">
        <form method="POST" action="{{ route('dashboard.payments.update', $row->id) }}">
            @csrf @method('PUT')

            @include('dashboard.payments._form', ['row' => $row])

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-primary">{{ __('dashboard.update') }}</button>
                <a class="btn btn-light border"
                    href="{{ route('dashboard.payments.index') }}">{{ __('dashboard.back') }}</a>
            </div>
        </form>
    </div>
@endsection
