@extends('layouts.dashboard')

@section('title', __('dashboard.payments'))
@section('page_title', __('dashboard.create') . ' - ' . __('dashboard.payments'))

@section('content')
    <div class="card-soft p-4">
        <form method="POST" action="{{ route('dashboard.payments.store') }}">
            @csrf

            @include('dashboard.payments._form')

            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-primary">{{ __('dashboard.save') }}</button>
                <a class="btn btn-light border"
                    href="{{ route('dashboard.payments.index') }}">{{ __('dashboard.back') }}</a>
            </div>
        </form>
    </div>
@endsection
