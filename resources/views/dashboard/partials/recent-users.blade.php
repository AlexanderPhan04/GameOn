<div class="table-responsive flex-grow-1">
    <table class="table table-hover align-middle">
        <thead>
            <tr style="border-bottom: 2px solid rgba(226, 232, 240, 0.5);">
                <th class="border-0 text-muted fw-semibold">{{ __('app.dashboard.users') }}</th>
                <th class="border-0 text-muted fw-semibold">{{ __('app.auth.email') }}</th>
                <th class="border-0 text-muted fw-semibold">{{ __('app.auth.role') }}</th>
                <th class="border-0 text-muted fw-semibold">{{ __('app.teams.status') }}</th>
                <th class="border-0 text-muted fw-semibold">{{ __('app.dashboard.created_date') }}</th>
                <th class="border-0 text-muted fw-semibold">{{ __('app.common.action') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_users as $user)
            <tr class="user-row" style="transition: all 0.3s ease;" 
                onmouseover="this.style.backgroundColor='rgba(102, 126, 234, 0.05)'"
                onmouseout="this.style.backgroundColor='transparent'">
                <td class="py-3">
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-3">
                            @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" 
                                 class="rounded-circle" 
                                 style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="background: linear-gradient(135deg, #667eea, #764ba2); width: 45px; height: 45px;">
                                <span class="text-white fw-bold">{{ substr($user->display_name, 0, 1) }}</span>
                            </div>
                            @endif
                            @if($user->online_status === 'online')
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle" 
                                  style="width: 12px; height: 12px; border: 2px solid white;"></span>
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ $user->display_name }}</h6>
                            <small class="text-muted">{{ $user->name }}</small>
                        </div>
                    </div>
                </td>
                <td class="py-3">
                    <span class="text-muted">{{ $user->email }}</span>
                </td>
                <td class="py-3">
                    @switch($user->user_role)
                    @case('super_admin')
                    <span class="badge" style="background: linear-gradient(135deg, #ed8936, #dd6b20); color: white;">
                        <i class="fas fa-crown me-1"></i>Super Admin
                    </span>
                    @break
                    @case('admin')
                    <span class="badge" style="background: linear-gradient(135deg, #48bb78, #38a169); color: white;">
                        <i class="fas fa-user-shield me-1"></i>Admin
                    </span>
                    @break
                    @case('player')
                    <span class="badge" style="background: linear-gradient(135deg, #4299e1, #3182ce); color: white;">
                        <i class="fas fa-gamepad me-1"></i>Player
                    </span>
                    @break
                    @case('viewer')
                    <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                        <i class="fas fa-eye me-1"></i>Viewer
                    </span>
                    @break
                    @default
                    <span class="badge bg-light text-dark">{{ ucfirst($user->user_role) }}</span>
                    @endswitch
                </td>
                <td class="py-3">
                    @if($user->status === 'active')
                    <span class="badge bg-success rounded-pill">
                        <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>{{ __('app.dashboard.active') }}
                    </span>
                    @elseif($user->status === 'suspended')
                    <span class="badge bg-warning rounded-pill">
                        <i class="fas fa-pause me-1"></i>{{ __('app.teams.suspended') }}
                    </span>
                    @elseif($user->status === 'banned')
                    <span class="badge bg-danger rounded-pill">
                        <i class="fas fa-ban me-1"></i>{{ __('app.teams.banned') }}
                    </span>
                    @else
                    <span class="badge bg-secondary rounded-pill">
                        <i class="fas fa-trash me-1"></i>{{ __('app.common.deleted') }}
                    </span>
                    @endif
                </td>
                <td class="py-3">
                    <div>
                        <div class="fw-semibold text-dark">{{ $user->created_at->format('d/m/Y') }}</div>
                        <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                    </div>
                </td>
                <td class="py-3">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item view-user-btn" href="#" data-user-id="{{ $user->id }}">
                                <i class="fas fa-eye me-2"></i>{{ __('app.dashboard.view_details') }}
                            </a>
                            @if($user->user_role !== 'super_admin')
                            <a class="dropdown-item edit-user-btn" href="#" data-user-id="{{ $user->id }}">
                                <i class="fas fa-edit me-2"></i>{{ __('app.common.edit') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            @if($user->status !== 'suspended')
                            <a class="dropdown-item text-warning change-status-btn" href="#"
                                data-user-id="{{ $user->id }}" data-status="suspended" data-action="{{ __('app.teams.suspended') }}">
                                <i class="fas fa-pause me-2"></i>{{ __('app.teams.suspended') }}
                            </a>
                            @endif
                            @if($user->status !== 'banned')
                            <a class="dropdown-item text-danger change-status-btn" href="#"
                                data-user-id="{{ $user->id }}" data-status="banned" data-action="{{ __('app.teams.banned') }}">
                                <i class="fas fa-ban me-2"></i>{{ __('app.teams.banned') }}
                            </a>
                            @endif
                            @if($user->status !== 'active')
                            <a class="dropdown-item text-success change-status-btn" href="#"
                                data-user-id="{{ $user->id }}" data-status="active" data-action="{{ __('app.dashboard.active') }}">
                                <i class="fas fa-check-circle me-2"></i>{{ __('app.dashboard.active') }}
                            </a>
                            @endif
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@if($recent_users->hasPages())
<div class="p-3 border-top">
    <div class="d-flex justify-content-center">
        {{ $recent_users->links('pagination::bootstrap-4') }}
    </div>
</div>
@endif

