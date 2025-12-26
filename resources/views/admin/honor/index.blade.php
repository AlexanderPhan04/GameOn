@extends('layouts.app')

@section('title', __('app.honor.manage_title'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy me-2"></i>{{ __('app.honor.manage_title') }}
                    </h6>
                    <a href="{{ route('admin.honor.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>{{ __('app.honor.create_event') }}
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="20%">{{ __('app.honor.col_event_name') }}</th>
                                        <th width="15%">{{ __('app.honor.col_mode') }}</th>
                                        <th width="15%">{{ __('app.honor.col_target') }}</th>
                                        <th width="15%">{{ __('app.honor.col_time') }}</th>
                                        <th width="10%">{{ __('app.honor.col_status') }}</th>
                                        <th width="10%">{{ __('app.honor.col_votes') }}</th>
                                        <th width="10%">{{ __('app.honor.col_actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>{{ $event->id }}</td>
                                            <td>
                                                <div>
                                                    <strong>{{ $event->name }}</strong>
                                                    @if($event->description)
                                                        <br><small class="text-muted">{{ Str::limit($event->description, 50) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $event->mode === 'event' ? 'warning' : 'success' }}">
                                                    {{ $event->mode === 'event' ? __('app.honor.mode_event') : __('app.honor.mode_free') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($event->target_type) }}</span>
                                            </td>
                                            <td>
                                                @if($event->start_time)
                                                    <small class="text-muted">{{ __('app.honor.time_from') }}: {{ $event->start_time->format('d/m/Y H:i') }}</small><br>
                                                @endif
                                                @if($event->end_time)
                                                    <small class="text-muted">{{ __('app.honor.time_to') }}: {{ $event->end_time->format('d/m/Y H:i') }}</small>
                                                @else
                                                    <small class="text-success">{{ __('app.honor.time_unlimited') }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                @if($event->isCurrentlyRunning())
                                                    <span class="badge badge-success">{{ __('app.honor.status_active') }}</span>
                                                @else
                                                    <span class="badge badge-{{ $event->is_active ? 'warning' : 'secondary' }}">
                                                        {{ $event->is_active ? __('app.honor.status_paused') : __('app.honor.status_off') }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="text-center">
                                                    <div class="h6 mb-0">{{ $event->getTotalVotesCount() }}</div>
                                                    <small class="text-muted">{{ __('app.honor.votes') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.honor.show', $event) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="{{ __('app.honor.view_detail') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.honor.edit', $event) }}" 
                                                       class="btn btn-sm btn-outline-warning" title="{{ __('app.honor.edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-{{ $event->is_active ? 'secondary' : 'success' }}" 
                                                            onclick="toggleStatus({{ $event->id }})" title="{{ $event->is_active ? __('app.honor.turn_off') : __('app.honor.turn_on') }}">
                                                        <i class="fas fa-{{ $event->is_active ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $events->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('app.honor.empty_title') }}</h5>
                            <p class="text-muted">{{ __('app.honor.empty_desc') }}</p>
                            <a href="{{ route('admin.honor.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i>{{ __('app.honor.create_event') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toggle Status Form -->
<form id="toggleForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

<!-- Reset Votes Form -->
<form id="resetForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function toggleStatus(eventId) {
    if (confirm('{{ __('honor.confirm_toggle') }}')) {
        const form = document.getElementById('toggleForm');
        form.action = `/admin/honor/${eventId}/toggle`;
        form.submit();
    }
}

function resetVotes(eventId) {
    if (confirm('{{ __('honor.confirm_reset') }}')) {
        const form = document.getElementById('resetForm');
        form.action = `/admin/honor/${eventId}/reset`;
        form.submit();
    }
}

function deleteEvent(eventId) {
    if (confirm('{{ __('honor.confirm_delete') }}')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/honor/${eventId}`;
        form.submit();
    }
}
</script>
@endpush
