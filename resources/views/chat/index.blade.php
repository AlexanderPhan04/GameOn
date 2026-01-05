@extends('layouts.app')

@section('title', 'Global Chat')

@push('styles')
<style>
    body:has(.chat-page) { padding-top: 64px !important; background: #000814 !important; }
    body:has(.chat-page) main { padding: 0 !important; background: #000814 !important; }
    body:has(.chat-page) footer { display: none !important; }
    
    .chat-page { display: flex; min-height: calc(100vh - 64px); background: #000814; }
    
    .chat-sidebar {
        width: 340px; min-width: 340px;
        background: linear-gradient(180deg, #0d1b2a 0%, #000022 100%);
        border-right: 1px solid rgba(0, 229, 255, 0.15);
        display: flex; flex-direction: column;
    }
    .sidebar-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.4), rgba(0, 0, 34, 0.4));
        border-bottom: 1px solid rgba(0, 229, 255, 0.2);
        position: relative;
    }
    .sidebar-header::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
        background: linear-gradient(90deg, #00E5FF, #8b5cf6, #00E5FF);
    }
    .sidebar-title {
        font-family: 'Rajdhani', sans-serif; font-size: 1.5rem; font-weight: 700;
        color: #00E5FF; display: flex; align-items: center; gap: 0.75rem;
    }
    .sidebar-subtitle { color: #94a3b8; font-size: 0.85rem; margin-top: 0.25rem; }
    
    .action-section { padding: 1.25rem; border-bottom: 1px solid rgba(0, 229, 255, 0.1); }
    .create-btn {
        width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.75rem;
        padding: 0.875rem 1.25rem; background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none; border-radius: 12px; color: white; font-weight: 600; cursor: pointer;
        transition: all 0.3s ease; box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3);
    }
    .create-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 30px rgba(139, 92, 246, 0.5); }
    
    .search-box { margin-top: 1rem; position: relative; }
    .search-input-wrap {
        display: flex; align-items: center; background: rgba(0, 0, 20, 0.6);
        border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; padding: 0.5rem 0.75rem;
        transition: all 0.3s ease;
    }
    .search-input-wrap:focus-within { border-color: #00E5FF; box-shadow: 0 0 20px rgba(0, 229, 255, 0.15); }
    .search-input-wrap i { color: #64748b; margin-right: 0.75rem; }
    .search-input { flex: 1; background: transparent; border: none; outline: none; color: #fff; font-size: 0.9rem; }
    .search-input::placeholder { color: #64748b; }
    .search-submit {
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #000055, #000077); border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 10px; color: #00E5FF; cursor: pointer; transition: all 0.3s ease;
    }
    .search-submit:hover { background: rgba(0, 229, 255, 0.15); box-shadow: 0 0 15px rgba(0, 229, 255, 0.3); }
    .search-results {
        position: absolute; top: 100%; left: 0; right: 0; margin-top: 0.5rem;
        background: #0d1b2a; border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px;
        max-height: 300px; overflow-y: auto; z-index: 100; display: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
    .search-results.show { display: block; }
    .search-result-item {
        display: flex; align-items: center; gap: 0.75rem; padding: 0.875rem 1rem;
        cursor: pointer; transition: all 0.2s ease; border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .search-result-item:last-child { border-bottom: none; }
    .search-result-item:hover { background: rgba(0, 229, 255, 0.1); }
    .search-result-item img { width: 40px; height: 40px; border-radius: 50%; border: 2px solid rgba(0, 229, 255, 0.3); }
    .search-result-item .name { color: #fff; font-weight: 500; }
    .search-result-item .email { color: #64748b; font-size: 0.8rem; }
    
    .conversations-section { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
    .conversations-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.875rem 1.25rem; background: rgba(0, 0, 20, 0.4);
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .conversations-title { font-family: 'Rajdhani', sans-serif; color: #94a3b8; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; }
    .conversations-count { background: linear-gradient(135deg, #00E5FF, #0099cc); color: #000814; font-size: 0.75rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 20px; }
    .conversations-list { flex: 1; overflow-y: auto; padding: 0.75rem; }
    .conversations-list::-webkit-scrollbar { width: 4px; }
    .conversations-list::-webkit-scrollbar-thumb { background: #00E5FF; border-radius: 4px; }
    
    .conversation-item {
        display: flex; align-items: center; gap: 0.875rem; padding: 0.875rem;
        margin-bottom: 0.5rem; background: rgba(0, 0, 20, 0.5);
        border: 1px solid rgba(0, 229, 255, 0.1); border-radius: 14px;
        cursor: pointer; transition: all 0.3s ease; text-decoration: none;
    }
    .conversation-item:hover { background: rgba(0, 229, 255, 0.08); border-color: rgba(0, 229, 255, 0.3); transform: translateX(4px); }
    .conversation-item.active { background: rgba(0, 229, 255, 0.12); border-color: #00E5FF; }
    .conversation-avatar { width: 48px; height: 48px; border-radius: 14px; border: 2px solid rgba(0, 229, 255, 0.3); object-fit: cover; }
    .conversation-info { flex: 1; min-width: 0; }
    .conversation-name { color: #fff; font-weight: 600; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .conversation-preview { color: #94a3b8; font-size: 0.8rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .conversation-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 0.5rem; }
    .conversation-time { color: #64748b; font-size: 0.7rem; }
    .unread-badge { background: linear-gradient(135deg, #00E5FF, #0099cc); color: #000814; font-size: 0.7rem; font-weight: 700; padding: 0.15rem 0.5rem; border-radius: 10px; }
    
    .empty-state { text-align: center; padding: 2.5rem 1.5rem; }
    .empty-icon { width: 70px; height: 70px; margin: 0 auto 1.25rem; background: linear-gradient(135deg, rgba(0, 229, 255, 0.1), rgba(139, 92, 246, 0.1)); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 20px; display: flex; align-items: center; justify-content: center; }
    .empty-icon i { font-size: 1.75rem; color: #00E5FF; }
    .empty-title { font-family: 'Rajdhani', sans-serif; color: #fff; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; }
    .empty-desc { color: #64748b; font-size: 0.85rem; margin-bottom: 1.25rem; }
    .empty-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.25rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; border-radius: 10px; color: #fff; font-size: 0.85rem; font-weight: 600; cursor: pointer; }
</style>
<style>
    .chat-main { flex: 1; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #000814, #000022, #0d1b2a); position: relative; overflow: hidden; width: 100%; }
    
    .modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px); z-index: 9998; display: none; }
    .modal-overlay.show { display: block; }
    .modal-container { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; z-index: 9999; padding: 1rem; }
    .modal-container.show { display: flex; }
    .modal-box { width: 100%; max-width: 550px; background: linear-gradient(135deg, #0d1b2a, #000022); border: 1px solid rgba(0, 229, 255, 0.25); border-radius: 20px; overflow: hidden; box-shadow: 0 25px 80px rgba(0, 0, 0, 0.8), 0 0 60px rgba(0, 229, 255, 0.1); animation: modalIn 0.3s ease; }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.15)); border-bottom: 1px solid rgba(0, 229, 255, 0.15); }
    .modal-header-left { display: flex; align-items: center; gap: 1rem; }
    .modal-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.25rem; }
    .modal-title { font-family: 'Rajdhani', sans-serif; color: #fff; font-size: 1.25rem; font-weight: 700; }
    .modal-subtitle { color: #94a3b8; font-size: 0.85rem; }
    .modal-close { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.1); border: none; border-radius: 10px; color: #94a3b8; cursor: pointer; }
    .modal-close:hover { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .modal-body { padding: 1.5rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; }
    .form-label i { color: #00E5FF; margin-right: 0.4rem; }
    .form-input { width: 100%; box-sizing: border-box; background: rgba(0, 0, 20, 0.6); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; padding: 0.875rem 1rem; color: #fff; font-size: 0.9rem; }
    .form-input:focus { outline: none; border-color: #00E5FF; box-shadow: 0 0 20px rgba(0, 229, 255, 0.15); }
    .form-input::placeholder { color: #64748b; }
    .modal-footer { display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; background: rgba(0, 0, 20, 0.4); border-top: 1px solid rgba(0, 229, 255, 0.1); }
    .btn-cancel { padding: 0.75rem 1.25rem; background: rgba(255, 255, 255, 0.1); border: none; border-radius: 10px; color: #94a3b8; font-weight: 500; cursor: pointer; }
    .btn-submit { padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; border-radius: 10px; color: #fff; font-weight: 600; cursor: pointer; }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="chat-page">
    <aside class="chat-sidebar">
        <div class="sidebar-header">
            <h1 class="sidebar-title"><i class="fas fa-comments"></i> Global Chat</h1>
            <p class="sidebar-subtitle">Kết nối mọi lúc, mọi nơi</p>
        </div>
        <div class="action-section">
            <button class="create-btn" id="openModalBtn"><i class="fas fa-users"></i> Tạo nhóm chat <i class="fas fa-plus" style="margin-left:auto;"></i></button>
            <div class="search-box">
                <div class="search-input-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" class="search-input" id="userSearch" placeholder="Tìm kiếm người dùng...">
                    <button class="search-submit" id="searchBtn"><i class="fas fa-arrow-right"></i></button>
                </div>
                <div class="search-results" id="searchResults"></div>
            </div>
        </div>
        <div class="conversations-section">
            <div class="conversations-header">
                <span class="conversations-title">Cuộc trò chuyện</span>
                <span class="conversations-count">{{ $conversations->count() }}</span>
            </div>
            <div class="conversations-list">
                @forelse($conversations as $conversation)
                <a href="{{ route('chat.show', $conversation) }}" class="conversation-item {{ request()->route('conversation')?->id == $conversation->id ? 'active' : '' }}">
                    <img src="{{ $conversation->getDisplayAvatar(auth()->id()) }}" class="conversation-avatar">
                    <div class="conversation-info">
                        <div class="conversation-name">{{ $conversation->getDisplayName(auth()->id()) }}</div>
                        <div class="conversation-preview">{{ $conversation->last_message_preview ?? 'Chưa có tin nhắn' }}</div>
                    </div>
                    <div class="conversation-meta">
                        <span class="conversation-time">{{ $conversation->last_message_at?->diffForHumans() ?? '' }}</span>
                        @if($conversation->getUnreadCount(auth()->id()) > 0)
                        <span class="unread-badge">{{ $conversation->getUnreadCount(auth()->id()) }}</span>
                        @endif
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-comments"></i></div>
                    <h3 class="empty-title">Chưa có cuộc trò chuyện</h3>
                    <p class="empty-desc">Tìm kiếm người dùng để bắt đầu!</p>
                    <button class="empty-btn" id="openModalBtn2"><i class="fas fa-plus"></i> Tạo nhóm</button>
                </div>
                @endforelse
            </div>
        </div>
    </aside>
    <main class="chat-main">
        <!-- Empty state - select a conversation -->
    </main>
</div>

<div class="modal-overlay" id="modalOverlay"></div>
<div class="modal-container" id="modalContainer">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-header-left">
                <div class="modal-icon"><i class="fas fa-users"></i></div>
                <div><h3 class="modal-title">Tạo nhóm chat mới</h3><p class="modal-subtitle">Kết nối với bạn bè</p></div>
            </div>
            <button class="modal-close" id="closeModalBtn"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label"><i class="fas fa-tag"></i> Tên nhóm <span style="color:#ef4444;">*</span></label>
                <input type="text" class="form-input" id="groupName" placeholder="VD: Team Gaming...">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-align-left"></i> Mô tả</label>
                <textarea class="form-input" id="groupDesc" rows="2" placeholder="Mô tả nhóm..." style="resize:none;"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="fas fa-user-plus"></i> Thêm thành viên</label>
                <input type="text" class="form-input" id="memberSearch" placeholder="Tìm người dùng...">
                <div class="search-results" id="memberResults" style="position:relative;margin-top:0.5rem;"></div>
                <div id="selectedMembers" style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-top:0.75rem;"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" id="cancelModalBtn">Hủy</button>
            <button class="btn-submit" id="submitGroupBtn" disabled><i class="fas fa-plus"></i> Tạo nhóm</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('modalOverlay');
    const container = document.getElementById('modalContainer');
    const openBtns = document.querySelectorAll('#openModalBtn, #openModalBtn2');
    const closeBtns = document.querySelectorAll('#closeModalBtn, #cancelModalBtn');
    const groupName = document.getElementById('groupName');
    const submitBtn = document.getElementById('submitGroupBtn');
    
    openBtns.forEach(btn => btn.addEventListener('click', () => { overlay.classList.add('show'); container.classList.add('show'); }));
    closeBtns.forEach(btn => btn.addEventListener('click', () => { overlay.classList.remove('show'); container.classList.remove('show'); }));
    overlay.addEventListener('click', () => { overlay.classList.remove('show'); container.classList.remove('show'); });
    
    groupName.addEventListener('input', () => { submitBtn.disabled = groupName.value.trim().length < 2; });
    
    let selectedUsers = [];
    const memberSearch = document.getElementById('memberSearch');
    const memberResults = document.getElementById('memberResults');
    const selectedMembers = document.getElementById('selectedMembers');
    
    let searchTimeout;
    memberSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { memberResults.classList.remove('show'); return; }
        searchTimeout = setTimeout(() => {
            fetch(`{{ route("chat.search-users") }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.users?.length) {
                        const filtered = data.users.filter(u => !selectedUsers.find(s => s.id === u.id));
                        memberResults.innerHTML = filtered.map(u => `<div class="search-result-item" data-id="${u.id}" data-name="${u.name}" data-avatar="${u.avatar}"><img src="${u.avatar}"><div><div class="name">${u.name}</div><div class="email">${u.email}</div></div></div>`).join('') || '<div style="padding:1rem;color:#64748b;text-align:center;">Không tìm thấy</div>';
                        memberResults.classList.add('show');
                    } else { memberResults.innerHTML = '<div style="padding:1rem;color:#64748b;text-align:center;">Không tìm thấy</div>'; memberResults.classList.add('show'); }
                });
        }, 300);
    });
    
    memberResults.addEventListener('click', function(e) {
        const item = e.target.closest('.search-result-item');
        if (!item) return;
        selectedUsers.push({ id: item.dataset.id, name: item.dataset.name, avatar: item.dataset.avatar });
        renderMembers();
        memberSearch.value = '';
        memberResults.classList.remove('show');
    });
    
    function renderMembers() {
        selectedMembers.innerHTML = selectedUsers.map(u => `<span style="display:inline-flex;align-items:center;gap:0.4rem;background:rgba(0,229,255,0.1);border:1px solid rgba(0,229,255,0.2);border-radius:8px;padding:0.3rem 0.6rem;"><img src="${u.avatar}" style="width:20px;height:20px;border-radius:50%;"><span style="color:#fff;font-size:0.85rem;">${u.name}</span><button type="button" onclick="removeUser(${u.id})" style="background:none;border:none;color:#64748b;cursor:pointer;">&times;</button></span>`).join('');
    }
    window.removeUser = function(id) { selectedUsers = selectedUsers.filter(u => u.id != id); renderMembers(); };
    
    submitBtn.addEventListener('click', function() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('name', groupName.value.trim());
        formData.append('description', document.getElementById('groupDesc').value.trim());
        selectedUsers.forEach(u => formData.append('user_ids[]', u.id));
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
        fetch('{{ route("chat.create-group") }}', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => { if (data.success) window.location.href = data.redirect_url; else { alert(data.message || 'Lỗi'); submitBtn.disabled = false; submitBtn.innerHTML = '<i class="fas fa-plus"></i> Tạo nhóm'; } });
    });
    
    const userSearch = document.getElementById('userSearch');
    const searchResults = document.getElementById('searchResults');
    let userTimeout;
    userSearch.addEventListener('input', function() {
        clearTimeout(userTimeout);
        const q = this.value.trim();
        if (q.length < 2) { searchResults.classList.remove('show'); return; }
        userTimeout = setTimeout(() => {
            fetch(`{{ route("chat.search-users") }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.users?.length) { searchResults.innerHTML = data.users.map(u => `<div class="search-result-item" onclick="startChat(${u.id})"><img src="${u.avatar}"><div><div class="name">${u.name}</div><div class="email">${u.email}</div></div></div>`).join(''); }
                    else { searchResults.innerHTML = '<div style="padding:1rem;color:#64748b;text-align:center;">Không tìm thấy</div>'; }
                    searchResults.classList.add('show');
                });
        }, 300);
    });
    document.getElementById('searchBtn').addEventListener('click', () => { if (userSearch.value.trim().length >= 2) userSearch.dispatchEvent(new Event('input')); });
    
    window.startChat = function(userId) {
        searchResults.innerHTML = '<div style="padding:1rem;color:#94a3b8;text-align:center;"><i class="fas fa-spinner fa-spin"></i></div>';
        fetch('{{ route("chat.start") }}', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: JSON.stringify({ user_id: userId }) })
            .then(r => r.json())
            .then(data => { if (data.success) window.location.href = data.redirect_url; else searchResults.classList.remove('show'); });
    };
    
    document.addEventListener('click', e => {
        if (!e.target.closest('.search-box')) searchResults.classList.remove('show');
        if (!e.target.closest('#memberSearch') && !e.target.closest('#memberResults')) memberResults.classList.remove('show');
    });
});
</script>
@endpush
