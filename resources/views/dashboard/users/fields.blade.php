@php
    $currentRole = $row?->roles?->first()?->name;
@endphp

<div class="col-md-4">
    <label class="form-label">Type</label>
    <select name="type" id="userType" class="form-select" required>
        <option value="customer" @selected(old('type', $row->type ?? '') == 'customer')>customer</option>
        <option value="provider" @selected(old('type', $row->type ?? '') == 'provider')>provider</option>
        <option value="employee" @selected(old('type', $row->type ?? '') == 'employee')>employee</option>
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="{{ old('name', $row->name ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Email</label>
    <input name="email" type="email" class="form-control" required value="{{ old('email', $row->email ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Phone</label>
    <input name="phone" class="form-control" value="{{ old('phone', $row->phone ?? '') }}">
</div>

<div class="col-md-4">
    <label class="form-label">Password</label>
    <input name="password" type="password" class="form-control"
        placeholder="{{ $row ? 'Leave empty to keep current' : '' }}">
</div>

<div class="col-md-4" id="roleWrap" style="display:none;">
    <label class="form-label">Role (Employee)</label>
    <select name="role" class="form-select">
        @foreach($roles as $role)
            <option value="{{ $role->name }}" @selected(old('role', $currentRole) === $role->name)>
                {{ $role->name }}
            </option>
        @endforeach
    </select>

    @if($row && $row->type === 'employee')
        <div class="small text-muted mt-1">Current: {{ $currentRole ?? '-' }}</div>
    @endif
</div>

<script>
    (function () {
        const type = document.getElementById('userType');
        const wrap = document.getElementById('roleWrap');

        function toggleRole() {
            wrap.style.display = (type.value === 'employee') ? 'block' : 'none';
        }

        type.addEventListener('change', toggleRole);
        toggleRole();
    })();
</script>
