<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="{{ old('name', $row->name ?? '') }}">
</div>
<div class="col-md-3">
    <label class="form-label">Monthly Price</label>
    <input name="monthly_price" class="form-control" value="{{ old('monthly_price', $row->monthly_price ?? 0) }}">
</div>
<div class="col-md-3">
    <label class="form-label">Annually Price</label>
    <input name="annually_price" class="form-control" value="{{ old('annually_price', $row->annually_price ?? 0) }}">
</div>
