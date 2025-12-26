@extends('layouts.app')

@section('title', __('app.profile.manage_users'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Hero -->
            <div class="users-hero mb-4">
                <div class="hero-inner d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="hero-title mb-1"><i class="fas fa-users me-2"></i>{{ __('app.profile.manage_users') }}</h1>
                        <p class="hero-subtitle mb-0">{{ __('app.profile.manage_users_description') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-success" onclick="exportUsers()"><i class="fas fa-file-excel me-2"></i>{{ __('app.users.export_csv') }}</button>
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#bulkActionModal" disabled id="bulkActionBtn"><i class="fas fa-layer-group me-2"></i>{{ __('app.users.bulk_operations') }}</button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-none"></div>

                <!-- Toolbar -->
                <div class="card-body border-bottom filter-card">
                    <form method="GET" action="{{ route('admin.users.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">{{ __('app.search.search') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('app.users.search_placeholder') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">{{ __('app.users.role') }}</label>
                                <select class="form-select" name="role">
                                    <option value="">{{ __('app.users.all') }}</option>
                                    @if(Auth::user()->user_role === 'super_admin')
                                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    @endif
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="player" {{ request('role') === 'player' ? 'selected' : '' }}>Player</option>
                                    <option value="viewer" {{ request('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">{{ __('app.users.status') }}</label>
                                <select class="form-select" name="status">
                                    <option value="">{{ __('app.users.all') }}</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('app.users.active') }}</option>
                                    <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>{{ __('app.users.suspended') }}</option>
                                    <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>{{ __('app.users.banned') }}</option>
                                    <option value="deleted" {{ request('status') === 'deleted' ? 'selected' : '' }}>{{ __('app.users.deleted') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small">{{ __('app.users.sort_by') }}</label>
                                <select class="form-select" name="sort">
                                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>{{ __('app.users.creation_date') }}</option>
                                    <option value="last_login" {{ request('sort') === 'last_login' ? 'selected' : '' }}>{{ __('app.users.last_login') }}</option>
                                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>{{ __('app.users.name_az') }}</option>
                                    <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>{{ __('app.users.email_az') }}</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i>{{ __('app.search.filter') }}</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary"><i class="fas fa-times me-1"></i>{{ __('app.common.reset') }}</a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Users Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll" class="form-check-input">
                                    </th>
                                    <th width="60">Avatar</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>{{ __('app.users.role') }}</th>
                                    <th>{{ __('app.users.status') }}</th>
                                    <th>{{ __('app.users.registration_date') }}</th>
                                    <th>{{ __('app.users.last_login') }}</th>
                                    <th width="200">{{ __('app.common.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <input type="checkbox"
                                            class="form-check-input user-checkbox"
                                            value="{{ $user->id }}"
                                            data-role="{{ $user->user_role }}">
                                    </td>
                                    <td>
                                        @if($user->avatar)
                                        <img src="{{ get_avatar_url($user->avatar) }}"
                                            class="rounded-circle"
                                            width="40"
                                            height="40"
                                            alt="Avatar">
                                        @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                            style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->name && $user->email)
                                            <br><small class="text-muted">ID: {{ $user->id }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($user->user_role === 'super_admin') bg-danger
                                            @elseif($user->user_role === 'admin') bg-warning
                                            @elseif($user->user_role === 'player') bg-primary
                                            @else bg-secondary
                                            @endif">
                                            @if($user->user_role === 'super_admin') Super Admin
                                            @elseif($user->user_role === 'admin') Admin
                                            @elseif($user->user_role === 'player') Player
                                            @else Viewer
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="dropdown d-inline">
                                            <span class="badge 
                                                @if($user->status === 'active') bg-success
                                                @elseif($user->status === 'suspended') bg-warning
                                                @elseif($user->status === 'banned') bg-danger
                                                @else bg-secondary
                                                @endif" 
                                                id="status-badge-{{ $user->id }}"
                                                data-bs-toggle="dropdown"
                                                role="button"
                                                aria-expanded="false"
                                                title="{{ __('app.users.click_to_change_status') }}">
                                                @if($user->status === 'active') {{ __('app.users.active') }}
                                                @elseif($user->status === 'suspended') {{ __('app.users.suspended') }}
                                                @elseif($user->status === 'banned') {{ __('app.users.banned') }}
                                                @else {{ __('app.users.deleted') }}
                                                @endif
                                            </span>
                                            <ul class="dropdown-menu">
                                                @if($user->status !== 'active')
                                                <li><a class="dropdown-item change-status-btn" href="#" data-user-id="{{ $user->id }}" data-status="active" data-action="{{ __('app.users.activate') }}"><i class="fas fa-check me-2 text-success"></i>{{ __('app.users.activate') }}</a></li>
                                                @endif
                                                @if($user->status !== 'suspended')
                                                <li><a class="dropdown-item change-status-btn" href="#" data-user-id="{{ $user->id }}" data-status="suspended" data-action="{{ __('app.users.suspend') }}"><i class="fas fa-pause me-2 text-warning"></i>{{ __('app.users.suspend') }}</a></li>
                                                @endif
                                                @if($user->status !== 'banned')
                                                <li><a class="dropdown-item change-status-btn" href="#" data-user-id="{{ $user->id }}" data-status="banned" data-action="{{ __('app.users.ban') }}"><i class="fas fa-ban me-2 text-danger"></i>{{ __('app.users.ban') }}</a></li>
                                                @endif
                                                @if($user->status !== 'deleted')
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item change-status-btn" href="#" data-user-id="{{ $user->id }}" data-status="deleted" data-action="{{ __('app.common.delete') }}"><i class="fas fa-trash me-2 text-danger"></i>{{ __('app.common.delete') }}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        {{ $user->last_login ? $user->last_login->format('d/m/Y H:i') : __('app.users.never_logged_in') }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-primary view-user-btn" data-bs-toggle="offcanvas" data-bs-target="#userDetails" aria-controls="userDetails"
                                                data-user-id="{{ $user->id }}"
                                                title="{{ __('app.dashboard.view_details') }}" data-bs-toggle2="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @php
                                            $currentUser = Auth::user();
                                            $canEditUser = false;

                                            if ($currentUser->user_role === 'super_admin') {
                                            // Super Admin có thể edit tất cả, trừ chính mình
                                            $canEditUser = $user->id !== $currentUser->id;
                                            } elseif ($currentUser->user_role === 'admin') {
                                            // Admin chỉ có thể edit Player và Viewer
                                            $canEditUser = $user->id !== $currentUser->id &&
                                            !in_array($user->user_role, ['super_admin', 'admin']);
                                            }
                                            @endphp

                                            @if($canEditUser)
                                            <button type="button" class="btn btn-outline-warning edit-user-btn" data-user-id="{{ $user->id }}" title="{{ __('app.common.edit') }}" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle2="tooltip" title="{{ __('app.users.change_status') }}"
                                                    data-bs-toggle="dropdown" title="{{ __('app.users.change_status') }}">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu" id="dropdown-menu-{{ $user->id }}">
                                                    @if($user->status !== 'active')
                                                    <li>
                                                        <a class="dropdown-item text-success change-status-btn"
                                                            href="#" data-user-id="{{ $user->id }}" data-status="active" data-action="{{ __('app.users.activate') }}">
                                                            <i class="fas fa-check me-2"></i>{{ __('app.users.activate') }}
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if($user->status !== 'suspended')
                                                    <li>
                                                        <a class="dropdown-item text-warning change-status-btn"
                                                            href="#" data-user-id="{{ $user->id }}" data-status="suspended" data-action="{{ __('app.users.suspend') }}">
                                                            <i class="fas fa-pause me-2"></i>{{ __('app.users.suspend') }}
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if($user->status !== 'banned')
                                                    <li>
                                                        <a class="dropdown-item text-danger change-status-btn"
                                                            href="#" data-user-id="{{ $user->id }}" data-status="banned" data-action="{{ __('app.users.ban') }}">
                                                            <i class="fas fa-ban me-2"></i>{{ __('app.users.ban') }}
                                                        </a>
                                                    </li>
                                                    @endif
                                                    @if($user->status !== 'deleted')
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger change-status-btn"
                                                            href="#" data-user-id="{{ $user->id }}" data-status="deleted" data-action="{{ __('app.common.delete') }}">
                                                            <i class="fas fa-trash me-2"></i>{{ __('app.common.delete') }}
                                                        </a>
                                                    </li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @else
                                            <button type="button" class="btn btn-outline-secondary" disabled title="{{ __('app.users.no_permission_to_edit') }}">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">{{ __('app.users.no_users_found') }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ __('app.users.showing') }} {{ $users->firstItem() }} - {{ $users->lastItem() }}
                            {{ __('app.users.in_total') }} {{ $users->total() }} {{ __('app.users.users') }}
                        </div>
                        {{ $users->links() }}
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
                <h5 class="modal-title">{{ __('app.users.bulk_operations') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('app.users.selected_count') }} <span id="selectedCount">0</span> {{ __('app.users.users') }}</p>
                <div class="mb-3">
                    <label class="form-label">{{ __('app.users.select_action') }}:</label>
                    <select class="form-select" id="bulkAction">
                        <option value="">{{ __('app.users.select_action') }}</option>
                        <option value="activate">{{ __('app.users.activate') }}</option>
                        <option value="suspend">{{ __('app.users.suspend') }}</option>
                        <option value="ban">{{ __('app.users.ban') }}</option>
                        <option value="delete">{{ __('app.common.delete') }}</option>
                    </select>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('app.users.bulk_action_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.common.cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="executeBulkAction()">{{ __('app.users.execute') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas: User Details -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="userDetails" aria-labelledby="userDetailsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="userDetailsLabel">{{ __('app.users.user_info') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div id="userDetailsContent" class="text-muted">{{ __('app.users.select_user_to_view_details') }}</div>
    </div>
    <div class="offcanvas-footer p-3 border-top">
        <button class="btn btn-outline-secondary w-100" data-bs-dismiss="offcanvas">{{ __('app.common.close') }}</button>
    </div>
    
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalTitle">{{ __('app.users.confirm_action') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirmationModalBody">
                {{ __('app.users.confirm_action_message') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.common.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmActionBtn">{{ __('app.common.confirm') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notification-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1055"></div>
@endsection

@push('styles')
<style>
    /* Hero */
    .users-hero{position:relative;border-radius:16px;overflow:hidden;background:linear-gradient(135deg,#22c55e,#3b82f6);color:#fff}
    .users-hero .hero-inner{padding:1.75rem}
    .hero-title{font-weight:700;font-size:1.6rem;text-shadow:0 2px 4px rgba(0,0,0,.2)}
    .hero-subtitle{opacity:.9}

    /* Filter */
    .filter-card .form-label{font-weight:600;color:#495057}
    .filter-card .form-control,.filter-card .form-select{border:2px solid #e9ecef;border-radius:10px;padding:.6rem .9rem}
    .filter-card .input-group-text{background:#f8f9fa;border:2px solid #e9ecef;border-right:none;border-radius:10px 0 0 10px}

    /* Table improvements */
    .table th {font-weight:600;font-size:.875rem;text-transform:uppercase;letter-spacing:.025em}
    tr.selected-row {background-color:#f3f6ff !important}
    .avatar-fallback{width:40px;height:40px;border-radius:50%;background:#6366f1;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    .badge{font-weight:600}
    /* Offcanvas adjustments */
    #userDetails .offcanvas-body{padding:0}
    #userDetails #user-detail-root{padding:1rem}
    #userDetails .breadcrumb{display:none}
    #userDetails .card{border-radius:12px;box-shadow:none}
    #userDetails .card .card-header{padding:.5rem .75rem}
    #userDetails .card .card-body{padding:.75rem}
    #userDetails .rounded-circle{box-shadow:0 4px 12px rgba(0,0,0,.08)}
    /* Constrain avatar size inside offcanvas user details */
    #userDetails .card-body img.rounded-circle{width:72px!important;height:72px!important;object-fit:cover}
    #userDetails .card-body .rounded-circle.bg-primary{width:72px!important;height:72px!important;font-size:28px!important}
    /* Compact header inside offcanvas */
    #userDetails #user-detail-root .d-flex.justify-content-between.align-items-center{margin-bottom:.75rem !important}
    #userDetails #user-detail-root h3{font-size:1.1rem;font-weight:700;margin:0}
    #userDetails #user-detail-root .btn{padding:.35rem .6rem;font-size:.85rem;border-radius:8px}
    #userDetails #user-detail-root .btn.btn-secondary{display:none}
    /* Pills */
    #userDetails .badge{border-radius:999px;padding:.35rem .6rem}

    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    tr.selected-row {
        background-color: #e9ecef !important;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {

        // Enable tooltips globally
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [data-bs-toggle2="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) })

        // Select all functionality
        $('#selectAll').change(function() {
            $('.user-checkbox').prop('checked', this.checked).trigger('change');
        });

        $('.user-checkbox').change(function() {
            updateBulkActionButton();

            if ($(this).is(':checked')) {
                $(this).closest('tr').addClass('selected-row');
            } else {
                $(this).closest('tr').removeClass('selected-row');
            }

            // Update select all checkbox
            const total = $('.user-checkbox').length;
            const checked = $('.user-checkbox:checked').length;
            $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
            $('#selectAll').prop('checked', checked > 0 && checked === total);
        });

        // Filter form auto-submit on change
        $('#filterForm .form-select').change(function() {
            $('#filterForm').submit();
        });

        // Event listeners cho buttons
        $(document).on('click', '.view-user-btn', function() {
            const userId = $(this).data('user-id');
            loadUserDetails(userId);
        });

        $(document).on('click', '.edit-user-btn', function() {
            const userId = $(this).data('user-id');
            console.log('Edit user clicked:', userId);
            editUser(userId);
        });

        $(document).on('click', '.change-status-btn', function(e) {
            e.preventDefault();
            const userId = $(this).data('user-id');
            const status = $(this).data('status');
            const action = $(this).data('action');

            console.log('Change status clicked:', {
                userId,
                status,
                action
            });

            // Close the dropdown
            $(this).closest('.dropdown-menu').siblings('.dropdown-toggle').dropdown('hide');

            promptChangeStatus(userId, status, action);
        });
    });

    function updateBulkActionButton() {
        const checked = $('.user-checkbox:checked').length;
        $('#bulkActionBtn').prop('disabled', checked === 0);
        $('#selectedCount').text(checked);
    }

    function viewUser(id) {
        const url = `{{ route('admin.users.show', ['user' => '__ID__']) }}`.replace('__ID__', id);
        window.open(url, '_blank');
    }

    // Load user details into offcanvas
    function loadUserDetails(id) {
        $('#userDetailsContent').html('<div class="text-center text-muted py-4"><div class="spinner-border" role="status"></div><div class="mt-2">{{ __("app.users.loading") }}</div></div>');
        $.get(`{{ route('admin.users.show', ['user' => '__ID__']) }}`.replace('__ID__', id), { partial: true })
            .done(function(html) {
                $('#userDetailsContent').html(html);
            })
            .fail(function() {
                $('#userDetailsContent').html('<div class="text-danger">{{ __("app.users.cannot_load_user_info") }}</div>');
            });
    }

    function editUser(id) {
        const url = `{{ route('admin.users.edit', ['user' => '__ID__']) }}`.replace('__ID__', id);
        console.log('Navigating to edit user URL:', url);
        window.location.href = url;
    }

    // Mở modal xác nhận trước khi thay đổi trạng thái
    function promptChangeStatus(userId, status, actionText) {
        confirmDialog('Xác nhận ' + actionText, `Bạn có chắc chắn muốn ${actionText.toLowerCase()} người dùng này?`)
            .then(function(ok){ if (ok) changeStatus(userId, status); });
    }

    // Hàm này sẽ xây dựng lại HTML cho dropdown menu dựa trên trạng thái mới
    function updateActionDropdown(userId, newStatus) {
        const dropdownMenu = $(`#dropdown-menu-${userId}`);
        dropdownMenu.empty(); // Xóa các mục cũ

        let newItemsHtml = '';

        if (newStatus !== 'active') {
            newItemsHtml += `<li><a class="dropdown-item text-success change-status-btn" href="#" data-user-id="${userId}" data-status="active" data-action="{{ __("app.users.activate") }}"><i class="fas fa-check me-2"></i>{{ __("app.users.activate") }}</a></li>`;
        }
        if (newStatus !== 'suspended') {
            newItemsHtml += `<li><a class="dropdown-item text-warning change-status-btn" href="#" data-user-id="${userId}" data-status="suspended" data-action="{{ __("app.users.suspend") }}"><i class="fas fa-pause me-2"></i>{{ __("app.users.suspend") }}</a></li>`;
        }
        if (newStatus !== 'banned') {
            newItemsHtml += `<li><a class="dropdown-item text-danger change-status-btn" href="#" data-user-id="${userId}" data-status="banned" data-action="{{ __("app.users.ban") }}"><i class="fas fa-ban me-2"></i>{{ __("app.users.ban") }}</a></li>`;
        }
        if (newStatus !== 'deleted') {
            newItemsHtml += `<li><hr class="dropdown-divider"></li>`;
            newItemsHtml += `<li><a class="dropdown-item text-danger change-status-btn" href="#" data-user-id="${userId}" data-status="deleted" data-action="{{ __("app.common.delete") }}"><i class="fas fa-trash me-2"></i>{{ __("app.common.delete") }}</a></li>`;
        }

        dropdownMenu.html(newItemsHtml);
    }

    // Hàm thực hiện thay đổi trạng thái sau khi xác nhận
    function changeStatus(userId, status) {
        console.log('changeStatus called:', userId, status);

        $.ajax({
            url: `{{ route('admin.users.update-status', ['user' => '__USER_ID__']) }}`.replace('__USER_ID__', userId),
            method: 'PATCH',
            data: {
                status: status,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('AJAX Success:', response);
                if (response.success) {
                    // Cập nhật badge
                    const badge = $(`#status-badge-${userId}`);
                    console.log('Badge element:', badge.length);
                    badge.removeClass().addClass(`badge ${response.badge_class}`);
                    badge.text(response.status_display);

                    showNotification('success', response.message);

                    // Cập nhật lại dropdown action một cách an toàn
                    updateActionDropdown(userId, status);

                } else {
                    showNotification('error', response.message || 'Có lỗi xảy ra khi cập nhật trạng thái');
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', xhr, status, error);
                console.log('Response Text:', xhr.responseText);

                let errorMessage = 'Có lỗi xảy ra, vui lòng thử lại.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    errorMessage = 'Bạn không có quyền thực hiện hành động này.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Không tìm thấy người dùng.';
                } else if (xhr.status === 422) {
                    errorMessage = 'Dữ liệu không hợp lệ.';
                }

                showNotification('error', errorMessage);
            }
        });
    }

    function executeBulkAction() {
        const selectedIds = $('.user-checkbox:checked').map(function() {
            return this.value;
        }).get();

        const action = $('#bulkAction').val();

        if (!action) {
            // Thay thế alert() bằng notification
            showNotification('error', 'Vui lòng chọn một hành động để thực hiện.');
            return;
        }

        $.ajax({
            url: '{{ route("admin.users.bulk-update") }}',
            method: 'POST',
            data: {
                user_ids: selectedIds,
                action: action,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    // Đợi 2 giây để user đọc thông báo rồi tải lại trang
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra khi thực hiện thao tác hàng loạt.');
            }
        });

        $('#bulkActionModal').modal('hide');
    }

    function exportUsers() {
        const params = new URLSearchParams(window.location.search);
        window.open(`{{ route('admin.users.export') }}?${params.toString()}`);
    }

    // Chuẩn hóa thông báo: sử dụng notify() toàn cục
    function showNotification(type, message) { try { window.notify(type, message); } catch(e) { console.log(type.toUpperCase()+':', message); } }
</script>
@endpush