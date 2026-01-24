@extends('layouts.dashboard')
@section('title', $title)
@section('page_title', $title)

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="mb-1">{{ $title }}</h3>
            <div class="text-muted small">{{ $breadcrumb ?? 'Overview / ' . $title }}</div>
        </div>

        <a class="btn btn-primary" href="{{ $createUrl }}">
            <i class="bi bi-plus-lg me-1"></i> Create
        </a>
    </div>

    <div class="card-soft p-3">
        <form class="row g-2 mb-3">
            <div class="col-md-6">
                <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search...">
            </div>
            <div class="col-md-3">
                <select class="form-select" name="per_page">
                    @foreach([5, 10, 20, 50] as $n)
                        <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>Show {{ $n }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                @if(!empty($filtersView))
                    @include($filtersView)
                @endif
                <button class="btn btn-dark w-100"><i class="bi bi-funnel me-1"></i> Filters</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        @foreach($columns as $c)
                            <th>{{ $c }}</th>
                        @endforeach
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            {!! $renderRow($row) !!}
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ $editUrl($row) }}">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form class="d-inline" method="POST" action="{{ $deleteUrl($row) }}"
                                    onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-5">No data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-2">
            {{ $rows->links() }}
        </div>
    </div>
@endsection
