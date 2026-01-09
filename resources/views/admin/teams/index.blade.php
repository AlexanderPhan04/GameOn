@extends('layouts.app')

@section('title', __('app.profile.manage_teams'))

@push('styles')
<style>
    .teams-container { background: #000814; min-height: 100vh; }

    /* Hero Section */
    .teams-hero {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .teams-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #00E5FF, transparent);
    }
    .hero-icon {
        width: 60px; height: 60px; min-width: 60px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 0 25px rgba(99, 102, 241, 0.3);
    }
    .hero-icon i { font-size: 1.5rem; color: white; }
    .hero-title { font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700; color: #00E5FF; margin: 0; }
    .hero-subtitle { color: #94a3b8; font-size: 0.9rem; margin: 0.25rem 0 0 0; }

    .btn-neon {
        background: linear-gradient(135deg, #000055, #000077);
        color: #00E5FF;
        border: 1px solid rgba(0, 229, 255, 0.4);
        padding: 0.6rem 1.25rem;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.3s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-neon:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 20px rgba(0, 229, 255, 0.4);
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .btn-neon-success {
        background: linear-gradient(135deg, #065f46, #047857);
        border-color: rgba(34, 197, 94, 0.4);
        color: #22c55e;
    }
    .btn-neon-success:hover { box-shadow: 0 0 20px rgba(34, 197, 94, 0.4); }

    /* Filter Card */
    .filter-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    .filter-label { color: #94a3b8; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.4rem; display: block; }
    .filter-input, .filter-select {
        width: 100%;
        padding: 0.6rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #FFFFFF;
        font-size: 0.85rem;
        transition: all 0.3s ease;
    }
    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #00E5FF;
        box-shadow: 0 0 10px rgba(0, 229, 255, 0.2);
    }
    .filter-input::placeholder { color: #64748b; }
    .filter-select option { background: #0d1b2a; color: #FFFFFF; }
    .input-icon-wrapper { position: relative; }
    .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b; font-size: 0.85rem; }
    .input-icon-wrapper .filter-input { padding-left: 2.5rem; }

    /* Table Card */
    .table-card {
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
    }
    .teams-table { width: 100%; border-collapse: collapse; }
    .teams-table th {
        background: rgba(0, 229, 255, 0.05);
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .teams-table td {
        padding: 1rem;
        color: #e2e8f0;
        font-size: 0.875rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.05);
        vertical-align: middle;
    }
    .teams-table tbody tr { transition: all 0.3s ease; }
    .teams-table tbody tr:hover { background: rgba(0, 229, 255, 0.05); }
    .team-logo { width: 45px; height: 45px; border-radius: 10px; object-fit: cover; border: 2px solid rgba(0, 229, 255, 0.3); }
    .team-logo-fallback {
        width: 45px; height: 45px; border-radius: 10px;
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        display: flex; align-items: center; justify-content: center;
        color: white; font-weight: 700; font-size: 0.9rem;
    }
    .team-name { font-weight: 600; color: #FFFFFF; }
    .team-desc { color: #64748b; font-size: 0.8rem; margin-top: 0.2rem; }
    .captain-name { color: #FFFFFF; }
    .captain-email { color: #64748b; font-size: 0.8rem; }

    /* Badges */
    .badge-game { background: rgba(0, 229, 255, 0.15); color: #00E5FF; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .badge-members { background: rgba(99, 102, 241, 0.2); color: #818cf8; padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .badge-status { padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; }
    .status-active { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
    .status-inactive { background: rgba(100, 116, 139, 0.2); color: #94a3b8; }
    .status-suspended { background: rgba(245, 158, 11, 0.2); color: #f59e0b; }
    .status-banned { background: rgba(239, 68, 68, 0.2); color: #ef4444; }

    /* Action Buttons */
    .btn-action { padding: 0.4rem 0.6rem; border-radius: 8px; font-size: 0.8rem; transition: all 0.3s ease; cursor: pointer; border: 1px solid transparent; background: transparent; }
    .btn-action-view { color: #00E5FF; border-color: rgba(0, 229, 255, 0.3); }
    .btn-action-view:hover { background: rgba(0, 229, 255, 0.15); }
    .btn-action-settings { color: #94a3b8; border-color: rgba(148, 163, 184, 0.3); }
    .btn-action-settings:hover { background: rgba(148, 163, 184, 0.15); color: #FFFFFF; }

    /* Dropdown */
    .dropdown-wrapper { position: relative; display: inline-block; }
    .dropdown-menu-custom {
        position: absolute; top: 100%; right: 0;
        background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px; min-width: 160px; padding: 0.5rem 0;
        z-index: 1000; display: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
    }
    .dropdown-wrapper:hover .dropdown-menu-custom { display: block; }
    .dropdown-item-custom {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 1rem; color: #94a3b8; font-size: 0.85rem;
        text-decoration: none; transition: all 0.2s ease;
        cursor: pointer; border: none; background: none; width: 100%; text-align: left;
    }
    .dropdown-item-custom:hover { background: rgba(0, 229, 255, 0.1); color: #FFFFFF; }
    .dropdown-item-custom.text-success { color: #22c55e; }
    .dropdown-item-custom.text-warning { color: #f59e0b; }
    .dropdown-item-custom.text-danger { color: #ef4444; }

    /* Empty State */
    .empty-state { text-align: center; padding: 4rem 2rem; }
    .empty-icon { width: 80px; height: 80px; background: rgba(0, 229, 255, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; }
    .empty-icon i { font-size: 2rem; color: #64748b; }
    .empty-title { color: #FFFFFF; font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
    .empty-text { color: #64748b; font-size: 0.9rem; }

    .custom-checkbox { width: 18px; height: 18px; accent-color: #00E5FF; cursor: pointer; }
    .pagination-wrapper { padding: 1rem 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); display: flex; justify-content: space-between; align-items: center; }
    .pagination-info { color: #64748b; font-size: 0.85rem; }

    /* Modal */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(4px); z-index: 99999; display: none; align-items: center; justify-content: center; padding: 1rem; }
    .modal-overlay.active { display: flex; }
    .modal-content-custom { background: linear-gradient(145deg, #0d1b2a 0%, #000022 100%); border: 1px solid rgba(0, 229, 255, 0.3); border-radius: 20px; width: 100%; max-width: 450px; overflow: hidden; }
    .modal-header-custom { padding: 1.25rem 1.5rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); display: flex; align-items: center; justify-content: space-between; }
    .modal-title-custom { color: #FFFFFF; font-size: 1.1rem; font-weight: 600; }
    .modal-body-custom { padding: 1.5rem; color: #94a3b8; }
    .modal-footer-custom { padding: 1rem 1.5rem; border-top: 1px solid rgba(0, 229, 255, 0.1); display: flex; gap: 0.75rem; justify-content: flex-end; }
    .btn-modal { padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; }
    .btn-modal-cancel { background: rgba(100, 116, 139, 0.2); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); }
    .btn-modal-cancel:hover { background: rgba(100, 116, 139, 0.3); color: #FFFFFF; }
    .btn-modal-primary { background: linear-gradient(135deg, #6366f1, #4f46e5); color: #FFFFFF; border: none; }
    .btn-modal-primary:hover { box-shadow: 0 0 20px rgba(99, 102, 241, 0.4); }
    .warning-box { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 10px; padding: 0.75rem 1rem; margin-top: 1rem; display: flex; align-items: flex-start; gap: 10px; font-size: 0.85rem; color: #fbbf24; }
    .warning-box i { color: #f59e0b; margin-top: 2px; }

    @media (max-width: 768px) {
        .teams-hero { padding: 1.25rem; }
        .hero-content { flex-direction: column; align-items: flex-start !important; gap: 1rem; }
        .hero-buttons { width: 100%; flex-direction: column; }
        .btn-neon { width: 100%; justify-content: center; }
        .filter-grid { grid-template-columns: 1fr !important; }
        .teams-table { display: block; overflow-x: auto; }
    }
</style>
@endpush

@section('content')
<div class="teams-container">
    <div class="max-w-7xl mx-auto px-4 py-6">
        <!-- Hero Section -->
        <div class="teams-hero">
            <div class="hero-content flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div class="hero-icon"><i class="fas fa-users-cog"></i></div>
                    <div>
                        <h1 class="hero-title">{{ __('app.profile.manage_teams') }}</h1>
                        <p class="hero-subtitle">{{ __('app.profile.manage_teams_description') }}</p>
                    </div>
                </div>
                <div class="hero-buttons flex gap-3">
                    <button type="button" class="btn-neon btn-neon-success" onclick="exportTeams()">
                        <i class="fas fa-file-excel"></i><span>{{ __('app.teams.export_csv') }}</span>
                    </button>
                    <button type="button" class="btn-neon" id="bulkActionBtn" disabled onclick="openBulkModal()">
                        <i class="fas fa-layer-group"></i><span>{{ __('app.teams.bulk_operations') }}</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.teams.index') }}" id="filterForm">
                <div class="filter-grid grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="filter-label">{{ __('app.search.search') }}</label>
                        <div class="input-icon-wrapper">
                            <i class="fas fa-search input-icon"></i>
                            <input type="text" class="filter-input" name="search" value="{{ request('search') }}" placeholder="{{ __('app.teams.search_placeholder') }}">
                        </div>
                    </div>
                    <div>
                        <label class="filter-label">{{ __('app.nav.tournaments') }}</label>
                        <select class="filter-select" name="game_id">
                            <option value="">{{ __('app.teams.all') }}</option>
                            @foreach($games as $game)
                            <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>{{ $game->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="filter-label">{{ __('app.teams.status') }}</label>
                        <select class="filter-select" name="status">
                            <option value="">{{ __('app.teams.all') }}</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('app.teams.active') }}</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('app.teams.inactive') }}</option>
                            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>{{ __('app.teams.suspended') }}</option>
                            <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>{{ __('app.teams.banned') }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="filter-label">{{ __('app.teams.sort_by') }}</label>
                        <select class="filter-select" name="sort">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>{{ __('app.teams.creation_date') }}</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>{{ __('app.teams.name_az') }}</option>
                            <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>{{ __('app.teams.status') }}</option>
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-neon flex-1"><i class="fas fa-filter"></i><span>{{ __('app.search.filter') }}</span></button>
                        <a href="{{ route('admin.teams.index') }}" class="btn-neon"><i class="fas fa-times"></i></a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Teams Table -->
        <div class="table-card">
            <div class="overflow-x-auto">
                <table class="teams-table">
                    <thead>
                        <tr>
                            <th class="w-12"><input type="checkbox" id="selectAll" class="custom-checkbox"></th>
                            <th class="w-16">Logo</th>
                            <th>{{ __('app.teams.team_name') }}</th>
                            <th>{{ __('app.teams.team_leader') }}</th>
                            <th>{{ __('app.nav.tournaments') }}</th>
                            <th>{{ __('app.teams.member_count') }}</th>
                            <th>{{ __('app.teams.status') }}</th>
                            <th>{{ __('app.teams.creation_date') }}</th>
                            <th class="w-32">{{ __('app.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teams as $team)
                        <tr>
                            <td><input type="checkbox" class="custom-checkbox team-checkbox" value="{{ $team->id }}"></td>
                            <td>
                                @if($team->logo_url)
                                <img src="{{ asset('uploads/' . $team->logo_url) }}" class="team-logo" alt="Logo">
                                @else
                                <div class="team-logo-fallback">{{ strtoupper(substr($team->name, 0, 2)) }}</div>
                                @endif
                            </td>
                            <td>
                                <div class="team-name">{{ $team->name }}</div>
                                @if($team->description)<div class="team-desc">{{ Str::limit($team->description, 50) }}</div>@endif
                            </td>
                            <td>
                                @if($team->captain)
                                <div class="captain-name">{{ $team->captain->name }}</div>
                                <div class="captain-email">{{ $team->captain->email }}</div>
                                @else
                                <span class="text-gray-500">{{ __('app.teams.no_captain') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($team->game)<span class="badge-game">{{ $team->game->name }}</span>
                                @else<span class="text-gray-500">{{ __('app.teams.not_selected') }}</span>@endif
                            </td>
                            <td><span class="badge-members">{{ $team->members->count() }}/{{ $team->max_members ?? 'N/A' }}</span></td>
                            <td>
                                <span class="badge-status status-{{ $team->status }}" id="status-badge-{{ $team->id }}">
                                    @if($team->status === 'active') {{ __('app.teams.active') }}
                                    @elseif($team->status === 'inactive') {{ __('app.teams.inactive') }}
                                    @elseif($team->status === 'suspended') {{ __('app.teams.suspended') }}
                                    @else {{ __('app.teams.banned') }}@endif
                                </span>
                            </td>
                            <td>{{ $team->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="flex gap-2">
                                    <button type="button" class="btn-action btn-action-view" onclick="viewTeam({{ $team->id }})" title="{{ __('app.dashboard.view_details') }}"><i class="fas fa-eye"></i></button>
                                    <div class="dropdown-wrapper">
                                        <button type="button" class="btn-action btn-action-settings" title="{{ __('app.teams.change_status') }}"><i class="fas fa-cog"></i></button>
                                        <div class="dropdown-menu-custom">
                                            @if($team->status !== 'active')<button class="dropdown-item-custom text-success" onclick="changeStatus({{ $team->id }}, 'active')"><i class="fas fa-check"></i> {{ __('app.teams.activate') }}</button>@endif
                                            @if($team->status !== 'suspended')<button class="dropdown-item-custom text-warning" onclick="changeStatus({{ $team->id }}, 'suspended')"><i class="fas fa-pause"></i> {{ __('app.teams.suspend') }}</button>@endif
                                            @if($team->status !== 'banned')<button class="dropdown-item-custom text-danger" onclick="changeStatus({{ $team->id }}, 'banned')"><i class="fas fa-ban"></i> {{ __('app.teams.ban') }}</button>@endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
                                <div class="empty-state">
                                    <div class="empty-icon"><i class="fas fa-users"></i></div>
                                    <h3 class="empty-title">{{ __('app.teams.no_teams_found') }}</h3>
                                    <p class="empty-text">Không tìm thấy đội nào phù hợp với bộ lọc</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($teams->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">Hiển thị {{ $teams->firstItem() }} - {{ $teams->lastItem() }} {{ __('app.teams.in_total') }} {{ $teams->total() }} {{ __('app.teams.teams') }}</div>
                {{ $teams->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal-overlay" id="bulkActionModal">
    <div class="modal-content-custom">
        <div class="modal-header-custom">
            <h5 class="modal-title-custom"><i class="fas fa-layer-group mr-2"></i>{{ __('app.teams.bulk_operations') }}</h5>
            <button type="button" class="btn-action" onclick="closeBulkModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body-custom">
            <p>{{ __('app.teams.selected_count') }} <span id="selectedCount" style="color: #00E5FF; font-weight: bold;">0</span> {{ __('app.teams.teams') }}</p>
            <div style="margin-top: 1rem;">
                <label class="filter-label">{{ __('app.teams.select_action') }}:</label>
                <select class="filter-select" id="bulkAction">
                    <option value="">{{ __('app.teams.select_action') }}</option>
                    <option value="activate">{{ __('app.teams.activate') }}</option>
                    <option value="suspend">{{ __('app.teams.suspend') }}</option>
                    <option value="ban">{{ __('app.teams.ban') }}</option>
                    <option value="delete">{{ __('app.common.delete') }}</option>
                </select>
            </div>
            <div class="warning-box"><i class="fas fa-exclamation-triangle"></i><span>{{ __('app.teams.bulk_action_warning') }}</span></div>
        </div>
        <div class="modal-footer-custom">
            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeBulkModal()">{{ __('app.common.cancel') }}</button>
            <button type="button" class="btn-modal btn-modal-primary" onclick="executeBulkAction()">{{ __('app.teams.execute') }}</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('selectAll').addEventListener('change', function() {
        document.querySelectorAll('.team-checkbox').forEach(cb => cb.checked = this.checked);
        updateBulkActionButton();
    });
    document.querySelectorAll('.team-checkbox').forEach(cb => cb.addEventListener('change', updateBulkActionButton));
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() { document.getElementById('filterForm').submit(); });
    });
});
function updateBulkActionButton() {
    const checked = document.querySelectorAll('.team-checkbox:checked').length;
    document.getElementById('bulkActionBtn').disabled = checked === 0;
    document.getElementById('selectedCount').textContent = checked;
}
function viewTeam(id) { window.open(`{{ url('admin/teams') }}/${id}`, '_blank'); }
function changeStatus(teamId, status) {
    if (!confirm('{{ __("app.teams.confirm_status_change") }}?')) return;
    fetch(`{{ url('admin/teams') }}/${teamId}/status`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ status: status })
    }).then(r => r.json()).then(data => { if (data.success) location.reload(); else alert(data.message || 'Error'); }).catch(() => alert('Error'));
}
function openBulkModal() { document.getElementById('bulkActionModal').classList.add('active'); }
function closeBulkModal() { document.getElementById('bulkActionModal').classList.remove('active'); }
function executeBulkAction() {
    const selectedIds = Array.from(document.querySelectorAll('.team-checkbox:checked')).map(cb => cb.value);
    const action = document.getElementById('bulkAction').value;
    if (!action) { alert('{{ __("app.teams.please_select_action") }}'); return; }
    fetch('{{ route("admin.teams.bulk-update") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ team_ids: selectedIds, action: action })
    }).then(r => r.json()).then(data => { if (data.success) location.reload(); else alert(data.message); }).catch(() => alert('Error'));
    closeBulkModal();
}
function exportTeams() { window.open(`{{ route('admin.teams.export') }}?${new URLSearchParams(window.location.search).toString()}`); }
document.getElementById('bulkActionModal').addEventListener('click', function(e) { if (e.target === this) closeBulkModal(); });
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeBulkModal(); });
</script>
@endpush
