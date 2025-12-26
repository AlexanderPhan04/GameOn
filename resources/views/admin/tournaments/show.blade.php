@extends('layouts.app')

@section('title', $tournament->name)

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="mb-0">{{ $tournament->name }}</h2>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Quay lại
                    </a>
                    <a href="{{ route('admin.tournaments.edit', $tournament->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Chỉnh sửa
                    </a>
                    <button class="btn btn-outline-danger" id="delete-tournament-btn" data-tournament-id="{{ $tournament->id }}" data-tournament-name="{{ $tournament->name }}">
                        <i class="fas fa-trash me-1"></i>Xóa
                    </button>
                </div>
            </div>

            <!-- Banner -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="position-relative">
                    @if($tournament->banner_url)
                    <img src="{{ $tournament->banner_url }}" class="card-img-top" style="height: 300px; object-fit: cover;">
                    @else
                    <div class="card-img-top bg-gradient-primary d-flex align-items-center justify-content-center text-white" style="height: 300px;">
                        <div class="text-center">
                            <i class="fas fa-trophy fa-4x mb-3 opacity-75"></i>
                            <h3>{{ $tournament->name }}</h3>
                        </div>
                    </div>
                    @endif

                    <!-- Status Badge -->
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-{{ $tournament->status_color }} fs-5">
                            {{ $tournament->status_label }}
                        </span>
                    </div>

                    <!-- Logo -->
                    @if($tournament->logo_url)
                    <div class="position-absolute bottom-0 start-0 m-4">
                        <img src="{{ $tournament->logo_url }}" class="rounded-circle border border-4 border-white shadow"
                            style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Left Column -->
                <div class="col-lg-8">
                    <!-- Thông tin cơ bản -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Game</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-gamepad me-1"></i>
                                        {{ $tournament->game->name ?? 'N/A' }}
                                    </p>

                                    <h6 class="text-muted">Thể loại thi đấu</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $tournament->competition_type_label }}
                                        @if($tournament->format)
                                        <span class="badge bg-secondary ms-2">{{ $tournament->format }}</span>
                                        @endif
                                    </p>

                                    <h6 class="text-muted">Hình thức thi đấu</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-sitemap me-1"></i>
                                        {{ $tournament->tournament_format_label }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Số lượng tham gia tối đa</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-hashtag me-1"></i>
                                        {{ $tournament->max_participants }}
                                        {{ $tournament->competition_type === 'team' ? 'đội' : 'người chơi' }}
                                    </p>

                                    @if($tournament->substitute_players > 0)
                                    <h6 class="text-muted">Số người dự bị</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-user-plus me-1"></i>
                                        {{ $tournament->substitute_players }} người/đội
                                    </p>
                                    @endif

                                    <h6 class="text-muted">Hình thức tham gia</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-{{ $tournament->participation_type === 'public' ? 'globe' : 'lock' }} me-1"></i>
                                        {{ $tournament->participation_type_label }}
                                    </p>
                                </div>
                            </div>

                            @if($tournament->description)
                            <hr>
                            <h6 class="text-muted">Mô tả</h6>
                            <p class="mb-0">{{ $tournament->description }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Thời gian & Địa điểm -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Thời gian & Địa điểm
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted">Thời gian tổ chức</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $tournament->formatted_date_range }}
                                        @if($tournament->scheduled_time)
                                        <br><i class="fas fa-clock me-1"></i>
                                        {{ $tournament->scheduled_time->format('H:i') }}
                                        @endif
                                    </p>

                                    <h6 class="text-muted">Thời lượng</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-hourglass-half me-1"></i>
                                        {{ $tournament->duration_in_days }} ngày
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Loại địa điểm</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $tournament->location_type_label }}
                                    </p>

                                    @if($tournament->location_address)
                                    <h6 class="text-muted">Địa chỉ</h6>
                                    <p class="mb-3">
                                        <i class="fas fa-location-dot me-1"></i>
                                        {{ $tournament->location_address }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Luật thi đấu -->
                    @if($tournament->rules_details)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-gavel me-2"></i>
                                Luật thi đấu
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($tournament->rules_details['map_pool']) && $tournament->rules_details['map_pool'])
                            <h6 class="text-muted">Map Pool</h6>
                            <p class="mb-3">{{ $tournament->rules_details['map_pool'] }}</p>
                            @endif

                            @if(isset($tournament->rules_details['bo_format']) && $tournament->rules_details['bo_format'])
                            <h6 class="text-muted">Best of Format</h6>
                            <p class="mb-3">{{ $tournament->rules_details['bo_format'] }}</p>
                            @endif

                            @if(isset($tournament->rules_details['other']) && $tournament->rules_details['other'])
                            <h6 class="text-muted">Luật khác</h6>
                            <p class="mb-0">{{ $tournament->rules_details['other'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Stream & Links -->
                    @if($tournament->stream_link || $tournament->hashtags)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-link me-2"></i>
                                Links & Tags
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($tournament->stream_link)
                            <h6 class="text-muted">Link phát sóng</h6>
                            <p class="mb-3">
                                <a href="{{ $tournament->stream_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Xem trực tiếp
                                </a>
                            </p>
                            @endif

                            @if($tournament->hashtags)
                            <h6 class="text-muted">Hashtags / Keywords</h6>
                            <p class="mb-0">
                                @foreach($tournament->hashtags as $tag)
                                <span class="badge bg-light text-dark me-1 mb-1">{{ $tag }}</span>
                                @endforeach
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column -->
                <div class="col-lg-4">
                    <!-- Quản lý -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-users-cog me-2"></i>
                                Ban tổ chức
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($tournament->organizer_name)
                            <h6 class="text-muted">Tên ban tổ chức</h6>
                            <p class="mb-3">{{ $tournament->organizer_name }}</p>
                            @endif

                            @if($tournament->organizer_contact)
                            <h6 class="text-muted">Liên hệ</h6>
                            <p class="mb-3">{{ $tournament->organizer_contact }}</p>
                            @endif

                            @if($tournament->referees)
                            <h6 class="text-muted">Trọng tài / Admin</h6>
                            <ul class="list-unstyled mb-0">
                                @foreach($tournament->referees as $referee)
                                <li class="mb-1">
                                    <i class="fas fa-user-check me-1"></i>{{ $referee }}
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Giải thưởng -->
                    @if($tournament->prize_structure)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-trophy me-2"></i>
                                Giải thưởng
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($tournament->prize_structure as $prize)
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 bg-light rounded">
                                <span class="fw-bold">{{ $prize['position'] }}</span>
                                <span class="text-success">{{ $prize['reward'] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Nhà tài trợ -->
                    @if($tournament->sponsors)
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-handshake me-2"></i>
                                Nhà tài trợ
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($tournament->sponsors as $sponsor)
                            <div class="mb-2">
                                <i class="fas fa-building me-1"></i>{{ $sponsor }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Thông tin quản lý -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="mb-0 text-muted">
                                <i class="fas fa-info me-2"></i>
                                Thông tin quản lý
                            </h6>
                        </div>
                        <div class="card-body small">
                            <div class="row">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <span class="text-muted">Tạo bởi:</span><br>
                                        <strong>{{ $tournament->creator->name ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-muted">Ngày tạo:</span><br>
                                        {{ $tournament->created_at->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                                <div class="col-6">
                                    @if($tournament->updated_at != $tournament->created_at)
                                    <div class="mb-2">
                                        <span class="text-muted">Sửa bởi:</span><br>
                                        <strong>{{ $tournament->updater->name ?? 'N/A' }}</strong>
                                    </div>
                                    <div class="mb-2">
                                        <span class="text-muted">Lần cuối:</span><br>
                                        {{ $tournament->updated_at->format('d/m/Y H:i') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa tournament <strong id="deleteTournamentName"></strong>?</p>
                <p class="text-warning small">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Hành động này không thể hoàn tác!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Xóa Tournament</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.getElementById('delete-tournament-btn');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        deleteBtn.addEventListener('click', function() {
            const tournamentId = this.getAttribute('data-tournament-id');
            const tournamentName = this.getAttribute('data-tournament-name');

            document.getElementById('deleteTournamentName').textContent = tournamentName;
            deleteModal.show();

            // Store tournament ID for deletion
            document.getElementById('confirmDelete').setAttribute('data-tournament-id', tournamentId);
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            const tournamentId = this.getAttribute('data-tournament-id');

            if (!tournamentId) return;

            fetch(`/admin/tournaments/${tournamentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route("admin.tournaments.index") }}';
                    } else {
                        alert('Có lỗi xảy ra khi xóa tournament!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra khi xóa tournament!');
                });
        });
    });
</script>

<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
    }
</style>
@endsection