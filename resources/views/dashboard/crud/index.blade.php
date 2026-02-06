@extends('layouts.dashboard')

@section('title', $title)
@section('page_title', $title)

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div>
            <h3 class="mb-1">{{ $title }}</h3>
            <div class="text-muted small">{{ $breadcrumb ?? 'Overview / ' . $title }}</div>
        </div>

        @if(!empty($createUrl))
            <a class="btn btn-dark" href="{{ $createUrl }}">
                <i class="bi bi-plus-lg me-1"></i> {{ __('dashboard.create') ?? 'Create' }}
            </a>
        @endif
    </div>

    <div class="card-soft p-3">
        <form class="row g-2 mb-3" method="GET">
            <div class="col-12 col-md-6">
                <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Search...">
            </div>

            <div class="col-6 col-md-3">
                <select class="form-select" name="per_page">
                    @foreach([5, 10, 20, 50] as $n)
                        <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>
                            Show {{ $n }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-6 col-md-3 d-flex gap-2">
                {{-- any extra filters fields --}}
                @if(!empty($filtersView))
                    @include($filtersView)
                @endif

                {{-- ✅ submit filters --}}
                <button type="submit" class="btn btn-dark flex-grow-1">
                    <i class="bi bi-funnel me-1"></i> Filters
                </button>

                {{-- ✅ reset --}}
                <a class="btn btn-light border" href="{{ url()->current() }}">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        @foreach($columns as $c)
                            <th class="text-nowrap">{{ $c }}</th>
                        @endforeach
                        <th class="text-end text-nowrap">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rows as $row)
                        <tr>


                            {!! $renderRow($row) !!}
                            <td class="text-end text-nowrap">
                                @if(!empty($showUrl))
                                    <a class="btn btn-sm btn-light border" href="{{ $showUrl($row) }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                                @if(!empty($editUrl))
                                    <a class="btn btn-sm btn-light border" href="{{ $editUrl($row) }}">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif

                                @if(!empty($deleteUrl))
                                    <form class="d-inline" method="POST" action="{{ $deleteUrl($row) }}"
                                        onsubmit="return confirm('Delete?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-5">
                                No data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ IMPORTANT: use Bootstrap pagination to avoid huge SVG arrows --}}
        @if(method_exists($rows, 'links'))
            <div class="mt-3 d-flex justify-content-center">
                {{ $rows->appends(request()->query())->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
