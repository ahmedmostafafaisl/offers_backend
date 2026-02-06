<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="{{ old('name', $row->name ?? '') }}">
</div>
<div class="col-md-3">
    <label class="form-label">Quarterly Price</label>
    <input name="quarterly_price" class="form-control" value="{{ old('quarterly_price', $row->quarterly_price ?? 0) }}">
</div>

<div class="col-md-3">
    <label class="form-label">Semi-Annual Price</label>
    <input name="semi_annual_price" class="form-control" value="{{ old('semi_annual_price', $row->semi_annual_price ?? 0) }}">
</div>
<div class="col-md-3">
    <label class="form-label">Annual Price</label>
    <input name="annual_price" class="form-control" value="{{ old('annual_price', $row->annual_price ?? 0) }}">
</div>
