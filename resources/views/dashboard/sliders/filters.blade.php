<div class="col-md-3">
    <select class="form-select" name="status">
        <option value="">All Status</option>
        <option value="1" @selected(request('status') === '1')>Active</option>
        <option value="0" @selected(request('status') === '0')>Inactive</option>
    </select>
</div>
