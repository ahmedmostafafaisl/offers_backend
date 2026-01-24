<div class="col-md-4">
    <label class="form-label">User</label>
    <select name="user_id" class="form-select" required>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) old('user_id', $row->user_id ?? '') === (string) $u->id)>
                #{{ $u->id }} - {{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Linked User (optional)</label>
    <select name="linked_user_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) old('linked_user_id', $row->linked_user_id ?? '') === (string) $u->id)>#{{ $u->id }} - {{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Type</label>
    <select name="type" class="form-select" required>
        @foreach(['employee', 'provider', 'customer'] as $t)
            <option value="{{ $t }}" @selected(old('type', $row->type ?? '') === $t)>{{ $t }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="{{ old('name', $row->name ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Phone</label>
    <input name="phone" class="form-control" value="{{ old('phone', $row->phone ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Photo</label>
    <input name="photo" type="file" class="form-control">
    @if($row && $row->photo)
        <div class="small text-muted mt-1"><code>{{ $row->photo }}</code></div>
    @endif
</div>

<div class="col-md-6">
    <label class="form-label">Country</label>
    <input name="country" class="form-control" value="{{ old('country', $row->country ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">City</label>
    <input name="city" class="form-control" value="{{ old('city', $row->city ?? '') }}">
</div>

<hr class="my-2">

<div class="col-md-4">
    <label class="form-label">WhatsApp (provider)</label>
    <input name="whats_app_number" class="form-control"
        value="{{ old('whats_app_number', $row->whats_app_number ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Store Number</label>
    <input name="store_number" class="form-control" value="{{ old('store_number', $row->store_number ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Store Establish Date</label>
    <input type="date" name="store_establish_date" class="form-control"
        value="{{ old('store_establish_date', optional($row->store_establish_date ?? null)?->format('Y-m-d')) }}">
</div>

<div class="col-md-6">
    <label class="form-label">Tax Number</label>
    <input name="tax_number" class="form-control" value="{{ old('tax_number', $row->tax_number ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Commercial Registration</label>
    <input name="commercial_registration" class="form-control"
        value="{{ old('commercial_registration', $row->commercial_registration ?? '') }}">
</div>
