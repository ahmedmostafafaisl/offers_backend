{{-- Featured toggle for a list row. Needs: $row (Offer|Category), $route (featured route name) --}}
<form method="POST" action="{{ route($route, $row->id) }}" class="d-flex align-items-center gap-1" style="min-width:150px;">
    @csrf
    @if ($row->is_featured)
        <input type="number" name="featured_order" min="0" max="65535" value="{{ $row->featured_order }}"
               class="form-control form-control-sm" style="width:64px;" title="{{ __('site.order') }}">
        <button name="is_featured" value="0" class="btn btn-sm btn-warning" title="{{ __('site.unfeature') }}">
            <i class="bi bi-star-fill"></i>
        </button>
        <button name="is_featured" value="1" class="btn btn-sm btn-outline-secondary" title="{{ __('site.save') }}">
            <i class="bi bi-check2"></i>
        </button>
    @else
        <input type="hidden" name="featured_order" value="0">
        <button name="is_featured" value="1" class="btn btn-sm btn-outline-secondary" title="{{ __('site.feature') }}">
            <i class="bi bi-star"></i> {{ __('site.feature') }}
        </button>
    @endif
</form>
