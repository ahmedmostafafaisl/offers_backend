<div class="col-md-4">
    <label class="form-label">Requester User</label>
    <select name="requester_user_id" class="form-select" required>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) old('requester_user_id', $row->requester_user_id ?? '') === (string) $u->id)>#{{ $u->id }} - {{ $u->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Target User (optional)</label>
    <select name="target_user_id" class="form-select">
        <option value="">-- None --</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" @selected((string) old('target_user_id', $row->target_user_id ?? '') === (string) $u->id)>#{{ $u->id }} - {{ $u->name }}</option>
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
    <label class="form-label">Phone</label>
    <input name="phone" class="form-control" required value="{{ old('phone', $row->phone ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Email</label>
    <input name="email" class="form-control" value="{{ old('email', $row->email ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">OTP Hash</label>
    <input name="otp_hash" class="form-control" value="{{ old('otp_hash', $row->otp_hash ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Expires At</label>
    <input type="datetime-local" name="expires_at" class="form-control"
        value="{{ old('expires_at', $row->expires_at ? \Carbon\Carbon::parse($row->expires_at)->format('Y-m-d\TH:i') : '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Verified At</label>
    <input type="datetime-local" name="verified_at" class="form-control"
        value="{{ old('verified_at', $row->verified_at ? \Carbon\Carbon::parse($row->verified_at)->format('Y-m-d\TH:i') : '') }}">
</div>
