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
        border-radius: 12px; padding: 0.75rem; width: 280px;
        display: none; z-index: 100;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
    
    .emoji-picker.show { display: block; }
    
    .emoji-grid { display: grid; grid-template-columns: repeat(8, 1fr); gap: 0.35rem; }
    
    .emoji-btn {
        width: 30px; height: 30px;
        padding: 0; background: transparent; border: none;
        border-radius: 6px; font-size: 1.2rem; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
    }
    
    .emoji-btn:hover { background: rgba(0, 229, 255, 0.15); transform: scale(1.15); }
    
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
        body:has(.chat-page) main { margin-left: 0 !important; width: 100% !important; }
        .chat-page { padding: 0; gap: 0; }
        .chat-sidebar { display: none; }
        .chat-main-area { border-radius: 0; border: none; }
        .btn-back-mobile { display: flex !important; }
        .chat-header { padding: 0.75rem 1rem; }
        .chat-input-area { padding: 0.75rem 1rem; }
        .messages-area { padding: 1rem; }
    }
    
    .btn-back-mobile {
        display: none;
        width: 36px; height: 36px;
        align-items: center; justify-content: center;
        background: rgba(0, 229, 255, 0.1);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 8px;
        color: #00E5FF;
        text-decoration: none;
        margin-right: 0.5rem;
    }
    
    .btn-back-mobile:hover { background: rgba(0, 229, 255, 0.2); color: #fff; }
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
                <a href="{{ route('chat.index') }}" class="btn-back-mobile">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <img src="{{ $conversation->getDisplayAvatar(auth()->id()) }}" alt="Avatar">
                <div>
                    <h6>{{ $conversation->getDisplayName(auth()->id()) }}</h6>
                    <span>{{ $conversation->type === 'group' ? 'Nhóm chat' : 'Cuộc trò chuyện riêng tư' }}</span>
                </div>
            </div>
            <div class="dropdown-wrapper">
                <button class="btn-menu" id="menu-toggle"><i class="fas fa-ellipsis-v"></i></button>
                <div class="dropdown-menu-chat" id="dropdown-menu">
                    @if($conversation->type === 'group')
                    <button class="dropdown-item-chat" id="view-members-btn">
                        <i class="fas fa-users"></i> Xem thành viên ({{ $conversation->participants->count() }})
                    </button>
                    <div class="dropdown-divider"></div>
                    @endif
                    <button class="dropdown-item-chat" id="toggle-notification-btn">
                        <i class="fas fa-bell"></i> <span id="notification-text">Bật thông báo</span>
                    </button>
                    <div class="dropdown-divider"></div>
                    <button class="dropdown-item-chat" id="clear-history-btn">
                        <i class="fas fa-broom"></i> Xóa lịch sử
                    </button>
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

<!-- Confirm Modal -->
<div class="modal-overlay" id="confirmOverlay"></div>
<div class="modal-container" id="confirmContainer">
    <div class="modal-box" style="max-width: 400px;">
        <div class="modal-header" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(220, 38, 38, 0.15));">
            <div class="modal-header-left">
                <div class="modal-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <h3 class="modal-title" id="confirmTitle">Xác nhận</h3>
                    <p class="modal-subtitle" id="confirmSubtitle">Hành động này không thể hoàn tác</p>
                </div>
            </div>
            <button class="modal-close" id="confirmCloseBtn"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p id="confirmMessage" style="color: #94a3b8; font-size: 0.95rem; line-height: 1.6; margin: 0;">Bạn có chắc chắn muốn thực hiện hành động này?</p>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" id="confirmCancelBtn">Hủy</button>
            <button class="btn-submit" id="confirmOkBtn" style="background: linear-gradient(135deg, #ef4444, #dc2626);"><i class="fas fa-check"></i> Xác nhận</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const convSlug = '{{ $conversation->slug }}';
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
        btn.onclick = function(e) {
            e.stopPropagation(); // Prevent closing picker
            msgInput.value += this.dataset.emoji;
            msgInput.focus();
            // Don't close picker - let user add multiple emojis
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
            const res = await fetch(`/chat/conversation/${convSlug}/send`, { method: 'POST', body: formData });
            const data = await res.json();
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
                alert(data.error);
            }
        } catch (err) { /* Silent error */ }
    });
    
    msgInput.onkeydown = (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            msgForm.requestSubmit();
        }
    };
    
    // Custom confirm modal
    const confirmOverlay = document.getElementById('confirmOverlay');
    const confirmContainer = document.getElementById('confirmContainer');
    const confirmTitle = document.getElementById('confirmTitle');
    const confirmSubtitle = document.getElementById('confirmSubtitle');
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmOkBtn = document.getElementById('confirmOkBtn');
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    const confirmCloseBtn = document.getElementById('confirmCloseBtn');
    
    let confirmCallback = null;
    
    function showConfirm(options) {
        confirmTitle.textContent = options.title || 'Xác nhận';
        confirmSubtitle.textContent = options.subtitle || 'Hành động này không thể hoàn tác';
        confirmMessage.textContent = options.message || 'Bạn có chắc chắn?';
        confirmOkBtn.innerHTML = `<i class="fas fa-${options.icon || 'check'}"></i> ${options.okText || 'Xác nhận'}`;
        confirmCallback = options.onConfirm;
        confirmOverlay.classList.add('show');
        confirmContainer.classList.add('show');
    }
    
    function hideConfirm() {
        confirmOverlay.classList.remove('show');
        confirmContainer.classList.remove('show');
        confirmCallback = null;
    }
    
    confirmCancelBtn.onclick = hideConfirm;
    confirmCloseBtn.onclick = hideConfirm;
    confirmOverlay.onclick = hideConfirm;
    confirmOkBtn.onclick = () => {
        if (confirmCallback) confirmCallback();
        hideConfirm();
    };
    
    const leaveHandler = () => {
        showConfirm({
            title: 'Rời cuộc trò chuyện',
            subtitle: 'Bạn sẽ không nhận được tin nhắn mới',
            message: 'Bạn có chắc chắn muốn rời khỏi cuộc trò chuyện này? Bạn sẽ cần được mời lại để tham gia.',
            okText: 'Rời đi',
            icon: 'sign-out-alt',
            onConfirm: async () => {
                try {
                    const res = await fetch(`/chat/conversation/${convSlug}/leave`, {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    const data = await res.json();
                    if (data.success) window.location.href = '{{ route("chat.index") }}';
                    else alert(data.error || 'Không thể rời cuộc trò chuyện');
                } catch (err) { 
                    alert('Có lỗi xảy ra');
                }
            }
        });
    };
    
    document.getElementById('leave-btn').onclick = leaveHandler;
    document.getElementById('leave-btn-menu').onclick = leaveHandler;
    
    // Clear history handler
    const clearHistoryBtn = document.getElementById('clear-history-btn');
    if (clearHistoryBtn) {
        clearHistoryBtn.onclick = () => {
            showConfirm({
                title: 'Xóa lịch sử chat',
                subtitle: 'Tất cả tin nhắn sẽ bị xóa',
                message: 'Bạn có chắc chắn muốn xóa toàn bộ lịch sử chat? Hành động này không thể hoàn tác.',
                okText: 'Xóa',
                icon: 'trash',
                onConfirm: async () => {
                    try {
                        const res = await fetch(`/chat/conversation/${convSlug}/clear-history`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        const data = await res.json();
                        if (data.success) {
                            msgList.innerHTML = '';
                            dropdownMenu.classList.remove('show');
                        } else {
                            alert(data.error || 'Không thể xóa lịch sử');
                        }
                    } catch (err) { 
                        alert('Có lỗi xảy ra');
                    }
                }
            });
        };
    }
    
    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toast
        const existingToast = document.getElementById('chat-toast');
        if (existingToast) existingToast.remove();
        
        const iconMap = {
            success: 'check-circle',
            error: 'exclamation-circle',
            info: 'info-circle',
            warning: 'exclamation-triangle'
        };
        const colorMap = {
            success: '#00E5FF',
            error: '#ef4444',
            info: '#3b82f6',
            warning: '#f59e0b'
        };
        
        const toast = document.createElement('div');
        toast.id = 'chat-toast';
        toast.innerHTML = `
            <div style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 99999; animation: toastSlideIn 0.3s ease;">
                <div style="background: linear-gradient(135deg, rgba(13, 27, 42, 0.98), rgba(0, 0, 34, 0.98)); border: 1px solid ${colorMap[type]}40; border-radius: 12px; padding: 14px 20px; display: flex; align-items: center; gap: 12px; box-shadow: 0 10px 40px rgba(0,0,0,0.5), 0 0 20px ${colorMap[type]}20; backdrop-filter: blur(10px);">
                    <div style="width: 32px; height: 32px; background: ${colorMap[type]}20; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: ${colorMap[type]};">
                        <i class="fas fa-${iconMap[type]}"></i>
                    </div>
                    <span style="color: #fff; font-size: 14px; font-weight: 500;">${message}</span>
                </div>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'toastSlideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Add toast animation styles
    if (!document.getElementById('toast-styles')) {
        const style = document.createElement('style');
        style.id = 'toast-styles';
        style.textContent = `
            @keyframes toastSlideIn { from { opacity: 0; transform: translateX(-50%) translateY(-20px); } to { opacity: 1; transform: translateX(-50%) translateY(0); } }
            @keyframes toastSlideOut { from { opacity: 1; transform: translateX(-50%) translateY(0); } to { opacity: 0; transform: translateX(-50%) translateY(-20px); } }
        `;
        document.head.appendChild(style);
    }
    
    // Toggle notification handler
    let notificationEnabled = localStorage.getItem(`chat_notification_${convSlug}`) !== 'disabled';
    const toggleNotificationBtn = document.getElementById('toggle-notification-btn');
    const notificationText = document.getElementById('notification-text');
    
    function updateNotificationUI() {
        if (notificationText) {
            notificationText.textContent = notificationEnabled ? 'Tắt thông báo' : 'Bật thông báo';
        }
        if (toggleNotificationBtn) {
            const icon = toggleNotificationBtn.querySelector('i');
            if (icon) {
                icon.className = notificationEnabled ? 'fas fa-bell-slash' : 'fas fa-bell';
            }
        }
    }
    
    updateNotificationUI();
    
    if (toggleNotificationBtn) {
        toggleNotificationBtn.onclick = () => {
            notificationEnabled = !notificationEnabled;
            localStorage.setItem(`chat_notification_${convSlug}`, notificationEnabled ? 'enabled' : 'disabled');
            updateNotificationUI();
            dropdownMenu.classList.remove('show');
            showToast(notificationEnabled ? 'Đã bật thông báo cho cuộc trò chuyện này' : 'Đã tắt thông báo cho cuộc trò chuyện này', notificationEnabled ? 'success' : 'info');
        };
    }
    
    // Real-time WebSocket with Laravel Echo
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};
    const currentUserId = {{ Auth::id() }};
    
    // Helper function to add message to UI
    function addMessageToUI(m, isOwn = false) {
        // Check if message already exists
        if (document.querySelector(`[data-message-id="${m.id}"]`)) {
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
        
        const messageClass = isOwn ? 'own' : 'other';
        const senderHtml = isOwn ? '' : `<div class="msg-sender">${m.sender.name}</div>`;
        
        const messageHtml = `
        <div class="message-item ${messageClass}" data-message-id="${m.id}">
            <div class="message-content">
                <img src="${m.sender.avatar}" alt="${m.sender.name}" class="msg-avatar">
                <div class="message-bubble">
                    ${senderHtml}
                    ${attachmentHtml}
                    ${textHtml}
                    <div class="msg-time">${m.formatted_time}</div>
                </div>
            </div>
        </div>`;
        
        msgList.insertAdjacentHTML('beforeend', messageHtml);
        lastMessageId = Math.max(lastMessageId, m.id);
        
        // Scroll to bottom if user is near bottom
        const isNearBottom = msgContainer.scrollHeight - msgContainer.scrollTop - msgContainer.clientHeight < 150;
        if (isNearBottom) {
            msgContainer.scrollTop = msgContainer.scrollHeight;
        }
    }
    
    // Typing indicator element
    let typingIndicator = null;
    const typingUsers = new Map();
    
    function updateTypingIndicator() {
        const names = Array.from(typingUsers.values());
        
        if (names.length === 0) {
            if (typingIndicator) {
                typingIndicator.remove();
                typingIndicator = null;
            }
            return;
        }
        
        const text = names.length === 1 
            ? `${names[0]} đang gõ...` 
            : `${names.slice(0, 2).join(', ')} đang gõ...`;
        
        if (!typingIndicator) {
            typingIndicator = document.createElement('div');
            typingIndicator.className = 'typing-indicator';
            typingIndicator.style.cssText = 'padding: 0.5rem 1rem; color: #94a3b8; font-size: 0.85rem; font-style: italic;';
            msgList.appendChild(typingIndicator);
        }
        
        typingIndicator.innerHTML = `<i class="fas fa-ellipsis-h"></i> ${text}`;
        msgContainer.scrollTop = msgContainer.scrollHeight;
    }
    
    // Listen for real-time events via Laravel Echo
    if (typeof window.Echo !== 'undefined') {
        window.Echo.private(`conversation.${convSlug}`)
            // New message received
            .listen('.message.sent', (e) => {
                // Don't add if it's from current user (already added when sent)
                if (e.sender.id !== currentUserId) {
                    addMessageToUI(e, false);
                    
                    // Play notification sound only if notifications are enabled for this conversation
                    if (notificationEnabled) {
                        try {
                            const audio = new Audio('/matchfound.mp3');
                            audio.volume = 0.85;
                            audio.play().catch(() => {});
                        } catch (err) {}
                    }
                }
            })
            // User typing
            .listen('.user.typing', (e) => {
                if (e.user_id !== currentUserId) {
                    if (e.is_typing) {
                        typingUsers.set(e.user_id, e.user_name);
                    } else {
                        typingUsers.delete(e.user_id);
                    }
                    updateTypingIndicator();
                    
                    // Auto-remove typing indicator after 5 seconds
                    if (e.is_typing) {
                        setTimeout(() => {
                            typingUsers.delete(e.user_id);
                            updateTypingIndicator();
                        }, 5000);
                    }
                }
            })
            // Message deleted
            .listen('.message.deleted', (e) => {
                const msgEl = document.querySelector(`[data-message-id="${e.message_id}"]`);
                if (msgEl) {
                    msgEl.remove();
                }
            });
    } else {
        // Fallback to polling if Echo is not available
        async function fetchNewMessages() {
            try {
                const res = await fetch(`/chat/conversation/${convSlug}/messages?after_id=${lastMessageId}`);
                const data = await res.json();
                
                if (data.data && data.data.length > 0) {
                    data.data.forEach(m => {
                        if (m.sender.id !== currentUserId) {
                            addMessageToUI(m, false);
                        } else {
                            lastMessageId = Math.max(lastMessageId, m.id);
                        }
                    });
                }
            } catch (err) {
                // Silent polling error
            }
        }
        
        const pollingInterval = setInterval(fetchNewMessages, 3000);
        window.addEventListener('beforeunload', () => clearInterval(pollingInterval));
    }
    
    // Send typing status when user types
    let typingTimeout;
    let isCurrentlyTyping = false;
    
    msgInput.addEventListener('input', function() {
        if (!isCurrentlyTyping) {
            isCurrentlyTyping = true;
            fetch(`/chat/conversation/${convSlug}/typing`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ is_typing: true })
            });
        }
        
        clearTimeout(typingTimeout);
        typingTimeout = setTimeout(() => {
            isCurrentlyTyping = false;
            fetch(`/chat/conversation/${convSlug}/typing`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ is_typing: false })
            });
        }, 2000);
    });

    // View members modal
    @if($conversation->type === 'group')
    @php
        $currentParticipant = $conversation->participants->where('user_id', auth()->id())->first();
        $isGroupAdmin = $currentParticipant && $currentParticipant->role === 'admin';
        $membersData = $conversation->participants->load('user')->map(function($p) {
            return [
                'id' => $p->user->id,
                'name' => $p->user->display_name ?? $p->user->name,
                'avatar' => $p->user->getDisplayAvatar(),
                'role' => $p->role,
                'is_online' => $p->user->isOnline(),
            ];
        })->values();
    @endphp
    const isGroupAdmin = {{ $isGroupAdmin ? 'true' : 'false' }};
    let currentMembers = @json($membersData);
    
    const viewMembersBtn = document.getElementById('view-members-btn');
    if (viewMembersBtn) {
        viewMembersBtn.addEventListener('click', function() {
            showMembersModal();
        });
    }
    
    function showMembersModal() {
        // Remove existing modal
        const existingModal = document.getElementById('members-modal');
        if (existingModal) existingModal.remove();
        
        let html = '<div style="position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:10000;display:flex;align-items:center;justify-content:center;" id="members-modal">';
        html += '<div style="background:linear-gradient(145deg,#0d1b2a,#000022);border:1px solid rgba(0,229,255,0.2);border-radius:16px;width:90%;max-width:450px;max-height:85vh;overflow:hidden;">';
        
        // Header
        html += '<div style="padding:1rem 1.5rem;border-bottom:1px solid rgba(0,229,255,0.1);display:flex;justify-content:space-between;align-items:center;">';
        html += '<h3 style="color:#fff;margin:0;font-size:1.1rem;"><i class="fas fa-users" style="color:#00E5FF;margin-right:0.5rem;"></i>Thành viên nhóm (<span id="member-count">' + currentMembers.length + '</span>)</h3>';
        html += '<button onclick="document.getElementById(\'members-modal\').remove()" style="background:none;border:none;color:#64748b;font-size:1.2rem;cursor:pointer;">&times;</button>';
        html += '</div>';
        
        // Add member section (admin only)
        if (isGroupAdmin) {
            html += '<div style="padding:1rem 1.5rem;border-bottom:1px solid rgba(0,229,255,0.1);">';
            html += '<div style="position:relative;">';
            html += '<input type="text" id="add-member-input" placeholder="Tìm người dùng để thêm..." style="width:100%;box-sizing:border-box;background:rgba(0,0,20,0.6);border:1px solid rgba(0,229,255,0.2);border-radius:10px;padding:0.75rem 1rem;color:#fff;font-size:0.9rem;">';
            html += '<div id="add-member-results" style="position:absolute;top:100%;left:0;right:0;background:#0d1b2a;border:1px solid rgba(0,229,255,0.2);border-radius:10px;margin-top:0.25rem;max-height:200px;overflow-y:auto;display:none;z-index:10;"></div>';
            html += '</div>';
            html += '</div>';
        }
        
        // Members list
        html += '<div id="members-list" style="padding:1rem;max-height:50vh;overflow-y:auto;">';
        html += renderMembersList();
        html += '</div></div></div>';
        
        document.body.insertAdjacentHTML('beforeend', html);
        
        // Close on backdrop click
        document.getElementById('members-modal').addEventListener('click', function(e) {
            if (e.target === this) this.remove();
        });
        
        // Setup add member search (admin only)
        if (isGroupAdmin) {
            setupAddMemberSearch();
        }
    }
    
    function renderMembersList() {
        let html = '';
        currentMembers.forEach(m => {
            const roleLabel = m.role === 'admin' ? '<span style="background:rgba(99,102,241,0.2);color:#818cf8;padding:0.15rem 0.5rem;border-radius:4px;font-size:0.7rem;margin-left:0.5rem;">Admin</span>' : '';
            const onlineStatus = m.is_online ? '<span style="width:8px;height:8px;background:#22c55e;border-radius:50%;position:absolute;bottom:2px;right:2px;border:2px solid #0d1b2a;"></span>' : '';
            
            // Kick button (admin only, can't kick self or other admins)
            let kickBtn = '';
            if (isGroupAdmin && m.id !== currentUserId && m.role !== 'admin') {
                kickBtn = `<button onclick="kickMember(${m.id}, '${m.name.replace(/'/g, "\\'")}')" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:0.4rem 0.75rem;border-radius:6px;font-size:0.75rem;cursor:pointer;display:flex;align-items:center;gap:0.3rem;" title="Kick khỏi nhóm"><i class="fas fa-user-minus"></i></button>`;
            }
            
            html += '<div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;border-radius:8px;margin-bottom:0.5rem;background:rgba(0,229,255,0.05);" data-member-id="' + m.id + '">';
            html += '<div style="position:relative;flex-shrink:0;"><img src="' + m.avatar + '" style="width:40px;height:40px;border-radius:50%;object-fit:cover;">' + onlineStatus + '</div>';
            html += '<div style="flex:1;min-width:0;"><div style="color:#fff;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + m.name + roleLabel + '</div></div>';
            html += kickBtn;
            html += '</div>';
        });
        return html;
    }
    
    function setupAddMemberSearch() {
        const input = document.getElementById('add-member-input');
        const results = document.getElementById('add-member-results');
        let searchTimeout;
        
        input.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const q = this.value.trim();
            if (q.length < 2) {
                results.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                fetch(`{{ route("chat.search-users") }}?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (data.users?.length) {
                            // Filter out existing members
                            const existingIds = currentMembers.map(m => m.id);
                            const filtered = data.users.filter(u => !existingIds.includes(u.id));
                            
                            if (filtered.length) {
                                results.innerHTML = filtered.map(u => 
                                    `<div class="add-member-item" data-id="${u.id}" data-name="${u.name}" data-avatar="${u.avatar}" style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;cursor:pointer;border-bottom:1px solid rgba(0,229,255,0.1);">
                                        <img src="${u.avatar}" style="width:32px;height:32px;border-radius:50%;">
                                        <div style="flex:1;">
                                            <div style="color:#fff;font-weight:500;">${u.name}</div>
                                            <div style="color:#64748b;font-size:0.8rem;">${u.email}</div>
                                        </div>
                                        <i class="fas fa-plus" style="color:#00E5FF;"></i>
                                    </div>`
                                ).join('');
                                results.style.display = 'block';
                                
                                // Add click handlers
                                results.querySelectorAll('.add-member-item').forEach(item => {
                                    item.addEventListener('click', function() {
                                        addMember(this.dataset.id, this.dataset.name, this.dataset.avatar);
                                    });
                                    item.addEventListener('mouseenter', function() {
                                        this.style.background = 'rgba(0,229,255,0.1)';
                                    });
                                    item.addEventListener('mouseleave', function() {
                                        this.style.background = 'transparent';
                                    });
                                });
                            } else {
                                results.innerHTML = '<div style="padding:1rem;color:#64748b;text-align:center;">Không tìm thấy người dùng mới</div>';
                                results.style.display = 'block';
                            }
                        } else {
                            results.innerHTML = '<div style="padding:1rem;color:#64748b;text-align:center;">Không tìm thấy</div>';
                            results.style.display = 'block';
                        }
                    });
            }, 300);
        });
        
        // Hide results when clicking outside
        document.addEventListener('click', function(e) {
            if (!input.contains(e.target) && !results.contains(e.target)) {
                results.style.display = 'none';
            }
        });
    }
    
    window.addMember = async function(userId, userName, userAvatar) {
        try {
            const res = await fetch(`/chat/conversation/${convSlug}/add-member`, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                },
                body: JSON.stringify({ user_id: userId })
            });
            const data = await res.json();
            
            if (data.success) {
                // Add to current members
                currentMembers.push({
                    id: parseInt(userId),
                    name: userName,
                    avatar: userAvatar,
                    role: 'member',
                    is_online: false
                });
                
                // Update UI
                document.getElementById('members-list').innerHTML = renderMembersList();
                document.getElementById('member-count').textContent = currentMembers.length;
                document.getElementById('add-member-input').value = '';
                document.getElementById('add-member-results').style.display = 'none';
                
                showToast(data.message, 'success');
            } else {
                showToast(data.error || 'Không thể thêm thành viên', 'error');
            }
        } catch (err) {
            showToast('Có lỗi xảy ra', 'error');
        }
    };
    
    window.kickMember = function(userId, userName) {
        // Close members modal first
        const membersModal = document.getElementById('members-modal');
        if (membersModal) membersModal.remove();
        
        showConfirm({
            title: 'Kick thành viên',
            subtitle: userName,
            message: `Bạn có chắc chắn muốn kick "${userName}" khỏi nhóm? Người này sẽ cần được mời lại để tham gia.`,
            okText: 'Kick',
            icon: 'user-minus',
            onConfirm: async () => {
                try {
                    const res = await fetch(`/chat/conversation/${convSlug}/kick-member/${userId}`, {
                        method: 'DELETE',
                        headers: { 
                            'Content-Type': 'application/json', 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        }
                    });
                    const data = await res.json();
                    
                    if (data.success) {
                        // Remove from current members
                        currentMembers = currentMembers.filter(m => m.id !== userId);
                        showToast(data.message, 'success');
                    } else {
                        showToast(data.error || 'Không thể kick thành viên', 'error');
                    }
                } catch (err) {
                    showToast('Có lỗi xảy ra', 'error');
                }
            }
        });
    };
    
    // Add fadeOut animation
    if (!document.getElementById('member-styles')) {
        const style = document.createElement('style');
        style.id = 'member-styles';
        style.textContent = `@keyframes fadeOut { from { opacity: 1; transform: translateX(0); } to { opacity: 0; transform: translateX(20px); } }`;
        document.head.appendChild(style);
    }
    @endif
});
</script>
@endpush
