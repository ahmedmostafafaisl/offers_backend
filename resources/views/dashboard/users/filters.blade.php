<div class="col-md-3">
    <select class="form-select" name="type">
        <option value="">All Types</option>
        <option value="customer" @selected(request('type') === 'customer')>customer</option>
        <option value="provider" @selected(request('type') === 'provider')>provider</option>
        <option value="employee" @selected(request('type') === 'employee')>employee</option>
    </select>
</div>
