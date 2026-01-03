@extends('layouts.app')

@section('title', 'Chat - ' . $conversation->getDisplayName(auth()->id()))

@push('styles')
<style>
    body:has(.chat-page) { padding-top: 64px !important; background: #000814 !important; overflow: hidden !important; }
    body:has(.chat-page) main { padding: 0 !important; background: #000814 !important; margin-left: 60px !important; width: calc(100% - 60px) !important; max-width: none !important; }
    body:has(.chat-page) footer { display: none !important; }
    body.has-admin-sidebar:has(.chat-page) main { margin-left: 60px !important; width: calc(100% - 60px) !important; }
    
    .chat-page { display: flex; height: calc(100vh - 64px); max-height: calc(100vh - 64px); background: #000814; width: 100%; padding: 1rem; gap: 1rem; box-sizing: border-box; overflow: hidden; }
    
    /* Sidebar */
    .chat-sidebar {
        width: 300px; min-width: 300px;
        background: linear-gradient(180deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        display: flex; flex-direction: column;
        overflow: hidden;
    }
    
    .sidebar-header {
        padding: 1.25rem;
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.4), rgba(0, 0, 34, 0.4));
        border-bottom: 1px solid rgba(0, 229, 255, 0.2);
    }
    
    .sidebar-header a {
        display: flex; align-items: center; gap: 0.5rem;
        color: #00E5FF; text-decoration: none;
        font-family: 'Rajdhani', sans-serif; font-weight: 600; font-size: 1.1rem;
    }
    
    .sidebar-header a:hover { color: #fff; }
    
    .user-card {
        padding: 1rem; margin: 1rem;
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.4) 0%, rgba(0, 229, 255, 0.05) 100%);
        border: 1px solid rgba(0, 229, 255, 0.15); border-radius: 12px;
        display: flex; align-items: center; gap: 0.75rem;
    }
    
    .user-card img {
        width: 48px; height: 48px; border-radius: 12px;
        border: 2px solid rgba(0, 229, 255, 0.3); object-fit: cover;
    }
    
    .user-card h6 { color: #fff; font-family: 'Rajdhani', sans-serif; font-weight: 700; margin: 0; }
    
    .sidebar-actions { padding: 0 1rem 1rem; display: flex; flex-direction: column; gap: 0.5rem; }
    
    .btn-action {
        display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        padding: 0.75rem 1rem; border-radius: 10px;
        font-family: 'Rajdhani', sans-serif; font-weight: 600; font-size: 0.9rem;
        cursor: pointer; transition: all 0.3s ease; border: none;
    }
    
    .btn-primary-action {
        background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff;
        box-shadow: 0 4px 15px rgba(139, 92, 246, 0.3);
    }
    
    .btn-danger-action {
        background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); color: #fff;
    }
    
    .conversations-label {
        padding: 1rem; color: #64748b; font-size: 0.75rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.1em; text-align: center;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    /* Chat Main */
    .chat-main-area {
        flex: 1; display: flex; flex-direction: column;
        background: linear-gradient(180deg, #0d1b2a 0%, #000814 100%);
        min-width: 0; width: 100%;
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 16px;
        overflow: hidden;
        max-height: 100%;
    }
    
    .chat-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, rgba(0, 0, 85, 0.3) 0%, rgba(0, 229, 255, 0.05) 100%);
        border-bottom: 1px solid rgba(0, 229, 255, 0.15);
    }
    
    .chat-header-left { display: flex; align-items: center; gap: 0.75rem; }
    
    .chat-header img {
        width: 40px; height: 40px; border-radius: 10px; object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.3);
    }
    
    .chat-header h6 { color: #fff; font-family: 'Rajdhani', sans-serif; font-weight: 700; margin: 0; }
    .chat-header span { color: #64748b; font-size: 0.8rem; }
    
    .btn-menu {
        width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 8px; color: #94a3b8; cursor: pointer;
    }
    
    .btn-menu:hover { background: rgba(0, 229, 255, 0.1); color: #00E5FF; }
    
    /* Messages */
    .messages-area {
        flex: 1; overflow-y: auto; padding: 1.5rem;
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        min-height: 0;
    }
    
    .messages-area::-webkit-scrollbar { width: 6px; }
    .messages-area::-webkit-scrollbar-thumb { background: #667eea; border-radius: 3px; }
    
    .message-item { display: flex; margin-bottom: 1rem; }
    .message-item.own { justify-content: flex-end; }
    .message-item.other { justify-content: flex-start; }
    
    .message-content { display: flex; align-items: flex-end; gap: 0.5rem; max-width: 70%; }
    .message-item.own .message-content { flex-direction: row-reverse; }
    
    .msg-avatar { width: 28px; height: 28px; border-radius: 8px; object-fit: cover; }
    
    .message-bubble { padding: 0.75rem 1rem; border-radius: 16px; }
    
    .message-item.own .message-bubble {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff; border-bottom-right-radius: 4px;
    }
    
    .message-item.other .message-bubble {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(0, 229, 255, 0.15);
        color: #fff; border-bottom-left-radius: 4px;
    }
    
    .msg-sender { font-size: 0.7rem; font-weight: 600; color: #00E5FF; margin-bottom: 0.25rem; }
    .msg-text { font-size: 0.9rem; line-height: 1.4; }
    .msg-time { font-size: 0.65rem; opacity: 0.7; margin-top: 0.25rem; text-align: right; }
    
    /* Input */
    .chat-input-area {
        padding: 1rem 1.5rem;
        background: #0d1b2a;
        border-top: 1px solid rgba(0, 229, 255, 0.15);
        flex-shrink: 0;
    }
    
    .input-wrapper {
        display: flex; align-items: center; gap: 0.5rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 25px; padding: 0.5rem;
    }
    
    .input-wrapper:focus-within { border-color: #00E5FF; }
    
    .btn-input {
        width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
        border: none; border-radius: 50%; cursor: pointer; flex-shrink: 0;
    }
    
    .btn-attach { background: #374151; color: #94a3b8; }
    .btn-emoji { background: #f59e0b; color: #fff; }
    .btn-send { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #fff; }
    
    /* File Preview - compact inline style */
    .file-preview {
        padding: 0.5rem 1rem;
        background: rgba(0, 229, 255, 0.08);
        border-radius: 12px 12px 0 0;
        margin: 0 0.5rem;
    }
    
    .preview-content {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
    }
    
    .preview-img {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        border: 2px solid rgba(0, 229, 255, 0.3);
    }
    
    .preview-file {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #00E5FF;
        font-size: 0.8rem;
        max-width: 200px;
    }
    
    .preview-file i {
        font-size: 1rem;
    }
    
    .preview-file span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .remove-file-btn {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ef4444;
        border: none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        font-size: 0.65rem;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    
    .remove-file-btn:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
    
    .message-input {
        flex: 1; background: transparent; border: none; color: #fff;
        font-size: 0.9rem; padding: 0.5rem; resize: none; max-height: 100px;
    }
        .message-input::placeholder { color: #64748b; }
    .message-input:focus { outline: none; }
    
    /* Dropdown */
    .dropdown-wrapper { position: relative; }
    
    .dropdown-menu-chat {
        position: absolute; top: calc(100% + 5px); right: 0;
        background: #0d1b2a; border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 10px; padding: 0.5rem; min-width: 180px;
        display: none; z-index: 100;
    }
    
    .dropdown-menu-chat.show { display: block; }
    
    .dropdown-item-chat {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.5rem 0.75rem; color: #94a3b8; border-radius: 6px;
        cursor: pointer; font-size: 0.85rem; border: none; background: none; width: 100%;
    }
    
    .dropdown-item-chat:hover { background: rgba(0, 229, 255, 0.1); color: #00E5FF; }
    .dropdown-item-chat.danger { color: #ef4444; }
    .dropdown-item-chat.danger:hover { background: rgba(239, 68, 68, 0.1); }
    
    .dropdown-divider { height: 1px; background: rgba(0, 229, 255, 0.1); margin: 0.25rem 0; }
    
    /* Emoji */
    .emoji-wrapper { position: relative; }
    
    .emoji-picker {
        position: absolute; bottom: calc(100% + 10px); right: 0;
        background: #0d1b2a; border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px; padding: 0.75rem; width: 260px;
        display: none; z-index: 100;
    }
    
    .emoji-picker.show { display: block; }
    
    .emoji-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 0.25rem; }
    
    .emoji-btn {
        padding: 0.4rem; background: transparent; border: none;
        border-radius: 6px; font-size: 1.1rem; cursor: pointer;
    }
    
    .emoji-btn:hover { background: rgba(0, 229, 255, 0.1); }
    
    #file-input { display: none; }
    
    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px); z-index: 9998; display: none; }
    .modal-overlay.show { display: block; }
    .modal-container { position: fixed; inset: 0; display: none; align-items: center; justify-content: center; z-index: 9999; padding: 1rem; }
    .modal-container.show { display: flex; }
    .modal-box { width: 100%; max-width: 500px; background: linear-gradient(135deg, #0d1b2a, #000022); border: 1px solid rgba(0, 229, 255, 0.25); border-radius: 20px; overflow: hidden; }
    .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 1.5rem; background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.15)); border-bottom: 1px solid rgba(0, 229, 255, 0.15); }
    .modal-header-left { display: flex; align-items: center; gap: 1rem; }
    .modal-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #6366f1, #8b5cf6); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.25rem; }
    .modal-title { font-family: 'Rajdhani', sans-serif; color: #fff; font-size: 1.25rem; font-weight: 700; margin: 0; }
    .modal-subtitle { color: #94a3b8; font-size: 0.85rem; margin: 0; }
    .modal-close { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: rgba(255, 255, 255, 0.1); border: none; border-radius: 10px; color: #94a3b8; cursor: pointer; }
    .modal-close:hover { background: rgba(239, 68, 68, 0.2); color: #ef4444; }
    .modal-body { padding: 1.5rem; }
    .form-group { margin-bottom: 1.25rem; }
    .form-label { display: block; color: #94a3b8; font-size: 0.85rem; font-weight: 500; margin-bottom: 0.5rem; }
    .form-label i { color: #00E5FF; margin-right: 0.4rem; }
    .form-input { width: 100%; box-sizing: border-box; background: rgba(0, 0, 20, 0.6); border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; padding: 0.875rem 1rem; color: #fff; font-size: 0.9rem; }
    .form-input:focus { outline: none; border-color: #00E5FF; }
    .form-input::placeholder { color: #64748b; }
    .modal-footer { display: flex; align-items: center; justify-content: flex-end; gap: 0.75rem; padding: 1rem 1.5rem; background: rgba(0, 0, 20, 0.4); border-top: 1px solid rgba(0, 229, 255, 0.1); }
    .btn-cancel { padding: 0.75rem 1.25rem; background: rgba(255, 255, 255, 0.1); border: none; border-radius: 10px; color: #94a3b8; font-weight: 500; cursor: pointer; }
    .btn-submit { padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #6366f1, #8b5cf6); border: none; border-radius: 10px; color: #fff; font-weight: 600; cursor: pointer; }
    .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; }
    .member-results { margin-top: 0.5rem; background: #0d1b2a; border: 1px solid rgba(0, 229, 255, 0.2); border-radius: 12px; max-height: 200px; overflow-y: auto; display: none; }
    .member-results.show { display: block; }
    .member-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; cursor: pointer; border-bottom: 1px solid rgba(0, 229, 255, 0.1); }
    .member-item:hover { background: rgba(0, 229, 255, 0.1); }
    .member-item:last-child { border-bottom: none; }
    .member-item img { width: 36px; height: 36px; border-radius: 50%; }
    .member-item .name { color: #fff; font-weight: 500; }
    .member-item .email { color: #64748b; font-size: 0.8rem; }
    
    @media (max-width: 768px) {
        .chat-sidebar { display: none; }
    }
</style>
@endpush

@section('content')
<div class="chat-page">
    <aside class="chat-sidebar">
        <div class="sidebar-header">
            <a href="{{ route('chat.index') }}">
                <i class="fas fa-arrow-left"></i>
                <i class="fas fa-comments"></i>
                <span>Global Chat</span>
            </a>
        </div>

        <div class="user-card">
            <img src="{{ $conversation->getDisplayAvatar(auth()->id()) }}" alt="Avatar">
            <div>
                <h6>{{ $conversation->getDisplayName(auth()->id()) }}</h6>
            </div>
        </div>

        <div class="sidebar-actions">
            <button class="btn-action btn-primary-action" id="openModalBtn">
                <i class="fas fa-users"></i> Tạo nhóm chat
            </button>
            <button class="btn-action btn-danger-action" id="leave-btn">
                <i class="fas fa-sign-out-alt"></i> {{ $conversation->type === 'group' ? 'Rời nhóm' : 'Rời cuộc trò chuyện' }}
            </button>
        </div>

        <div class="conversations-label">Cuộc trò chuyện khác</div>
    </aside>

    <div class="chat-main-area">
        <header class="chat-header">
            <div class="chat-header-left">
                <img src="{{ $conversation->getDisplayAvatar(auth()->id()) }}" alt="Avatar">
                <div>
                    <h6>{{ $conversation->getDisplayName(auth()->id()) }}</h6>
                    <span>{{ $conversation->type === 'group' ? 'Nhóm chat' : 'Cuộc trò chuyện riêng tư' }}</span>
                </div>
            </div>
            <div class="dropdown-wrapper">
                <button class="btn-menu" id="menu-toggle"><i class="fas fa-ellipsis-v"></i></button>
                <div class="dropdown-menu-chat" id="dropdown-menu">
                    <button class="dropdown-item-chat"><i class="fas fa-bell"></i> Bật thông báo</button>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item-chat"><i class="fas fa-broom"></i> Xóa lịch sử</button>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item-chat danger" id="leave-btn-menu">
                        <i class="fas fa-sign-out-alt"></i> Rời cuộc trò chuyện
                    </button>
                </div>
            </div>
        </header>

        <div class="messages-area" id="messages-container">
            <div id="messages-list">
                @foreach($messages as $message)
                @include('chat.partials.message', ['message' => $message, 'currentUser' => $user])
                @endforeach
            </div>
        </div>

        <div class="chat-input-area">
            <!-- Image Preview -->
            <div id="file-preview" class="file-preview" style="display: none;">
                <div class="preview-content">
                    <img id="preview-image" src="" alt="Preview" class="preview-img">
                    <div id="preview-file" class="preview-file" style="display: none;">
                        <i class="fas fa-file"></i>
                        <span id="preview-filename"></span>
                    </div>
                    <button type="button" id="remove-file" class="remove-file-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="message-form">
                @csrf
                <input type="file" id="file-input" accept="image/*,application/pdf,.doc,.docx,.txt" style="display:none;">
                <div class="input-wrapper">
                    <button type="button" class="btn-input btn-attach" id="attach-btn"><i class="fas fa-paperclip"></i></button>
                    <textarea id="message-input" class="message-input" placeholder="Nhập tin nhắn..." rows="1"></textarea>
                    <div class="emoji-wrapper">
                        <button type="button" class="btn-input btn-emoji" id="emoji-toggle"><i class="far fa-smile"></i></button>
                        <div class="emoji-picker" id="emoji-picker">
                            <div class="emoji-grid">
                                @foreach(['😀','😂','😍','🥰','😊','🤔','😢','😡','👍','👎','❤️','🔥','💯','🎉','👏','🤝'] as $e)
                                <button type="button" class="emoji-btn" data-emoji="{{ $e }}">{{ $e }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-input btn-send"><i class="fas fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Group Modal -->
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
                <label class="form-label"><i class="fas fa-user-plus"></i> Thêm thành viên</label>
                <input type="text" class="form-input" id="memberSearch" placeholder="Tìm người dùng...">
                <div class="member-results" id="memberResults"></div>
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
    const convId = {{ $conversation->id }};
    const msgContainer = document.getElementById('messages-container');
    const msgList = document.getElementById('messages-list');
    const msgInput = document.getElementById('message-input');
    const msgForm = document.getElementById('message-form');
    const fileInput = document.getElementById('file-input');
    const emojiPicker = document.getElementById('emoji-picker');
    const dropdownMenu = document.getElementById('dropdown-menu');
    
    // Modal elements
    const overlay = document.getElementById('modalOverlay');
    const container = document.getElementById('modalContainer');
    const groupName = document.getElementById('groupName');
    const submitBtn = document.getElementById('submitGroupBtn');
    const memberSearch = document.getElementById('memberSearch');
    const memberResults = document.getElementById('memberResults');
    const selectedMembers = document.getElementById('selectedMembers');
    let selectedUsers = [];
    
    // Open modal
    document.getElementById('openModalBtn').onclick = () => {
        overlay.classList.add('show');
        container.classList.add('show');
    };
    
    // Close modal
    document.getElementById('closeModalBtn').onclick = () => {
        overlay.classList.remove('show');
        container.classList.remove('show');
    };
    document.getElementById('cancelModalBtn').onclick = () => {
        overlay.classList.remove('show');
        container.classList.remove('show');
    };
    overlay.onclick = () => {
        overlay.classList.remove('show');
        container.classList.remove('show');
    };
    
    // Group name validation
    groupName.oninput = () => {
        submitBtn.disabled = groupName.value.trim().length < 2;
    };
    
    // Search members
    let searchTimeout;
    memberSearch.oninput = function() {
        clearTimeout(searchTimeout);
        const q = this.value.trim();
        if (q.length < 2) { memberResults.classList.remove('show'); return; }
        searchTimeout = setTimeout(() => {
            fetch(`{{ route("chat.search-users") }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    if (data.users?.length) {
                        const filtered = data.users.filter(u => !selectedUsers.find(s => s.id === u.id));
                        memberResults.innerHTML = filtered.map(u => 
                            `<div class="member-item" data-id="${u.id}" data-name="${u.name}" data-avatar="${u.avatar}">
                                <img src="${u.avatar}">
                                <div><div class="name">${u.name}</div><div class="email">${u.email}</div></div>
                            </div>`
                        ).join('') || '<div style="padding:1rem;color:#64748b;text-align:center;">Không tìm thấy</div>';
                        memberResults.classList.add('show');
                    } else {
                        memberResults.innerHTML = '<div style="padding:1rem;color:#64748b;text-align:center;">Không tìm thấy</div>';
                        memberResults.classList.add('show');
                    }
                });
        }, 300);
    };
    
    // Select member
    memberResults.onclick = function(e) {
        const item = e.target.closest('.member-item');
        if (!item) return;
        selectedUsers.push({ id: item.dataset.id, name: item.dataset.name, avatar: item.dataset.avatar });
        renderMembers();
        memberSearch.value = '';
        memberResults.classList.remove('show');
    };
    
    function renderMembers() {
        selectedMembers.innerHTML = selectedUsers.map(u => 
            `<span style="display:inline-flex;align-items:center;gap:0.4rem;background:rgba(0,229,255,0.1);border:1px solid rgba(0,229,255,0.2);border-radius:8px;padding:0.3rem 0.6rem;">
                <img src="${u.avatar}" style="width:20px;height:20px;border-radius:50%;">
                <span style="color:#fff;font-size:0.85rem;">${u.name}</span>
                <button type="button" onclick="removeUser(${u.id})" style="background:none;border:none;color:#64748b;cursor:pointer;">&times;</button>
            </span>`
        ).join('');
    }
    
    window.removeUser = function(id) {
        selectedUsers = selectedUsers.filter(u => u.id != id);
        renderMembers();
    };
    
    // Submit group
    submitBtn.onclick = function() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('name', groupName.value.trim());
        selectedUsers.forEach(u => formData.append('user_ids[]', u.id));
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo...';
        fetch('{{ route("chat.create-group") }}', { method: 'POST', body: formData })
            .then(r => r.json())
            .then(data => {
                if (data.success) window.location.href = data.redirect_url;
                else {
                    alert(data.message || 'Lỗi');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-plus"></i> Tạo nhóm';
                }
            });
    };
    
    // Scroll to bottom
    msgContainer.scrollTop = msgContainer.scrollHeight;
    
    msgInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    
    // File preview elements
    const filePreview = document.getElementById('file-preview');
    const previewImage = document.getElementById('preview-image');
    const previewFile = document.getElementById('preview-file');
    const previewFilename = document.getElementById('preview-filename');
    const removeFileBtn = document.getElementById('remove-file');
    
    document.getElementById('attach-btn').onclick = () => fileInput.click();
    
    // Handle file selection with preview
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (!file) {
            filePreview.style.display = 'none';
            return;
        }
        
        const isImage = file.type.startsWith('image/');
        
        if (isImage) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                previewFile.style.display = 'none';
                filePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = 'none';
            previewFile.style.display = 'flex';
            previewFilename.textContent = file.name;
            filePreview.style.display = 'block';
        }
    });
    
    // Remove file
    removeFileBtn.onclick = function() {
        fileInput.value = '';
        filePreview.style.display = 'none';
        previewImage.src = '';
    };
    
    document.getElementById('emoji-toggle').onclick = (e) => {
        e.stopPropagation();
        emojiPicker.classList.toggle('show');
        dropdownMenu.classList.remove('show');
    };
    
    document.querySelectorAll('.emoji-btn').forEach(btn => {
        btn.onclick = function() {
            msgInput.value += this.dataset.emoji;
            msgInput.focus();
            emojiPicker.classList.remove('show');
        };
    });
    
    document.getElementById('menu-toggle').onclick = (e) => {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
        emojiPicker.classList.remove('show');
    };
    
    document.onclick = () => {
        emojiPicker.classList.remove('show');
        dropdownMenu.classList.remove('show');
    };
    
    msgForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const msg = msgInput.value.trim();
        const file = fileInput.files[0];
        if (!msg && !file) return;
        
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('content', msg);
        if (file) formData.append('attachment', file);
        
        try {
            const res = await fetch(`/chat/conversation/${convId}/send`, { method: 'POST', body: formData });
            const data = await res.json();
            console.log('Send response:', data);
            if (data.success && data.message) {
                // Build message HTML from JSON response (matching message.blade.php structure)
                const m = data.message;
                const isMine = m.sender.id === {{ Auth::id() }};
                
                let attachmentHtml = '';
                if (m.attachment_url) {
                    if (m.type === 'image') {
                        attachmentHtml = `<div class="msg-attachment">
                            <img src="${m.attachment_url}" alt="Hình ảnh" class="msg-image" onclick="window.open('${m.attachment_url}', '_blank')">
                        </div>`;
                    } else if (m.type === 'file') {
                        attachmentHtml = `<div class="msg-attachment">
                            <a href="${m.attachment_url}" target="_blank" class="msg-file">
                                <i class="fas fa-file"></i>
                                <span>${m.attachment_name || 'Tệp đính kèm'}</span>
                            </a>
                        </div>`;
                    }
                }
                
                let textHtml = '';
                if (m.content) {
                    textHtml = `<div class="msg-text">${m.content.replace(/\n/g, '<br>').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/&lt;br&gt;/g, '<br>')}</div>`;
                }
                
                const messageHtml = `
                <div class="message-item ${isMine ? 'own' : 'other'}" data-message-id="${m.id}">
                    <div class="message-content">
                        <img src="${m.sender.avatar}" alt="${m.sender.name}" class="msg-avatar">
                        <div class="message-bubble">
                            ${!isMine ? `<div class="msg-sender">${m.sender.name}</div>` : ''}
                            ${attachmentHtml}
                            ${textHtml}
                            <div class="msg-time">${m.formatted_time}</div>
                        </div>
                    </div>
                </div>`;
                
                msgList.insertAdjacentHTML('beforeend', messageHtml);
                msgContainer.scrollTop = msgContainer.scrollHeight;
                msgInput.value = '';
                msgInput.style.height = 'auto';
                fileInput.value = '';
                filePreview.style.display = 'none';
                previewImage.src = '';
                
                // Update lastMessageId for polling
                if (typeof lastMessageId !== 'undefined') {
                    lastMessageId = Math.max(lastMessageId, m.id);
                }
            } else if (data.error) {
                console.error('Send error:', data.error);
                alert(data.error);
            }
        } catch (err) { console.error('Fetch error:', err); }
    });
    
    msgInput.onkeydown = (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            msgForm.requestSubmit();
        }
    };
    
    const leaveHandler = async () => {
        if (!confirm('Bạn có chắc muốn rời?')) return;
        try {
            const res = await fetch(`/chat/conversation/${convId}/leave`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            const data = await res.json();
            if (data.success) window.location.href = '{{ route("chat.index") }}';
        } catch (err) { console.error(err); }
    };
    
    document.getElementById('leave-btn').onclick = leaveHandler;
    document.getElementById('leave-btn-menu').onclick = leaveHandler;
    
    // Real-time polling for new messages
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};
    const currentUserId = {{ Auth::id() }};
    
    async function fetchNewMessages() {
        try {
            const res = await fetch(`/chat/conversation/${convId}/messages?after_id=${lastMessageId}`);
            const data = await res.json();
            
            if (data.data && data.data.length > 0) {
                data.data.forEach(m => {
                    // Skip if this message is from current user (already added when sent)
                    if (m.sender.id === currentUserId) {
                        lastMessageId = Math.max(lastMessageId, m.id);
                        return;
                    }
                    
                    // Check if message already exists
                    if (document.querySelector(`[data-message-id="${m.id}"]`)) {
                        lastMessageId = Math.max(lastMessageId, m.id);
                        return;
                    }
                    
                    let attachmentHtml = '';
                    if (m.attachment_url) {
                        if (m.type === 'image') {
                            attachmentHtml = `<div class="msg-attachment">
                                <img src="${m.attachment_url}" alt="Hình ảnh" class="msg-image" onclick="window.open('${m.attachment_url}', '_blank')">
                            </div>`;
                        } else if (m.type === 'file') {
                            attachmentHtml = `<div class="msg-attachment">
                                <a href="${m.attachment_url}" target="_blank" class="msg-file">
                                    <i class="fas fa-file"></i>
                                    <span>${m.attachment_name || 'Tệp đính kèm'}</span>
                                </a>
                            </div>`;
                        }
                    }
                    
                    let textHtml = '';
                    if (m.content) {
                        const escaped = m.content.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
                        textHtml = `<div class="msg-text">${escaped}</div>`;
                    }
                    
                    const messageHtml = `
                    <div class="message-item other" data-message-id="${m.id}">
                        <div class="message-content">
                            <img src="${m.sender.avatar}" alt="${m.sender.name}" class="msg-avatar">
                            <div class="message-bubble">
                                <div class="msg-sender">${m.sender.name}</div>
                                ${attachmentHtml}
                                ${textHtml}
                                <div class="msg-time">${m.formatted_time}</div>
                            </div>
                        </div>
                    </div>`;
                    
                    msgList.insertAdjacentHTML('beforeend', messageHtml);
                    lastMessageId = Math.max(lastMessageId, m.id);
                });
                
                // Scroll to bottom if user is near bottom
                const isNearBottom = msgContainer.scrollHeight - msgContainer.scrollTop - msgContainer.clientHeight < 150;
                if (isNearBottom) {
                    msgContainer.scrollTop = msgContainer.scrollHeight;
                }
            }
        } catch (err) {
            console.error('Polling error:', err);
        }
    }
    
    // Poll every 3 seconds
    const pollingInterval = setInterval(fetchNewMessages, 3000);
    
    // Clean up on page leave
    window.addEventListener('beforeunload', () => {
        clearInterval(pollingInterval);
    });
});
</script>
@endpush
