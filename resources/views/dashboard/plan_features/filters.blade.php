<div class="col-md-3">
    <select class="form-select" name="plan_id">
        <option value="">All Plans</option>
        @foreach($plans as $p)
            <option value="{{ $p->id }}" @selected((string) request('plan_id') === (string) $p->id)>{{ $p->name }}</option>
        @endforeach
    </select>
</div>
