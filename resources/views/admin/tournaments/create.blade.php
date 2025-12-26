@extends('layouts.app')

@section('title', 'Tạo Tournament mới')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="mb-0">
                            <i class="fas fa-plus me-2"></i>
                            Tạo Tournament mới
                        </h4>
                        <a href="{{ route('admin.tournaments.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Quay lại
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle me-1"></i>Vui lòng kiểm tra lại:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.tournaments.store') }}" method="POST" enctype="multipart/form-data" id="tournamentForm">
                        @csrf

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-4" id="tournamentTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button">
                                    <i class="fas fa-info-circle me-1"></i>Thông tin cơ bản
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="datetime-tab" data-bs-toggle="tab" data-bs-target="#datetime" type="button">
                                    <i class="fas fa-calendar me-1"></i>Thời gian & Địa điểm
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="structure-tab" data-bs-toggle="tab" data-bs-target="#structure" type="button">
                                    <i class="fas fa-sitemap me-1"></i>Cấu trúc & Luật
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="management-tab" data-bs-toggle="tab" data-bs-target="#management" type="button">
                                    <i class="fas fa-users me-1"></i>Quản lý & Phần thưởng
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="display-tab" data-bs-toggle="tab" data-bs-target="#display" type="button">
                                    <i class="fas fa-eye me-1"></i>Hệ thống & Hiển thị
                                </button>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="tournamentTabsContent">

                            <!-- Tab 1: Thông tin cơ bản -->
                            <div class="tab-pane fade show active" id="basic" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Tên Tournament -->
                                        <div class="mb-3">
                                            <label for="name" class="form-label">
                                                Tên Tournament <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="name" name="name"
                                                value="{{ old('name') }}" required placeholder="Ví dụ: VCT Masters Shanghai 2024">
                                        </div>

                                        <!-- Game -->
                                        <div class="mb-3">
                                            <label for="game_id" class="form-label">
                                                Game <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="game_id" name="game_id" required>
                                                <option value="">-- Chọn Game --</option>
                                                @foreach($games as $game)
                                                <option value="{{ $game->id }}" {{ old('game_id') == $game->id ? 'selected' : '' }}>
                                                    {{ $game->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Thể loại -->
                                        <div class="mb-3">
                                            <label for="competition_type" class="form-label">
                                                Thể loại thi đấu <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="competition_type" name="competition_type" required>
                                                <option value="team" {{ old('competition_type', 'team') === 'team' ? 'selected' : '' }}>Đội tuyển</option>
                                                <option value="individual" {{ old('competition_type') === 'individual' ? 'selected' : '' }}>Cá nhân</option>
                                            </select>
                                        </div>

                                        <!-- Format -->
                                        <div class="mb-3">
                                            <label for="format" class="form-label">Format đội hình</label>
                                            <input type="text" class="form-control" id="format" name="format"
                                                value="{{ old('format') }}" placeholder="Ví dụ: 5v5, 1v1, 3v3">
                                            <div class="form-text">Quy mô đội thi đấu (tùy chọn)</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Logo -->
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Logo Tournament</label>
                                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                            <div class="form-text">Khuyến nghị: 256x256px, định dạng PNG/JPG, dưới 2MB</div>
                                        </div>

                                        <!-- Banner -->
                                        <div class="mb-3">
                                            <label for="banner" class="form-label">Banner Tournament</label>
                                            <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                                            <div class="form-text">Khuyến nghị: 1920x400px, định dạng PNG/JPG, dưới 5MB</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mô tả -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Mô tả Tournament</label>
                                    <textarea class="form-control" id="description" name="description" rows="4"
                                        placeholder="Giới thiệu về tournament, thể thức thi đấu, giải thưởng...">{{ old('description') }}</textarea>
                                    <div class="form-text">Tối đa 2000 ký tự</div>
                                </div>
                            </div>

                            <!-- Tab 2: Thời gian & Địa điểm -->
                            <div class="tab-pane fade" id="datetime" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-4">
                                        <!-- Ngày bắt đầu -->
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">
                                                Ngày bắt đầu <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ old('start_date') }}" required min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Ngày kết thúc -->
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">
                                                Ngày kết thúc <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="{{ old('end_date') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- Giờ thi đấu -->
                                        <div class="mb-3">
                                            <label for="scheduled_time" class="form-label">Giờ thi đấu dự kiến</label>
                                            <input type="time" class="form-control" id="scheduled_time" name="scheduled_time"
                                                value="{{ old('scheduled_time') }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Loại địa điểm -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        Loại địa điểm <span class="text-danger">*</span>
                                    </label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="location_type"
                                                    id="location_online" value="online" {{ old('location_type', 'online') === 'online' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="location_online">
                                                    <i class="fas fa-wifi me-1"></i>Online
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="location_type"
                                                    id="location_lan" value="lan" {{ old('location_type') === 'lan' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="location_lan">
                                                    <i class="fas fa-network-wired me-1"></i>LAN
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="location_type"
                                                    id="location_physical" value="physical" {{ old('location_type') === 'physical' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="location_physical">
                                                    <i class="fas fa-map-marker-alt me-1"></i>Địa điểm cụ thể
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Địa chỉ cụ thể -->
                                <div class="mb-3" id="location_address_group" style="display: none;">
                                    <label for="location_address" class="form-label">Địa chỉ cụ thể</label>
                                    <textarea class="form-control" id="location_address" name="location_address" rows="2"
                                        placeholder="Nhập địa chỉ venue, tòa nhà, thành phố...">{{ old('location_address') }}</textarea>
                                </div>
                            </div>

                            <!-- Tab 3: Cấu trúc & Luật -->
                            <div class="tab-pane fade" id="structure" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Hình thức thi đấu -->
                                        <div class="mb-3">
                                            <label for="tournament_format" class="form-label">
                                                Hình thức thi đấu <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="tournament_format" name="tournament_format" required>
                                                <option value="single_elimination" {{ old('tournament_format', 'single_elimination') === 'single_elimination' ? 'selected' : '' }}>Single Elimination</option>
                                                <option value="double_elimination" {{ old('tournament_format') === 'double_elimination' ? 'selected' : '' }}>Double Elimination</option>
                                                <option value="round_robin" {{ old('tournament_format') === 'round_robin' ? 'selected' : '' }}>Round Robin</option>
                                                <option value="swiss_system" {{ old('tournament_format') === 'swiss_system' ? 'selected' : '' }}>Swiss System</option>
                                            </select>
                                        </div>

                                        <!-- Số lượng tham gia tối đa -->
                                        <div class="mb-3">
                                            <label for="max_participants" class="form-label">
                                                Số lượng tham gia tối đa <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" class="form-control" id="max_participants" name="max_participants"
                                                value="{{ old('max_participants', 16) }}" required min="2" max="1024">
                                            <div class="form-text">Số đội hoặc người chơi tối đa có thể tham gia</div>
                                        </div>

                                        <!-- Số người dự bị -->
                                        <div class="mb-3">
                                            <label for="substitute_players" class="form-label">Số người dự bị mỗi đội</label>
                                            <input type="number" class="form-control" id="substitute_players" name="substitute_players"
                                                value="{{ old('substitute_players', 0) }}" min="0" max="10">
                                            <div class="form-text">Chỉ áp dụng cho thi đấu đội tuyển</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Luật thi đấu chi tiết -->
                                        <div class="mb-3">
                                            <label class="form-label">Luật thi đấu chi tiết</label>
                                            <div class="mb-2">
                                                <label for="rules_map_pool" class="form-label small">Map Pool</label>
                                                <input type="text" class="form-control form-control-sm" id="rules_map_pool"
                                                    placeholder="Ví dụ: Dust2, Mirage, Inferno">
                                            </div>
                                            <div class="mb-2">
                                                <label for="rules_bo_format" class="form-label small">Best of Format</label>
                                                <select class="form-select form-select-sm" id="rules_bo_format">
                                                    <option value="">-- Chọn --</option>
                                                    <option value="BO1">Best of 1 (BO1)</option>
                                                    <option value="BO3">Best of 3 (BO3)</option>
                                                    <option value="BO5">Best of 5 (BO5)</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label for="rules_other" class="form-label small">Luật khác</label>
                                                <textarea class="form-control form-control-sm" id="rules_other" rows="2"
                                                    placeholder="Các quy định khác..."></textarea>
                                            </div>
                                            <input type="hidden" id="rules_details" name="rules_details">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 4: Quản lý & Phần thưởng -->
                            <div class="tab-pane fade" id="management" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Ban tổ chức -->
                                        <div class="mb-3">
                                            <label for="organizer_name" class="form-label">Tên ban tổ chức</label>
                                            <input type="text" class="form-control" id="organizer_name" name="organizer_name"
                                                value="{{ old('organizer_name') }}" placeholder="Ví dụ: Riot Games Vietnam">
                                        </div>

                                        <!-- Contact info -->
                                        <div class="mb-3">
                                            <label for="organizer_contact" class="form-label">Thông tin liên hệ</label>
                                            <input type="text" class="form-control" id="organizer_contact" name="organizer_contact"
                                                value="{{ old('organizer_contact') }}" placeholder="Email, phone, discord...">
                                        </div>

                                        <!-- Trọng tài -->
                                        <div class="mb-3">
                                            <label class="form-label">Trọng tài / Admin</label>
                                            <div id="referees-container">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control referees-input" placeholder="Tên trọng tài">
                                                    <button type="button" class="btn btn-success" onclick="addReferee()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="referees" name="referees">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Cơ cấu giải thưởng -->
                                        <div class="mb-3">
                                            <label class="form-label">Cơ cấu giải thưởng</label>
                                            <div id="prizes-container">
                                                <div class="row mb-2">
                                                    <div class="col-4">
                                                        <input type="text" class="form-control prize-position" placeholder="Hạng">
                                                    </div>
                                                    <div class="col-6">
                                                        <input type="text" class="form-control prize-reward" placeholder="Giải thưởng">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-success w-100" onclick="addPrize()">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="hidden" id="prize_structure" name="prize_structure">
                                        </div>

                                        <!-- Nhà tài trợ -->
                                        <div class="mb-3">
                                            <label class="form-label">Nhà tài trợ</label>
                                            <div id="sponsors-container">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control sponsors-input" placeholder="Tên nhà tài trợ">
                                                    <button type="button" class="btn btn-success" onclick="addSponsor()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="sponsors" name="sponsors">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab 5: Hệ thống & Hiển thị -->
                            <div class="tab-pane fade" id="display" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- Trạng thái -->
                                        <div class="mb-3">
                                            <label for="status" class="form-label">
                                                Trạng thái <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Chưa mở đăng ký</option>
                                                <option value="registration_open" {{ old('status') === 'registration_open' ? 'selected' : '' }}>Đang đăng ký</option>
                                                <option value="ongoing" {{ old('status') === 'ongoing' ? 'selected' : '' }}>Đang diễn ra</option>
                                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Đã kết thúc</option>
                                            </select>
                                        </div>

                                        <!-- Hình thức tham gia -->
                                        <div class="mb-3">
                                            <label for="participation_type" class="form-label">
                                                Hình thức tham gia <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="participation_type" name="participation_type" required>
                                                <option value="public" {{ old('participation_type', 'public') === 'public' ? 'selected' : '' }}>Mở công khai</option>
                                                <option value="invite_only" {{ old('participation_type') === 'invite_only' ? 'selected' : '' }}>Chỉ mời</option>
                                            </select>
                                        </div>

                                        <!-- Link phát sóng -->
                                        <div class="mb-3">
                                            <label for="stream_link" class="form-label">Link phát sóng</label>
                                            <input type="url" class="form-control" id="stream_link" name="stream_link"
                                                value="{{ old('stream_link') }}" placeholder="https://twitch.tv/...">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <!-- Hashtags -->
                                        <div class="mb-3">
                                            <label class="form-label">Hashtags / Keywords</label>
                                            <div id="hashtags-container">
                                                <div class="input-group mb-2">
                                                    <input type="text" class="form-control hashtags-input" placeholder="#hashtag hoặc keyword">
                                                    <button type="button" class="btn btn-success" onclick="addHashtag()">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="hidden" id="hashtags" name="hashtags">
                                            <div class="form-text">Giúp tìm kiếm và marketing tournament</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.tournaments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Tạo Tournament
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle location type change
        const locationRadios = document.querySelectorAll('input[name="location_type"]');
        const locationAddressGroup = document.getElementById('location_address_group');

        locationRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'physical' || this.value === 'lan') {
                    locationAddressGroup.style.display = 'block';
                } else {
                    locationAddressGroup.style.display = 'none';
                }
            });
        });

        // Handle end date minimum
        document.getElementById('start_date').addEventListener('change', function() {
            document.getElementById('end_date').min = this.value;
        });

        // Form submission - collect dynamic arrays
        document.getElementById('tournamentForm').addEventListener('submit', function() {
            // Collect rules details
            const rulesDetails = {
                map_pool: document.getElementById('rules_map_pool').value,
                bo_format: document.getElementById('rules_bo_format').value,
                other: document.getElementById('rules_other').value
            };
            document.getElementById('rules_details').value = JSON.stringify(rulesDetails);

            // Collect referees
            const referees = [];
            document.querySelectorAll('#referees-container .referees-input').forEach(input => {
                if (input.value.trim()) referees.push(input.value.trim());
            });
            document.getElementById('referees').value = JSON.stringify(referees);

            // Collect prizes
            const prizes = [];
            document.querySelectorAll('#prizes-container .row').forEach(row => {
                const position = row.querySelector('.prize-position')?.value?.trim();
                const reward = row.querySelector('.prize-reward')?.value?.trim();
                if (position && reward) {
                    prizes.push({
                        position,
                        reward
                    });
                }
            });
            document.getElementById('prize_structure').value = JSON.stringify(prizes);

            // Collect sponsors
            const sponsors = [];
            document.querySelectorAll('#sponsors-container .sponsors-input').forEach(input => {
                if (input.value.trim()) sponsors.push(input.value.trim());
            });
            document.getElementById('sponsors').value = JSON.stringify(sponsors);

            // Collect hashtags
            const hashtags = [];
            document.querySelectorAll('#hashtags-container .hashtags-input').forEach(input => {
                if (input.value.trim()) hashtags.push(input.value.trim());
            });
            document.getElementById('hashtags').value = JSON.stringify(hashtags);
        });
    });

    // Dynamic list functions
    function addReferee() {
        const container = document.getElementById('referees-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
        <input type="text" class="form-control referees-input" placeholder="Tên trọng tài">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(div);
    }

    function addPrize() {
        const container = document.getElementById('prizes-container');
        const div = document.createElement('div');
        div.className = 'row mb-2';
        div.innerHTML = `
        <div class="col-4">
            <input type="text" class="form-control prize-position" placeholder="Hạng">
        </div>
        <div class="col-6">
            <input type="text" class="form-control prize-reward" placeholder="Giải thưởng">
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger w-100" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    `;
        container.appendChild(div);
    }

    function addSponsor() {
        const container = document.getElementById('sponsors-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
        <input type="text" class="form-control sponsors-input" placeholder="Tên nhà tài trợ">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(div);
    }

    function addHashtag() {
        const container = document.getElementById('hashtags-container');
        const div = document.createElement('div');
        div.className = 'input-group mb-2';
        div.innerHTML = `
        <input type="text" class="form-control hashtags-input" placeholder="#hashtag hoặc keyword">
        <button type="button" class="btn btn-danger" onclick="this.parentElement.remove()">
            <i class="fas fa-trash"></i>
        </button>
    `;
        container.appendChild(div);
    }
</script>
@endsection