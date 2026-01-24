<div class="col-md-6">
    <label class="form-label">Offer</label>
    <select name="offer_id" class="form-select" required>
        @foreach($offers as $o)
            <option value="{{ $o->id }}" @selected((string) old('offer_id', $row->offer_id ?? '') === (string) $o->id)>
                #{{ $o->id }} - {{ $o->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Image</label>
    <input name="image_file" type="file" class="form-control" {{ $row ? '' : 'required' }}>
    @if($row && $row->image)
        <div class="mt-2">
            <img src="{{ asset('storage/' . $row->image) }}"
                style="width:90px;height:90px;border-radius:12px;object-fit:cover;border:1px solid #eee;">
        </div>
    @endif
</div>
