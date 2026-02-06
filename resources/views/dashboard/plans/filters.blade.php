<div class="col-md-6">
    <input class="form-control" name="q" value="{{ request('q') }}" placeholder="{{ __('dashboard.search') }}">
</div>

<div class="col-md-3">
    <select class="form-select" name="per_page">
        @foreach([5, 10, 20, 50] as $n)
            <option value="{{ $n }}" @selected((int) request('per_page', 10) === $n)>
                {{ __('dashboard.show') }} {{ $n }}
            </option>
        @endforeach
    </select>
</div>
