<div class="col-md-6">
    <label class="form-label">Offer</label>
    <select name="offer_id" class="form-select" required>
        @foreach($offers as $o)
            <option value="{{ $o->id }}" @selected((string) old('offer_id', $row->offer_id ?? '') === (string) $o->id)>
                #{{ $o->id }} - {{ $o->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-3">
    <label class="form-label">Platform</label>
    <input name="platform" class="form-control" required value="{{ old('platform', $row->platform ?? '') }}">
</div>

<div class="col-md-3">
    <label class="form-label">URL</label>
    <input name="url" class="form-control" required value="{{ old('url', $row->url ?? '') }}">
</div>
