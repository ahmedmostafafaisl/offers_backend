<div class="col-md-3">
    <input name="reference_id" value="{{ request('reference_id') }}" class="form-control"
        placeholder="{{ __('dashboard.reference_id') }}">
</div>

<div class="col-md-2">
    <input name="phone" value="{{ request('phone') }}" class="form-control" placeholder="{{ __('dashboard.phone') }}">
</div>

<div class="col-md-2">
    <select name="status" class="form-select">
        <option value="">{{ __('dashboard.all') }}</option>
        <option value="pending" @selected(request('status') === 'pending')>pending</option>
        <option value="paid" @selected(request('status') === 'paid')>paid</option>
        <option value="failed" @selected(request('status') === 'failed')>failed</option>
    </select>
</div>

<div class="col-md-2">
    <input name="payment_type" value="{{ request('payment_type') }}" class="form-control"
        placeholder="{{ __('dashboard.payment_type') }}">
</div>
