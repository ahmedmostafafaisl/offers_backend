@php
    $title = 'Pending Profile Verifications';
    $columns = ['ID', 'Requester', 'Target', 'Type', 'Phone', 'Email', 'Verified', 'Expires', 'Created'];
@endphp

@include('dashboard.crud.index', [
    'title' => $title,
    'breadcrumb' => 'Overview / Pending Profile Verifications',
    'createUrl' => route('dashboard.pending-profile-verifications.create'),
    'columns' => $columns,
    'rows' => $rows,
    'filtersView' => 'dashboard.pending_verifications.filters',
    'editUrl' => fn($r) => route('dashboard.pending-profile-verifications.edit', $r->id),
    'deleteUrl' => fn($r) => route('dashboard.pending-profile-verifications.destroy', $r->id),
    'renderRow' => function ($r) {
        $verified = $r->verified_at ? '<span class="status-pill"><span class="status-dot"></span>Yes</span>' : '<span class="badge text-bg-secondary">No</span>';
        return "
        <td>{$r->id}</td>
        <td>#{$r->requester_user_id}</td>
        <td>" . ($r->target_user_id ? '#' . $r->target_user_id : '-') . "</td>
        <td>{$r->type}</td>
        <td>{$r->phone}</td>
        <td>{$r->email}</td>
        <td>{$verified}</td>
        <td>" . ($r->expires_at ? \Carbon\Carbon::parse($r->expires_at)->format('d/m/Y') : '-') . "</td>
        <td>" . optional($r->created_at)->format('d/m/Y') . "</td>
      ";
    }
])
