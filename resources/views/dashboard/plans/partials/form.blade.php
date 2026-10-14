{{-- resources/views/dashboard/plans/partials/form.blade.php --}}
@php
$isEdit = isset($row);

// ✅ translations for JS (no blade inside template literal)
$t = [
    'feature_name' => __('dashboard.feature_name'),
    'feature_description' => __('dashboard.feature_description'),
    'add_feature' => __('dashboard.add_feature'),
];
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">{{ __('dashboard.name') }}</label>
        <input name="name" class="form-control" value="{{ old('name', $row->name ?? '') }}" required>
        @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    {{-- Plan billing types: choose one or more, and set a price for each chosen type --}}
    <div class="col-12">
        <label class="form-label fw-semibold">{{ __('dashboard.plan_types') }}</label>
        <div class="text-muted small mb-2">{{ __('dashboard.plan_types_hint') }}</div>

        @php
            $typeLabels = [
                'quarterly'   => __('dashboard.quarterly'),
                'semi_annual' => __('dashboard.semi_annual'),
                'annual'      => __('dashboard.annual'),
            ];

            // Which types are ticked: old input wins, then the stored prices (NULL = not offered)
            $selectedTypes = old('types', collect(\App\Models\Plan::TYPES)
                ->filter(fn ($col, $type) => $isEdit && $row->{$col} !== null)
                ->keys()
                ->all());
        @endphp

        <div class="row g-3" id="planTypes">
            @foreach (\App\Models\Plan::TYPES as $type => $column)
                @php $checked = in_array($type, (array) $selectedTypes, true); @endphp
                <div class="col-md-4">
                    <div class="card-soft p-3 h-100 planTypeCard">
                        <div class="form-check mb-2">
                            <input class="form-check-input planTypeCheck" type="checkbox" name="types[]"
                                   value="{{ $type }}" id="type_{{ $type }}" @checked($checked)>
                            <label class="form-check-label fw-semibold" for="type_{{ $type }}">
                                {{ $typeLabels[$type] }}
                            </label>
                        </div>

                        <div class="input-group">
                            <input name="{{ $column }}" type="number" step="0.01" min="0"
                                   class="form-control planTypePrice"
                                   placeholder="{{ __('dashboard.price') }}"
                                   value="{{ old($column, $isEdit ? $row->{$column} : '') }}"
                                   @disabled(!$checked)>
                            <span class="input-group-text">{{ __('dashboard.currency') }}</span>
                        </div>
                        @error($column) <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            @endforeach
        </div>
        @error('types') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
    </div>

    <div class="col-12">
        <hr>
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="mb-0">{{ __('dashboard.plan_features') }}</h6>
            <span class="badge bg-light text-dark border" id="featuresCount">0</span>
        </div>

        <div id="featuresWrap" class="d-flex flex-column gap-2">
            @php
$features = old('features', $isEdit ? $row->features->toArray() : []);
if (empty($features)) {
    $features = [['id' => null, 'name' => '', 'description' => '']];
}
            @endphp

            @foreach ($features as $i => $f)
                <div class="card-soft p-3 featureRow">
                    <div class="row g-2 align-items-end">
                        {{-- ✅ keep id on update --}}
                        <input type="hidden" name="features[{{ $i }}][id]" value="{{ $f['id'] ?? '' }}">

                        <div class="col-md-4">
                            <label class="form-label">{{ __('dashboard.feature_name') }}</label>
                            <input name="features[{{ $i }}][name]" class="form-control"
                                   value="{{ $f['name'] ?? '' }}" >
                            @error("features.$i.name") <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('dashboard.feature_description') }}</label>
                            <input name="features[{{ $i }}][description]" class="form-control"
                                   value="{{ $f['description'] ?? '' }}">
                        </div>

                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm removeFeature">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-light border mt-2" id="addFeatureBtn">
            <i class="bi bi-plus"></i> {{ __('dashboard.add_feature') }}
        </button>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const T = @json($t);

    const wrap = document.getElementById('featuresWrap');
    const addBtn = document.getElementById('addFeatureBtn');
    const countEl = document.getElementById('featuresCount');

    if (!wrap) return;

    function updateCount() {
        countEl && (countEl.textContent = wrap.querySelectorAll('.featureRow').length);
    }

    function reIndex() {
        const rows = wrap.querySelectorAll('.featureRow');
        rows.forEach((row, idx) => {
            row.querySelectorAll('input').forEach(inp => {
                inp.name = inp.name.replace(/features\[\d+\]/, `features[${idx}]`);
            });
        });
        updateCount();
    }

    wrap.addEventListener('click', function (e) {
        if (e.target.closest('.removeFeature')) {
            const row = e.target.closest('.featureRow');
            row.remove();

            if (wrap.querySelectorAll('.featureRow').length === 0) {
                addRow();
            }
            reIndex();
        }
    });

    function addRow() {
        const idx = wrap.querySelectorAll('.featureRow').length;

        const div = document.createElement('div');
        div.className = 'card-soft p-3 featureRow';
        div.innerHTML = `
            <div class="row g-2 align-items-end">
                <input type="hidden" name="features[${idx}][id]" value="">
                <div class="col-md-4">
                    <label class="form-label">${T.feature_name}</label>
                    <input name="features[${idx}][name]" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">${T.feature_description}</label>
                    <input name="features[${idx}][description]" class="form-control">
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-outline-danger btn-sm removeFeature">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>
        `;

        wrap.appendChild(div);
        updateCount();
    }

    addBtn?.addEventListener('click', addRow);

    // init count
    updateCount();
})();

// Plan types: enable the price input only for ticked types
(function () {
    document.querySelectorAll('.planTypeCard').forEach(function (card) {
        const check = card.querySelector('.planTypeCheck');
        const price = card.querySelector('.planTypePrice');

        function sync() {
            price.disabled = !check.checked;
            price.required = check.checked;
            card.style.opacity = check.checked ? '1' : '.6';
            if (check.checked && !price.value) price.focus();
        }

        check.addEventListener('change', sync);
        price.required = check.checked;
        card.style.opacity = check.checked ? '1' : '.6';
    });
})();
</script>
@endpush
