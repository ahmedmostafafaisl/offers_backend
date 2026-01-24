<div class="col-md-6">
    <label class="form-label">User</label>
    <select name="user_id" class="form-select" required>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) old('user_id', $row->user_id ?? '') === (string) $u->id)>
                #{{ $u->id }} - {{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Offer</label>
    <select name="offer_id" class="form-select" required>
        @foreach($offers as $o)
            <option value="{{ $o->id }}" @selected((string) old('offer_id', $row->offer_id ?? '') === (string) $o->id)>
                #{{ $o->id }} - {{ $o->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-12">
    <label class="form-label">Reason</label>
    <textarea name="reason" class="form-control" rows="3" required>{{ old('reason', $row->reason ?? '') }}</textarea>
</div>
