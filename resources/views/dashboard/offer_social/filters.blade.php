<div class="col-md-3">
    <select class="form-select" name="offer_id">
        <option value="">All Offers</option>
        @foreach($offers as $o)
            <option value="{{ $o->id }}" @selected((string) request('offer_id') === (string) $o->id)>#{{ $o->id }} -
                {{ $o->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <input class="form-control" name="platform" value="{{ request('platform') }}" placeholder="Platform">
</div>
