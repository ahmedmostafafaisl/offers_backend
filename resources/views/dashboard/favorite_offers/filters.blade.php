<div class="col-md-3">
    <select class="form-select" name="user_id">
        <option value="">All Users</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) request('user_id') === (string) $u->id)>#{{ $u->id }} -
                {{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <select class="form-select" name="offer_id">
        <option value="">All Offers</option>
        @foreach($offers as $o)
            <option value="{{ $o->id }}" @selected((string) request('offer_id') === (string) $o->id)>#{{ $o->id }} -
                {{ $o->name }}</option>
        @endforeach
    </select>
</div>
