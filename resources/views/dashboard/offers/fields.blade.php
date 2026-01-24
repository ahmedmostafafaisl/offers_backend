<div class="col-md-4">
    <label class="form-label">Provider (user_id)</label>
    <select name="user_id" class="form-select" required>
        @foreach($providers as $p)
            <option value="{{ $p->id }}" @selected((string) old('user_id', $row->user_id ?? '') === (string) $p->id)>
                {{ $p->name }} (#{{ $p->id }})</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Category</label>
    <select name="category_id" class="form-select" required>
        @foreach($categories as $c)
            <option value="{{ $c->id }}" @selected((string) old('category_id', $row->category_id ?? '') === (string) $c->id)>
                {{ $c->name }}</option>
        @endforeach
    </select>
</div>

<div class="col-md-4">
    <label class="form-label">Active</label>
    <select name="is_active" class="form-select">
        <option value="1" @selected((string) old('is_active', $row->is_active ?? 1) === '1')>Active</option>
        <option value="0" @selected((string) old('is_active', $row->is_active ?? 1) === '0')>Inactive</option>
    </select>
</div>

<div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" required value="{{ old('name', $row->name ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Phone</label>
    <input name="phone" class="form-control" value="{{ old('phone', $row->phone ?? '') }}">
</div>

<div class="col-md-12">
    <label class="form-label">Details</label>
    <textarea name="details" class="form-control" rows="3">{{ old('details', $row->details ?? '') }}</textarea>
</div>

<div class="col-md-4">
    <label class="form-label">Price</label>
    <input name="price" class="form-control" value="{{ old('price', $row->price ?? '') }}">
</div>
<div class="col-md-4">
    <label class="form-label">Price Before</label>
    <input name="price_before" class="form-control" value="{{ old('price_before', $row->price_before ?? '') }}">
</div>
<div class="col-md-4">
    <label class="form-label">Price After</label>
    <input name="price_after" class="form-control" value="{{ old('price_after', $row->price_after ?? '') }}">
</div>

<div class="col-md-3">
    <label class="form-label">Latitude</label>
    <input name="latitude" class="form-control" value="{{ old('latitude', $row->latitude ?? '') }}">
</div>
<div class="col-md-3">
    <label class="form-label">Longitude</label>
    <input name="longitude" class="form-control" value="{{ old('longitude', $row->longitude ?? '') }}">
</div>

<div class="col-md-3">
    <label class="form-label">Start Date</label>
    <input type="date" name="start_date" class="form-control"
        value="{{ old('start_date', optional($row->start_date ?? null)?->format('Y-m-d')) }}">
</div>
<div class="col-md-3">
    <label class="form-label">Expiration Date</label>
    <input type="date" name="expiration_date" class="form-control"
        value="{{ old('expiration_date', optional($row->expiration_date ?? null)?->format('Y-m-d')) }}">
</div>

<div class="col-md-6">
    <label class="form-label">Location Name</label>
    <input name="location_name" class="form-control" value="{{ old('location_name', $row->location_name ?? '') }}">
</div>
<div class="col-md-6">
    <label class="form-label">Location Details</label>
    <input name="location_details" class="form-control"
        value="{{ old('location_details', $row->location_details ?? '') }}">
</div>

<hr class="my-2">

<div class="col-md-6">
    <label class="form-label">City AR</label>
    <input name="city_ar" class="form-control" value="{{ old('city_ar', $row->city_ar ?? '') }}">
</div>
<div class="col-md-6">
    <label class="form-label">City EN</label>
    <input name="city_en" class="form-control" value="{{ old('city_en', $row->city_en ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Address AR</label>
    <input name="address_ar" class="form-control" value="{{ old('address_ar', $row->address_ar ?? '') }}">
</div>
<div class="col-md-6">
    <label class="form-label">Address EN</label>
    <input name="address_en" class="form-control" value="{{ old('address_en', $row->address_en ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Governorate AR</label>
    <input name="governorate_ar" class="form-control" value="{{ old('governorate_ar', $row->governorate_ar ?? '') }}">
</div>
<div class="col-md-6">
    <label class="form-label">Governorate EN</label>
    <input name="governorate_en" class="form-control" value="{{ old('governorate_en', $row->governorate_en ?? '') }}">
</div>

<div class="col-md-6">
    <label class="form-label">Country AR</label>
    <input name="country_ar" class="form-control" value="{{ old('country_ar', $row->country_ar ?? '') }}">
</div>
<div class="col-md-6">
    <label class="form-label">Country EN</label>
    <input name="country_en" class="form-control" value="{{ old('country_en', $row->country_en ?? '') }}">
</div>

<hr class="my-2">

{{-- Images --}}
<div class="col-md-12">
    <label class="form-label">Images (multiple)</label>
    <input name="images[]" type="file" multiple class="form-control">

    @if($row && $row->relationLoaded('images'))
        <div class="mt-3 d-flex flex-wrap gap-2">
            @foreach($row->images as $img)
                <div class="border rounded p-2" style="width:170px;">
                    <img src="{{ asset('storage/' . $img->image) }}"
                        style="width:100%;height:110px;object-fit:cover;border-radius:10px;border:1px solid #eee;">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="delete_image_ids[]" value="{{ $img->id }}"
                            id="delimg{{ $img->id }}">
                        <label class="form-check-label small" for="delimg{{ $img->id }}">Delete</label>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Social Media --}}
<div class="col-md-12">
    <label class="form-label d-flex justify-content-between align-items-center">
        <span>Social Media</span>
        <button type="button" class="btn btn-sm btn-outline-primary" id="addSocial">
            <i class="bi bi-plus-lg"></i> Add
        </button>
    </label>

    <div id="socialWrap" class="d-flex flex-column gap-2">
        @php
            $socialOld = old('social_media');
            $social = $socialOld ?? (($row && $row->relationLoaded('socialMedia')) ? $row->socialMedia->map(fn($s) => ['platform' => $s->platform, 'url' => $s->url])->toArray() : []);
            if (empty($social))
                $social = [['platform' => 'Facebook', 'url' => 'https://facebook.com/'], ['platform' => 'Instagram', 'url' => 'https://instagram.com/']];
        @endphp

        @foreach($social as $i => $s)
            <div class="row g-2 social-row">
                <div class="col-md-3">
                    <input class="form-control" name="social_media[{{ $i }}][platform]" value="{{ $s['platform'] ?? '' }}"
                        placeholder="Platform">
                </div>
                <div class="col-md-8">
                    <input class="form-control" name="social_media[{{ $i }}][url]" value="{{ $s['url'] ?? '' }}"
                        placeholder="URL">
                </div>
                <div class="col-md-1 d-grid">
                    <button type="button" class="btn btn-outline-danger removeSocial"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        (function () {
            const wrap = document.getElementById('socialWrap');
            const addBtn = document.getElementById('addSocial');
            if (!wrap || !addBtn) return;

            function reindex() {
                const rows = wrap.querySelectorAll('.social-row');
                rows.forEach((row, idx) => {
                    const p = row.querySelector('input[name*="[platform]"]');
                    const u = row.querySelector('input[name*="[url]"]');
                    if (p) p.name = `social_media[${idx}][platform]`;
                    if (u) u.name = `social_media[${idx}][url]`;
                });
            }

            wrap.addEventListener('click', (e) => {
                if (e.target.closest('.removeSocial')) {
                    e.target.closest('.social-row').remove();
                    reindex();
                }
            });

            addBtn.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'row g-2 social-row';
                row.innerHTML = `
          <div class="col-md-3"><input class="form-control" placeholder="Platform"></div>
          <div class="col-md-8"><input class="form-control" placeholder="URL"></div>
          <div class="col-md-1 d-grid"><button type="button" class="btn btn-outline-danger removeSocial"><i class="bi bi-x-lg"></i></button></div>
        `;
                wrap.appendChild(row);
                reindex();
            });
            reindex();
        })();
    </script>
</div>
