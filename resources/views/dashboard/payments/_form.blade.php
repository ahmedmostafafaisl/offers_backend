@php
    $isEdit = isset($row);

    $users = \App\Models\User::select('id', 'name', 'email', 'type')
        ->latest()
        ->limit(200)
        ->get();

    // ✅ translations for JS (so it works with language switch)
    $t = [
        'name' => __('dashboard.name'),
        'item_number' => __('dashboard.item_number'),
        'price' => __('dashboard.price'),
        'quantity' => __('dashboard.quantity'),
        'add_item' => __('dashboard.add_item'),
    ];
@endphp

<div class="row g-3">

    <div class="col-md-6">
        <label class="form-label">{{ __('dashboard.user') }}</label>
        <select name="user_id" class="form-select">
            <option value="">{{ __('dashboard.none') ?? '-- None --' }}</option>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" @selected(old('user_id', $row->user_id ?? null) == $u->id)>
                    #{{ $u->id }} - {{ $u->name ?? $u->email }} ({{ $u->type }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('dashboard.payment_type') }}</label>
        <input name="payment_type" class="form-control" value="{{ old('payment_type', $row->payment_type ?? '') }}"
            required>
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('dashboard.phone') }}</label>
        <input name="phone" class="form-control" value="{{ old('phone', $row->phone ?? '') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label">{{ __('dashboard.status') }}</label>
        <select name="status" class="form-select" required>
            @php $v = old('status', $row->status ?? 'pending'); @endphp
            <option value="pending" @selected($v === 'pending')>pending</option>
            <option value="paid" @selected($v === 'paid')>paid</option>
            <option value="failed" @selected($v === 'failed')>failed</option>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('dashboard.payment_id') }}</label>
        <input name="payment_id" class="form-control" value="{{ old('payment_id', $row->payment_id ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">{{ __('dashboard.amount') }}</label>
        <input name="amount" type="number" step="0.01" class="form-control"
            value="{{ old('amount', $row->amount ?? '') }}">
        <div class="small text-muted">
            {{ __('dashboard.amount_hint') ?? 'If items provided, amount will be recalculated from items.' }}</div>
    </div>

    <div class="col-12">
        <hr>
        <h6 class="mb-2">{{ __('dashboard.payment_items') }}</h6>

        <div id="itemsWrap" class="d-flex flex-column gap-2">
            @php
                $items = old('items', isset($row) ? $row->items->toArray() : []);
                if (empty($items)) {
                    $items = [['name' => '', 'item_number' => '', 'price' => '', 'quantity' => 1]];
                }
            @endphp

            @foreach ($items as $i => $item)
                <div class="card-soft p-3 itemRow">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('dashboard.name') }}</label>
                            <input name="items[{{ $i }}][name]" class="form-control" value="{{ $item['name'] ?? '' }}"
                                required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('dashboard.item_number') }}</label>
                            <input name="items[{{ $i }}][item_number]" class="form-control"
                                value="{{ $item['item_number'] ?? '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('dashboard.price') }}</label>
                            <input name="items[{{ $i }}][price]" type="number" step="0.01" class="form-control"
                                value="{{ $item['price'] ?? '' }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('dashboard.quantity') }}</label>
                            <input name="items[{{ $i }}][quantity]" type="number" class="form-control"
                                value="{{ $item['quantity'] ?? 1 }}" required>
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm removeItem">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-light border mt-2" id="addItemBtn">
            <i class="bi bi-plus"></i> {{ __('dashboard.add_item') ?? 'Add item' }}
        </button>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            let wrap = document.getElementById('itemsWrap');
            let addBtn = document.getElementById('addItemBtn');

            // ✅ translations available in JS
            const T = @json($t);

            function reIndex() {
                let rows = wrap.querySelectorAll('.itemRow');
                rows.forEach((row, idx) => {
                    row.querySelectorAll('input').forEach(inp => {
                        inp.name = inp.name.replace(/items\[\d+\]/, `items[${idx}]`);
                    });
                });
            }

            wrap.addEventListener('click', function (e) {
                if (e.target.closest('.removeItem')) {
                    let row = e.target.closest('.itemRow');
                    row.remove();

                    if (wrap.querySelectorAll('.itemRow').length === 0) {
                        addRow();
                    }

                    reIndex();
                }
            });

            function addRow() {
                let idx = wrap.querySelectorAll('.itemRow').length;

                let div = document.createElement('div');
                div.className = 'card-soft p-3 itemRow';

                // ✅ DO NOT put Blade translations inside template literal
                div.innerHTML = `
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">${T.name}</label>
                                <input name="items[${idx}][name]" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">${T.item_number}</label>
                                <input name="items[${idx}][item_number]" class="form-control">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">${T.price}</label>
                                <input name="items[${idx}][price]" type="number" step="0.01" class="form-control" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">${T.quantity}</label>
                                <input name="items[${idx}][quantity]" type="number" class="form-control" value="1" required>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm removeItem">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    `;

                wrap.appendChild(div);
            }

            addBtn?.addEventListener('click', addRow);
        })();
    </script>
@endpush
