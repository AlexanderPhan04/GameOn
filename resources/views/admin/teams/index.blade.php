@extends('layouts.app')

@section('title', __('app.profile.manage_teams'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Hero -->
            <div class="teams-hero mb-4">
                <div class="hero-inner d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="hero-title mb-1">
                            <i class="fas fa-users-cog me-2"></i>
                            {{ __('app.profile.manage_teams') }}
                        </h1>
                        <p class="hero-subtitle mb-0">{{ __('app.profile.manage_teams_description') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="exportTeams()">
                            <i class="fas fa-file-excel me-2"></i>{{ __('app.teams.export_csv') }}
                        </button>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#bulkActionModal" disabled id="bulkActionBtn">
                            <i class="fas fa-layer-group me-2"></i>{{ __('app.teams.bulk_operations') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-none"></div>

                <!-- Toolbar -->
                <div class="card-body border-bottom filter-card">
                    <form method="GET" action="{{ route('admin.teams.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('app.search.search') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text"
                                        class="form-control"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="{{ __('app.teams.search_placeholder') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">{{ __('app.nav.tournaments') }}</label>
                                <select class="form-select" name="game_id">
                                    <option value="">{{ __('app.teams.all') }}</option>
                                    @foreach($games as $game)
                                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                                        {{ $game->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">{{ __('app.teams.status') }}</label>
                                <select class="form-select" name="status">
                                    <option value="">{{ __('app.teams.all') }}</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('app.teams.active') }}</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('app.teams.inactive') }}</option>
                                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>{{ __('app.teams.suspended') }}</option>
                                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>{{ __('app.teams.banned') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">{{ __('app.teams.sort_by') }}</label>
                                <select class="form-select" name="sort">
                                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>{{ __('app.teams.creation_date') }}</option>
                                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>{{ __('app.teams.name_az') }}</option>
                                    <option value="status" {{ request('sort') === 'status' ? 'selected' : '' }}>{{ __('app.teams.status') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i> {{ __('app.search.filter') }}
                                </button>
                                <a href="{{ route('admin.teams.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> {{ __('app.common.reset') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Teams Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 teams-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th width="60">Logo</th>
                                    <th>{{ __('app.teams.team_name') }}</th>
                                    <th>{{ __('app.teams.team_leader') }}</th>
                                    <th>{{ __('app.nav.tournaments') }}</th>
                                    <th>{{ __('app.teams.member_count') }}</th>
                                    <th>{{ __('app.teams.status') }}</th>
                                    <th>{{ __('app.teams.creation_date') }}</th>
                                    <th width="200">{{ __('app.common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teams as $team)
                                <tr class="team-row">
                                    <td>
                                        <input type="checkbox"
                                            class="form-check-input team-checkbox"
                                            value="{{ $team->id }}">
                                    </td>
                                    <td>
                                        @if($team->logo_url)
                                        <img src="{{ asset('storage/' . $team->logo_url) }}"
                                            class="rounded team-logo"
                                            width="40"
                                            height="40"
                                            alt="Logo">
                                        @else
                                        <div class="team-logo-fallback">{{ strtoupper(substr($team->name, 0, 2)) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="team-name">
                                            <strong class="d-block">{{ $team->name }}</strong>
                                            @if($team->description)
                                            <small class="text-muted">{{ Str::limit($team->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($team->captain)
                                        <div class="captain-info">
                                            {{ $team->captain->name }}
                                            <small class="text-muted d-block">{{ $team->captain->email }}</small>
                                        </div>
                                        @else
                                        <span class="text-muted">{{ __('app.teams.no_captain') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($team->game)
                                        <span class="badge bg-info">{{ $team->game->name }}</span>
                                        @else
                                        <span class="text-muted">{{ __('app.teams.not_selected') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ $team->members->count() }}/{{ $team->max_members ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($team->status === 'active') bg-success
                                            @elseif($team->status === 'inactive') bg-secondary
                                            @elseif($team->status === 'suspended') bg-warning
                                            @else bg-danger
                                            @endif" id="status-badge-{{ $team->id }}">
                                            @if($team->status === 'active') {{ __('app.teams.active') }}
                                            @elseif($team->status === 'inactive') {{ __('app.teams.inactive') }}
                                            @elseif($team->status === 'suspended') {{ __('app.teams.suspended') }}
                                            @else {{ __('app.teams.banned') }}
                                            @endif
                                        </span>
                                    </td>
                                    <td>{{ $team->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary view-team-btn"
                                                data-team-id="{{ $team->id }}"
                                                title="{{ __('app.dashboard.view_details') }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                    data-bs-toggle="dropdown" title="{{ __('app.teams.change_status') }}">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu" id="dropdown-menu-{{ $team->id }}">
                                                    @if($team->status !== 'active')
                                                    <li>
                                                        <a class="dropdown-item text-success change-team-status-btn"
                                                            href="#" data-team-id="{{ $team->id }}" data-status="active"                                                             data-action="{{ __('app.teams.activate') }}">
                                                            <i class="fas fa-check me-2"></i>{{ __('app.teams.activate') }}
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if($team->status !== 'suspended')
                                                    <li>
                                                        <a class="dropdown-item text-warning change-team-status-btn"
                                                            href="#" data-team-id="{{ $team->id }}" data-status="suspended"                                                             data-action="{{ __('app.teams.suspend') }}">
                                                            <i class="fas fa-pause me-2"></i>{{ __('app.teams.suspend') }}
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if($team->status !== 'banned')
                                                    <li>
                                                        <a class="dropdown-item text-danger change-team-status-btn"
                                                            href="#" data-team-id="{{ $team->id }}" data-status="banned"                                                             data-action="{{ __('app.teams.ban') }}">
                                                            <i class="fas fa-ban me-2"></i>{{ __('app.teams.ban') }}
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('app.teams.no_teams_found') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($teams->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Hiển thị {{ $teams->firstItem() }} - {{ $teams->lastItem() }}
                            {{ __('app.teams.in_total') }} {{ $teams->total() }} {{ __('app.teams.teams') }}
                        </div>
                        {{ $teams->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Bulk Action Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('app.teams.bulk_operations') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.teams.selected_count') }} <span id="selectedCount">0</span> {{ __('app.teams.teams') }}</p>
                <div class="mb-3">
                    <label class="form-label">{{ __('app.teams.select_action') }}:</label>
                    <select class="form-select" id="bulkAction">
                        <option value="">{{ __('app.teams.select_action') }}</option>
                        <option value="activate">{{ __('app.teams.activate') }}</option>
                        <option value="suspend">{{ __('app.teams.suspend') }}</option>
                        <option value="ban">{{ __('app.teams.ban') }}</option>
                        <option value="delete">{{ __('app.common.delete') }}</option>
                    </select>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('app.teams.bulk_action_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.common.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">{{ __('app.teams.execute') }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    /* Hero */
    .teams-hero{position:relative;border-radius:16px;overflow:hidden;background:linear-gradient(135deg,#0ea5e9,#6366f1);color:#fff}
    .teams-hero .hero-inner{position:relative;padding:1.75rem}
    .hero-title{font-weight:700;font-size:1.6rem;text-shadow:0 2px 4px rgba(0,0,0,.2)}
    .hero-subtitle{opacity:.9}

    /* Filter */
    .filter-card .form-label{font-weight:600;color:#495057}
    .filter-card .form-control,.filter-card .form-select{border:2px solid #e9ecef;border-radius:10px;padding:.6rem .9rem}
    .filter-card .form-control:focus,.filter-card .form-select:focus{border-color:#6366f1;box-shadow:0 0 0 .2rem rgba(99,102,241,.2)}
    .input-group-text{background:#f8f9fa;border:2px solid #e9ecef;border-right:none;border-radius:10px 0 0 10px}

    /* Table */
    .teams-table th{font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.025em}
    .team-logo{box-shadow:0 2px 6px rgba(0,0,0,.1)}
    .team-logo-fallback{width:40px;height:40px;border-radius:8px;background:#6366f1;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    .team-row:hover{background:#fafbff}
    .team-name small{display:block;margin-top:.15rem}

    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .team-checkbox:checked+td {
        background-color: #f8f9fa;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Select all functionality
        $('#selectAll').change(function() {
            $('.team-checkbox').prop('checked', this.checked);
            updateBulkActionButton();
        });

        $('.team-checkbox').change(function() {
            updateBulkActionButton();

            // Update select all checkbox
            const total = $('.team-checkbox').length;
            const checked = $('.team-checkbox:checked').length;
            $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
            $('#selectAll').prop('checked', checked === total);
        });

        // Filter form auto-submit on change
        $('.form-select').change(function() {
            $('#filterForm').submit();
        });

        // Event listeners cho teams
        $(document).on('click', '.view-team-btn', function() {
            const teamId = $(this).data('team-id');
            viewTeam(teamId);
        });

        $(document).on('click', '.change-team-status-btn', function(e) {
            e.preventDefault();
            const teamId = $(this).data('team-id');
            const status = $(this).data('status');
            const action = $(this).data('action');
            promptChangeTeamStatus(teamId, status, action);
        });
    });

    function updateBulkActionButton() {
        const checked = $('.team-checkbox:checked').length;
        $('#bulkActionBtn').prop('disabled', checked === 0);
        $('#selectedCount').text(checked);
    }

    function viewTeam(id) {
        window.open(`{{ url('admin/teams') }}/${id}`, '_blank');
    }

    function promptChangeTeamStatus(teamId, status, actionText) {
        if (!confirm('{{ __("app.teams.confirm_status_change") }} ' + actionText.toLowerCase() + ' {{ __("app.teams.this_team") }}?')) {
            return;
        }
        changeStatus(teamId, status);
    }

    function changeStatus(teamId, status) {
        $.ajax({
            url: `{{ url('admin/teams') }}/${teamId}/status`,
            method: 'PATCH',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update badge
                    const badge = $(`#status-badge-${teamId}`);
                    badge.removeClass().addClass(`badge ${response.badge_class}`);
                    badge.text(response.status_display);

                    // Update dropdown menu
                    updateTeamActionDropdown(teamId, status);

                    // Show success message
                    showNotification('success', response.message);
                } else {
                    showNotification('error', response.message || '{{ __("app.teams.status_update_error") }}');
                }
            },
            error: function() {
                showNotification('error', '{{ __("app.teams.status_update_error") }}');
            }
        });
    }

    function updateTeamActionDropdown(teamId, newStatus) {
        const dropdownMenu = $(`#dropdown-menu-${teamId}`);
        dropdownMenu.empty();

        let newItemsHtml = '';

        if (newStatus !== 'active') {
            newItemsHtml += `<li><a class="dropdown-item text-success change-team-status-btn" href="#" data-team-id="${teamId}" data-status="active" data-action="{{ __("app.teams.activate") }}"><i class="fas fa-check me-2"></i>{{ __("app.teams.activate") }}</a></li>`;
        }
        if (newStatus !== 'suspended') {
            newItemsHtml += `<li><a class="dropdown-item text-warning change-team-status-btn" href="#" data-team-id="${teamId}" data-status="suspended" data-action="{{ __("app.teams.suspend") }}"><i class="fas fa-pause me-2"></i>{{ __("app.teams.suspend") }}</a></li>`;
        }
        if (newStatus !== 'banned') {
            newItemsHtml += `<li><a class="dropdown-item text-danger change-team-status-btn" href="#" data-team-id="${teamId}" data-status="banned" data-action="{{ __("app.teams.ban") }}"><i class="fas fa-ban me-2"></i>{{ __("app.teams.ban") }}</a></li>`;
        }

        dropdownMenu.html(newItemsHtml);
    }

    function executeBulkAction() {
        const selectedIds = $('.team-checkbox:checked').map(function() {
            return this.value;
        }).get();

        const action = $('#bulkAction').val();

        if (!action) {
            alert('{{ __("app.teams.please_select_action") }}');
            return;
        }

        $.ajax({
            url: '{{ route("admin.teams.bulk-update") }}',
            method: 'POST',
            data: {
                team_ids: selectedIds,
                action: action,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    location.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', '{{ __("app.teams.bulk_action_error") }}');
            }
        });

        $('#bulkActionModal').modal('hide');
    }

    function exportTeams() {
        const params = new URLSearchParams(window.location.search);
        window.open(`{{ route('admin.teams.export') }}?${params.toString()}`);
    }

    function showNotification(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

        $('body').prepend(alertHtml);

        setTimeout(() => {
            $('.alert').first().alert('close');
        }, 5000);
    }
</script>
@endpush