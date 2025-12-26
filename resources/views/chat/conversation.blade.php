@extends('layouts.app')

@section('title', 'Chat - ' . $conversation->getDisplayName(auth()->id()))

@section('content')
<div class="container-fluid chat-container">
    <div class="row h-100">
        <!-- Sidebar -->
        <div class="col-md-4 col-lg-3 chat-sidebar h-100 p-0">
            <div class="d-flex flex-column h-100">
                <!-- Header -->
                <div class="chat-header">
                    <h5 class="mb-0">
                        <a href="{{ route('chat.index') }}" class="text-white text-decoration-none d-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i>
                            <i class="fas fa-comments me-2"></i>
                            Global Chat
                        </a>
                    </h5>
                </div>

                <!-- User Info -->
                <div class="user-info-card">
                    <div class="d-flex align-items-center">
                        <div class="position-relative">
                            <img src="{{ $conversation->getDisplayAvatar(auth()->id()) }}"
                                alt="Avatar" class="user-avatar me-3">
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold text-dark">{{ $conversation->getDisplayName(auth()->id()) }}</h6>
                            <div id="typing-indicator" class="typing-indicator" style="display: none;">
                                <i class="fas fa-circle-notch fa-spin"></i>
                                <span id="typing-users"></span> đang gõ...
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="p-3">
                    <div class="d-grid gap-2">
                        <button id="create-group-btn" class="btn btn-primary rounded-pill" 
                                data-bs-toggle="modal" data-bs-target="#createGroupModal">
                            <i class="fas fa-users me-2"></i>Tạo nhóm chat
                        </button>
                        <button id="leave-conversation-btn-sidebar" class="leave-btn">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ $conversation->type === 'group' ? 'Rời khỏi nhóm' : 'Rời khỏi cuộc trò chuyện' }}
                        </button>
                    </div>
                </div>

                

                

                <!-- Other Conversations -->
                <div class="flex-grow-1 p-3">
                    <div class="text-center text-muted">
                        <small class="fw-bold text-uppercase tracking-wide">Cuộc trò chuyện khác</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Chat -->
        <div class="col-md-8 col-lg-9 main-chat d-flex flex-column h-100 p-0">
            <!-- Top Bar -->
            <div class="chat-top-bar">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="{{ $conversation->getDisplayAvatar(auth()->id()) }}"
                            alt="Avatar" class="user-avatar me-3" style="width: 40px; height: 40px;">
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ $conversation->getDisplayName(auth()->id()) }}</h6>
                            <small class="text-muted">Cuộc trò chuyện riêng tư</small>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle rounded-pill" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a id="toggle-notifications-btn" class="dropdown-item rounded" href="#">
                                    <i class="fas fa-bell me-2 text-primary"></i><span id="notification-text">Bật thông báo</span>
                                </a></li>
                            
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a id="clear-chat-btn" class="dropdown-item rounded" href="#">
                                    <i class="fas fa-broom me-2 text-warning"></i>Xóa lịch sử chat
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @if($conversation->type === 'group' && $conversation->participants()->where('user_id', auth()->id())->first()?->role === 'admin')
                            <li><a id="delete-conversation-btn" class="dropdown-item text-danger rounded" href="#">
                                    <i class="fas fa-trash me-2"></i>Xóa nhóm chat
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endif
                            <li><a id="leave-conversation-btn-dropdown" class="dropdown-item text-danger rounded" href="#">
                                    <i class="fas fa-sign-out-alt me-2"></i>{{ $conversation->type === 'group' ? 'Rời khỏi nhóm' : 'Rời khỏi cuộc trò chuyện' }}
                                </a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="chat-messages flex-grow-1">
                <div id="messages-container" class="messages-container">
                    <div id="load-more-messages" class="text-center py-3" style="{{ $messages->hasMorePages() ? 'display: block;' : 'display: none;' }}">
                        <button class="load-more-btn">
                            <i class="fas fa-chevron-up me-2"></i>Tải tin nhắn cũ hơn
                        </button>
                    </div>
                    <div id="messages-list">
                        @foreach($messages as $message)
                        @include('chat.partials.message', ['message' => $message, 'currentUser' => $user])
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Input Area -->
            <div class="chat-input-area">
                <form id="message-form" enctype="multipart/form-data">
                    @csrf
                    <input type="file" id="file-input" accept="image/*,application/pdf,.doc,.docx,.txt" style="display: none;">
                    
                    <div id="file-preview" class="file-preview" style="display: none;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-alt me-3 text-primary fs-4"></i>
                            <div class="flex-grow-1">
                                <span id="file-name" class="fw-medium"></span>
                                <small class="d-block text-muted">Tệp đã chọn</small>
                            </div>
                            <button type="button" class="btn-close" aria-label="Remove File" id="remove-file-btn"></button>
                        </div>
                    </div>

                    <div class="input-group chat-input-wrapper">
                        <button type="button" class="input-btn attach-btn" id="attach-file-btn" title="Đính kèm file">
                            <i class="fas fa-paperclip"></i>
                        </button>

                        <div class="chat-input-container">
                            <textarea id="message-input" class="chat-input" placeholder="Nhập tin nhắn của bạn..."
                                maxlength="5000" autocomplete="off" rows="1"></textarea>
                        </div>

                        <div class="dropdown emoji-dropdown">
                            <button type="button" class="input-btn emoji-btn" title="Chọn emoji">
                                <i class="far fa-smile"></i>
                            </button>
                            <div class="dropdown-menu emoji-picker">
                                <div class="emoji-grid">
                                    @foreach(['😀', '😂', '😍', '🥰', '😊', '🤔', '😢', '😡', '👍', '👎', '❤️', '🔥', '💯', '🎉', '👏', '🤝', '😎', '🤩', '😘', '😋', '🤗', '🙄', '😴', '🤤', '🤯', '🥳', '😇', '🤠', '🤡', '🤢', '🤮', '😷'] as $emoji)
                                    <button class="emoji-item" data-emoji="{{ $emoji }}" type="button">{{ $emoji }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="input-btn send-btn" id="send-message-btn" title="Gửi tin nhắn">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modern Chat Interface - Fixed Layout */
    body {
        overflow: hidden; /* Ngăn scroll toàn trang */
        padding-top: 76px !important; /* Để tránh bị che bởi header */
    }
    
    /* Cố định header chung */
    .navbar {
        position: fixed !important;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1050;
        background: rgba(17, 24, 39, 0.95) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.15);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .chat-container {
        height: calc(100vh - 76px); /* Trừ đi chiều cao header */
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
        margin: 0;
        padding: 15px;
    }

    .row.h-100 {
        height: 100% !important;
        margin: 0;
    }

    .chat-sidebar {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        height: 100%;
        overflow-y: auto;
    }

    .chat-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 20px 20px 0 0;
        position: relative;
        overflow: hidden;
        margin-bottom: 0;
    }

    .chat-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
        pointer-events: none;
    }

    .chat-header h5 {
        font-weight: 600;
        margin-bottom: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .user-info-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        margin: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 15px;
        border: 3px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
    }

    .user-avatar:hover {
        border-color: #4f46e5;
        transform: scale(1.05);
    }

    .online-status {
        width: 14px;
        height: 14px;
        background: #10b981;
        border-radius: 50%;
        border: 3px solid white;
        position: absolute;
        bottom: -2px;
        right: -2px;
        animation: pulse-online 2s infinite;
        display: block;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4);
    }

    .online-status.offline {
        background: #6b7280;
        animation: none;
        display: none;
    }

    @keyframes pulse-online {
        0%, 100% { 
            opacity: 1; 
            transform: scale(1);
        }
        50% { 
            opacity: 0.7;
            transform: scale(1.1);
        }
    }

    /* Participants List Styles */
    .participant-item {
        background: rgba(255, 255, 255, 0.5);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .participant-item:hover {
        background: rgba(79, 70, 229, 0.1);
        transform: translateX(5px);
    }

    .participant-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e5e7eb;
        position: relative;
    }

    .participant-status {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid white;
        position: absolute;
        bottom: -2px;
        right: -2px;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }

    .participant-status.online {
        background: #10b981;
        animation: pulse-online 2s infinite;
    }

    .participant-status.offline {
        background: #6b7280;
        animation: none;
    }

    .participant-name {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1f2937;
    }

    .participant-status-text {
        font-size: 0.75rem;
    }

    /* Participants Section */
    .participants-section {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 15px;
        margin: 0 15px;
        overflow-y: auto;
        max-height: 300px;
    }

    .participants-section::-webkit-scrollbar {
        width: 4px;
    }

    .participants-section::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    .participants-section::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border-radius: 10px;
    }

    /* Online Counter */
    .online-counter {
        background: rgba(16, 185, 129, 0.1);
        border-radius: 10px;
        margin: 0 15px 15px;
    }

    .online-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        animation: pulse-online 2s infinite;
        flex-shrink: 0;
    }

    /* Modern Notification System */
    .modern-notifications-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        max-width: 400px;
        pointer-events: none;
    }

    .modern-notification {
        display: flex;
        align-items: flex-start;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transform: translateX(400px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: auto;
        max-width: 400px;
    }

    .modern-notification.show {
        transform: translateX(0);
        opacity: 1;
    }

    .modern-notification.hide {
        transform: translateX(400px);
        opacity: 0;
    }

    .modern-notification-success {
        border-left: 4px solid #10b981;
    }

    .modern-notification-success .notification-icon {
        color: #10b981;
    }

    .modern-notification-error {
        border-left: 4px solid #ef4444;
    }

    .modern-notification-error .notification-icon {
        color: #ef4444;
    }

    .modern-notification-warning {
        border-left: 4px solid #f59e0b;
    }

    .modern-notification-warning .notification-icon {
        color: #f59e0b;
    }

    .modern-notification-info {
        border-left: 4px solid #3b82f6;
    }

    .modern-notification-info .notification-icon {
        color: #3b82f6;
    }

    .notification-icon {
        font-size: 20px;
        margin-right: 12px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-title {
        font-weight: 600;
        font-size: 14px;
        color: #1f2937;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .notification-message {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.4;
    }

    .notification-close {
        background: none;
        border: none;
        color: #9ca3af;
        font-size: 14px;
        cursor: pointer;
        padding: 4px;
        border-radius: 6px;
        transition: all 0.2s ease;
        margin-left: 8px;
        flex-shrink: 0;
    }

    .notification-close:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #374151;
    }

    /* Modern Confirm Dialog */
    .modern-confirm-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(8px);
        z-index: 10001;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-confirm-overlay.show {
        opacity: 1;
    }

    .modern-confirm-modal {
        background: white;
        border-radius: 20px;
        max-width: 450px;
        width: 90vw;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        transform: scale(0.9);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .modern-confirm-overlay.show .modern-confirm-modal {
        transform: scale(1);
    }

    .modern-confirm-header {
        padding: 32px 32px 16px;
        text-align: center;
        border-bottom: 1px solid #f3f4f6;
    }

    .modern-confirm-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, #fef3c7, #fbbf24);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        font-size: 24px;
        color: #d97706;
    }

    .modern-confirm-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        line-height: 1.3;
    }

    .modern-confirm-body {
        padding: 16px 32px 24px;
        text-align: center;
    }

    .modern-confirm-message {
        font-size: 16px;
        color: #6b7280;
        line-height: 1.5;
        margin: 0;
    }

    .modern-confirm-footer {
        padding: 0 32px 32px;
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .modern-btn {
        padding: 12px 32px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: none;
        min-width: 120px;
    }

    .modern-btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .modern-btn-secondary:hover {
        background: #e5e7eb;
        color: #111827;
    }

    .modern-btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .modern-btn-danger:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    .typing-indicator {
        background: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .leave-btn {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        color: white;
        font-weight: 500;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        width: 100%;
    }

    .leave-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        color: white;
    }

    .main-chat {
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .chat-top-bar {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 1.5rem;
        border-radius: 20px 20px 0 0;
        flex-shrink: 0;
    }

    .chat-messages {
        background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        position: relative;
        flex: 1;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        max-height: none;
    }

    /* Custom scrollbar */
    .messages-container::-webkit-scrollbar {
        width: 6px;
    }

    .messages-container::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
    }

    .messages-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border-radius: 10px;
    }

    .messages-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(135deg, #3730a3, #6d28d9);
    }

    /* Smooth scrolling */
    .messages-container {
        scroll-behavior: smooth;
    }

    .load-more-btn {
        background: rgba(79, 70, 229, 0.1);
        border: 1px solid #4f46e5;
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        color: #4f46e5;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .load-more-btn:hover {
        background: #4f46e5;
        color: white;
        transform: translateY(-2px);
    }

    /* Message Bubbles */
    .message-bubble {
        padding: 1rem 1.5rem;
        border-radius: 20px;
        word-wrap: break-word;
        max-width: 75%;
        position: relative;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .message-bubble.own {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border-bottom-right-radius: 8px;
    }

    .message-bubble.other {
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 8px;
    }

    .message-time {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-top: 0.5rem;
    }

    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Input Area - Fixed positioning */
    .chat-input-area {
        background: white;
        padding: 1.5rem;
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 20px 20px;
        flex-shrink: 0;
        position: relative;
        z-index: 10;
    }

    .file-preview {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #d1d5db;
    }

    .chat-input-wrapper {
        background: #f8fafc;
        border-radius: 25px;
        padding: 0.5rem;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: flex-end;
        gap: 0.25rem;
    }

    .chat-input-container {
        flex: 1;
        position: relative;
    }

    .chat-input {
        width: 100%;
        border: none;
        background: transparent;
        padding: 0.75rem 1rem;
        border-radius: 20px;
        resize: none;
        font-size: 0.95rem;
        line-height: 1.4;
        outline: none;
        max-height: 120px;
        overflow-y: hidden;
        transition: all 0.2s ease;
    }
    

    .chat-input:focus {
        outline: none;
        box-shadow: none;
    }

    .input-btn {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        margin: 0 0.25rem;
        flex-shrink: 0;
    }

    .attach-btn {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        transition: all 0.2s ease;
    }

    .attach-btn:hover {
        background: linear-gradient(135deg, #4b5563 0%, #374151 100%);
        transform: scale(1.05);
    }

    .emoji-btn {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: white;
    }

    .send-btn {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        transition: all 0.2s ease;
    }

    .send-btn.disabled {
        background: #6c757d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .send-btn.sending {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .input-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .btn-pulse {
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    .dropdown-menu {
        border-radius: 15px;
        border: none;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
        backdrop-filter: blur(10px);
    }

    .emoji-picker {
        position: absolute !important;
        top: auto !important;
        bottom: 100% !important;
        transform: translateY(-10px);
        z-index: 9999;
        min-width: 250px;
        max-width: 300px;
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 0.25rem;
        padding: 0.75rem;
    }

    .emoji-item {
        font-size: 1.5rem;
        padding: 0.5rem;
        text-align: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
    }

    .emoji-item:hover {
        background: #f3f4f6;
        transform: scale(1.2);
    }

    /* Reaction buttons */
    .reaction-btn {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #e5e7eb;
        border-radius: 15px;
        padding: 0.25rem 0.75rem;
        margin: 0.25rem 0.25rem 0 0;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        backdrop-filter: blur(5px);
    }

    .reaction-btn:hover {
        background: #f3f4f6;
        transform: translateY(-1px);
    }

    .reaction-btn.reacted {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-color: #3b82f6;
        color: #1d4ed8;
    }

    /* Mobile responsiveness - Improved */
    @media (max-width: 768px) {
        body {
            padding-top: 60px !important;
        }
        
        .navbar {
            height: 60px !important;
        }
        
        .chat-container {
            height: calc(100vh - 60px);
            padding: 5px;
        }
        
        .row.h-100 {
            height: 100% !important;
        }
        
        .chat-sidebar {
            border-radius: 15px;
            margin-bottom: 10px;
        }
        
        .main-chat {
            border-radius: 15px;
        }
        
        .chat-header {
            border-radius: 15px 15px 0 0;
            padding: 1rem;
        }
        
        .chat-top-bar {
            border-radius: 15px 15px 0 0;
            padding: 1rem;
        }
        
        .message-bubble {
            max-width: 85%;
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
        }
        
        .user-info-card {
            margin: 0.5rem;
            padding: 1rem;
        }

        .messages-container {
            padding: 1rem;
        }

        .chat-input-area {
            padding: 1rem;
            border-radius: 0 0 15px 15px;
        }
        
        .input-group {
            padding: 0.25rem;
        }
        
        .input-btn {
            width: 40px;
            height: 40px;
            margin: 0 0.125rem;
        }

        .chat-input {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }
        
        /* Stack columns on mobile */
        .col-md-4, .col-md-8 {
            flex: none;
            width: 100%;
            max-width: 100%;
        }
        
        /* Hide sidebar on mobile when in conversation */
        .chat-sidebar {
            display: none;
        }
        
        /* Make main chat full width on mobile */
        .main-chat {
            width: 100%;
        }
        
        /* Show back button on mobile */
        .mobile-back-btn {
            display: inline-flex !important;
        }
    }

    /* Large screens optimization */
    @media (min-width: 1200px) {
        .chat-container {
            padding: 20px;
        }
        
        .message-bubble {
            max-width: 65%;
        }
        
        .messages-container {
            padding: 2rem;
        }
    }

    /* Hide mobile back button on desktop */
    .mobile-back-btn {
        display: none;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .message-item {
        animation: fadeInUp 0.3s ease;
    }

    /* Glass effect for modern look */
    .glass-effect {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    /* Group creation modal styles */
    .user-search-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    .selected-users .badge {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .btn-close-white {
        opacity: 0.8;
    }

    .btn-close-white:hover {
        opacity: 1;
    }

    /* Emoji picker dropdown positioning fix */
    .emoji-dropdown {
        position: relative;
    }
    
    .emoji-dropdown .dropdown-menu {
        position: absolute;
        bottom: calc(100% + 10px);
        right: 0;
        left: auto;
        transform: none;
        min-width: 300px;
        max-height: 250px;
        overflow-y: auto;
        z-index: 1050;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        padding: 1rem;
        margin: 0;
        display: none;
    }
    
    .emoji-dropdown .dropdown-menu.show {
        display: block !important;
    }
    
    .emoji-grid {
        display: grid;
        grid-template-columns: repeat(8, 1fr);
        gap: 0.25rem;
    }
    
    .emoji-item {
        padding: 0.5rem;
        border: none;
        background: transparent;
        border-radius: 6px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 36px;
        min-width: 36px;
    }
    
    .emoji-item:hover {
        background-color: #f3f4f6;
        transform: scale(1.1);
    }

    .emoji-item:active {
        background-color: #e5e7eb;
    }

    /* Send button animation */
    .btn-pulse {
        animation: pulse 1.5s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(13, 110, 253, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(13, 110, 253, 0);
        }
    }

    /* Modal improvements */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-bottom: 1px solid #e5e7eb;
        border-radius: 15px 15px 0 0;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .modal-footer {
        border-top: 1px solid #e5e7eb;
        border-radius: 0 0 15px 15px;
        background: #f8fafc;
    }

    .user-search-results {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        background: #fafbfc;
    }

    .user-search-item {
        transition: all 0.2s ease;
    }

    .user-search-item:hover {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }

    /* Modern Modal Styles */
    .modern-modal {
        background: linear-gradient(135deg, rgba(26, 26, 46, 0.98) 0%, rgba(22, 33, 62, 0.98) 50%, rgba(15, 15, 35, 0.98) 100%);
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 
            0 25px 50px -12px rgba(0, 0, 0, 0.7),
            0 0 0 1px rgba(255, 255, 255, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }

    .modern-modal-header {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 25px 30px;
        position: relative;
    }

    .modal-title-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .modal-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    }

    .modal-title-text .modal-title {
        color: white;
        font-size: 22px;
        font-weight: 600;
        margin: 0;
        line-height: 1.2;
    }

    .modal-subtitle {
        color: rgba(255, 255, 255, 0.7);
        font-size: 14px;
        margin: 0;
        display: block;
        margin-top: 2px;
    }

    .modern-btn-close {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        width: 35px;
        height: 35px;
        border-radius: 10px;
        color: rgba(255, 255, 255, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .modern-btn-close:hover {
        background: rgba(255, 59, 48, 0.2);
        color: #ff3b30;
        transform: scale(1.05);
    }

    .modern-modal-body {
        padding: 30px;
        background: transparent;
    }

    .form-section {
        height: 100%;
    }

    .modern-form-group {
        margin-bottom: 25px;
    }

    .modern-form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .modern-form-label i {
        width: 16px;
        color: rgba(102, 126, 234, 0.8);
    }

    .required {
        color: #ff6b6b;
    }

    .modern-form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        color: white;
        padding: 12px 16px;
        font-size: 14px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .modern-form-control:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(102, 126, 234, 0.5);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        color: white;
        outline: none;
    }

    .modern-form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .form-helper {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.6);
        margin-top: 5px;
    }

    /* File Upload Styling */
    .file-upload-wrapper {
        position: relative;
    }

    .file-input {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }

    .file-upload-display {
        background: rgba(255, 255, 255, 0.05);
        border: 2px dashed rgba(255, 255, 255, 0.2);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-display:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(102, 126, 234, 0.4);
    }

    .upload-icon {
        font-size: 24px;
        color: rgba(102, 126, 234, 0.8);
        margin-bottom: 8px;
    }

    .upload-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .upload-main {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
        font-size: 14px;
    }

    .upload-sub {
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
    }

    /* Member Search Styling */
    .member-search-wrapper {
        margin-bottom: 20px;
    }

    .search-input-container {
        position: relative;
    }

    .search-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(255, 255, 255, 0.5);
        font-size: 14px;
    }

    .modern-search-input {
        padding-left: 45px;
        padding-right: 45px;
    }

    .search-spinner {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: rgba(102, 126, 234, 0.8);
    }

    .live-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: rgba(26, 26, 46, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        margin-top: 5px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 1000;
        backdrop-filter: blur(20px);
    }

    .search-results-header {
        padding: 12px 16px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .results-count {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        font-weight: 500;
    }

    .search-results-list {
        max-height: 150px;
        overflow-y: auto;
    }

    .search-result-item {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .search-result-item:hover {
        background: rgba(102, 126, 234, 0.1);
    }

    .search-result-item:last-child {
        border-bottom: none;
    }

    .result-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 500;
        margin-right: 12px;
    }

    .result-info {
        flex: 1;
    }

    .result-name {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .result-email {
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
    }

    .add-member-btn {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgba(102, 126, 234, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(102, 126, 234, 0.8);
        font-size: 12px;
        transition: all 0.2s ease;
    }

    .add-member-btn:hover {
        background: rgba(102, 126, 234, 0.3);
        transform: scale(1.1);
    }

    /* Selected Members */
    .selected-members {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 16px;
        min-height: 120px;
    }

    .selected-members-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .selected-count {
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        font-weight: 500;
    }

    .clear-all-btn {
        background: rgba(255, 107, 107, 0.2);
        border: none;
        color: #ff6b6b;
        padding: 4px 8px;
        border-radius: 8px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .clear-all-btn:hover {
        background: rgba(255, 107, 107, 0.3);
    }

    .selected-members-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }

    .selected-member-tag {
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(102, 126, 234, 0.2);
        border: 1px solid rgba(102, 126, 234, 0.3);
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 12px;
    }

    .member-tag-avatar {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
        font-weight: 500;
    }

    .member-name {
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
    }

    .remove-member-btn {
        background: rgba(255, 107, 107, 0.2);
        border: none;
        color: #ff6b6b;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 10px;
        transition: all 0.2s ease;
    }

    .remove-member-btn:hover {
        background: rgba(255, 107, 107, 0.3);
        transform: scale(1.1);
    }

    .empty-members {
        text-align: center;
        color: rgba(255, 255, 255, 0.5);
        padding: 20px 0;
    }

    .empty-members i {
        font-size: 24px;
        margin-bottom: 8px;
        color: rgba(255, 255, 255, 0.3);
    }

    .empty-members p {
        margin: 8px 0 4px 0;
        font-weight: 500;
    }

    .empty-members small {
        font-size: 12px;
        color: rgba(255, 255, 255, 0.4);
    }

    /* Modal Footer */
    .modern-modal-footer {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 20px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .footer-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.6);
        font-size: 12px;
    }

    .footer-info i {
        color: rgba(102, 126, 234, 0.8);
    }

    .footer-actions {
        display: flex;
        gap: 12px;
    }

    .modern-btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.8);
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .modern-btn-secondary:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
        transform: translateY(-1px);
    }

    .modern-btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
    }

    .modern-btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    }

    .modern-btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }

    .btn-spinner {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    /* Responsive */
    @media (max-width: 992px) {
        .modern-modal-footer {
            padding: 15px 20px;
            flex-direction: column;
            gap: 12px;
        }
        
        .footer-actions {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (max-width: 768px) {
        .modal-dialog {
            margin: 5px;
        }
        
        .modern-modal-body .row {
            flex-direction: column;
        }
        
        .selected-members {
            min-height: 120px;
        }
    }
</style>

@endsection

@push('scripts')
<script>
    // Wait for jQuery to be available
    function waitForJQuery() {
        if (typeof jQuery !== 'undefined') {
            initChat();
        } else {
            setTimeout(waitForJQuery, 50);
        }
    }

    function initChat() {
        $(document).ready(function() {
            console.log('jQuery loaded and chat initialized');
            
            
            // --- SETUP ---
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        const conversationId = @json($conversation->id);
        const currentUserId = @json(auth()->id());
        let isTyping = false;
        let typingTimeout = null;
        let selectedFile = null;
        let currentPage = @json($messages->currentPage());
        let hasMoreMessages = @json($messages->hasMorePages());
        
        // Notification system variables
        let notificationsEnabled = false;
        let lastMessageId = 0;
        let documentHidden = false;
        let unreadCount = 0;
        let originalTitle = document.title;
        let titleInterval = null;
        
        // Auto-update system variables
        let updateInterval = null;
        let typingCheckInterval = null;
        let isPageActive = true;

        const messagesContainer = $('#messages-container');
        const messagesList = $('#messages-list');
        const messageInput = $('#message-input');
        const messageForm = $('#message-form');

        // Debug: Check if elements exist
        console.log('Elements found:', {
            messagesContainer: messagesContainer.length,
            messagesList: messagesList.length, 
            messageInput: messageInput.length,
            messageForm: messageForm.length,
            attachBtn: $('#attach-file-btn').length,
            fileInput: $('#file-input').length,
            emojiBtn: $('.emoji-btn').length,
            sendBtn: $('#send-message-btn').length
        });
        
        // Initialize notification system
        initNotifications();
        
        // Initialize auto-update system
        initAutoUpdate();
        
        // Page visibility detection
        initPageVisibility();
        
        // Initialize file upload display
        updateFileUploadDisplay();

        // --- NOTIFICATION SYSTEM ---
        
        function initNotifications() {
            // Request notification permission
            if ('Notification' in window) {
                if (Notification.permission === 'default') {
                    // Don't auto-request, let user click the button
                    updateNotificationUI();
                } else if (Notification.permission === 'granted') {
                    notificationsEnabled = true;
                    updateNotificationUI();
                    console.log('Notifications already enabled');
                } else {
                    // Permission denied
                    updateNotificationUI();
                }
            } else {
                // Browser doesn't support notifications
                $('#toggle-notifications-btn').hide();
            }
        }
        
        function showNotification(title, body, icon = null, autoClose = 5000) {
            if (!notificationsEnabled || !documentHidden) return;
            
            const notification = new Notification(title, {
                body: body,
                icon: icon || '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'chat-message',
                requireInteraction: false,
                silent: false
            });
            
            // Auto close notification
            if (autoClose > 0) {
                setTimeout(() => notification.close(), autoClose);
            }
            
            // Focus window when notification is clicked
            notification.onclick = function() {
                window.focus();
                notification.close();
            };
        }
        
        function updatePageTitle() {
            if (unreadCount > 0 && documentHidden) {
                document.title = `(${unreadCount}) ${originalTitle}`;
                
                // Blinking title effect
                if (!titleInterval) {
                    titleInterval = setInterval(() => {
                        document.title = document.title.startsWith('•') 
                            ? `(${unreadCount}) ${originalTitle}`
                            : `• (${unreadCount}) ${originalTitle}`;
                    }, 1000);
                }
            } else {
                document.title = originalTitle;
                if (titleInterval) {
                    clearInterval(titleInterval);
                    titleInterval = null;
                }
            }
        }
        
        // --- AUTO-UPDATE SYSTEM ---
        
        function initAutoUpdate() {
            // Start real-time polling
            startPolling();
            
            // Update when page becomes visible
            $(document).on('visibilitychange', function() {
                if (!document.hidden && unreadCount > 0) {
                    markAllAsRead();
                    loadNewMessages(true); // Force update
                }
            });
        }
        
        function startPolling() {
            // Stop any existing intervals
            if (updateInterval) clearInterval(updateInterval);
            if (typingCheckInterval) clearInterval(typingCheckInterval);
            
            // Start new message polling (every 2 seconds when active, 5 seconds when inactive)
            const messageInterval = isPageActive ? 2000 : 5000;
            updateInterval = setInterval(() => {
                loadNewMessages();
                updateOnlineStatus(); // Check online status
            }, messageInterval);
            
            // Start typing indicator polling (every 3 seconds)
            typingCheckInterval = setInterval(() => {
                checkTypingUsers();
            }, 3000);
            
            console.log(`Polling started: messages every ${messageInterval}ms, typing every 3000ms`);
        }
        
        function initPageVisibility() {
            // Handle page visibility changes
            document.addEventListener('visibilitychange', function() {
                documentHidden = document.hidden;
                isPageActive = !document.hidden;
                
                if (!documentHidden) {
                    unreadCount = 0;
                    updatePageTitle();
                    markAllAsRead();
                    // Restart polling with active intervals
                    startPolling();
                } else {
                    // Restart polling with inactive intervals
                    startPolling();
                }
                
                console.log('Page visibility changed:', documentHidden ? 'hidden' : 'visible');
            });
            
            // Handle window focus/blur
            $(window).on('focus', function() {
                isPageActive = true;
                documentHidden = false;
                unreadCount = 0;
                updatePageTitle();
                markAllAsRead();
                startPolling();
            }).on('blur', function() {
                isPageActive = false;
                documentHidden = true;
            });
        }
        
        function markAllAsRead() {
            $.post(`{{ route("chat.mark-read", $conversation->id) }}`)
                .done(function() {
                    console.log('Messages marked as read');
                })
                .fail(function() {
                    console.log('Failed to mark messages as read');
                });
        }
        
        // --- ONLINE STATUS SYSTEM ---
        
        function updateOnlineStatus() {
            // Update our own online status
            updateUserOnlineStatus();
            
            // Get conversation participants' online status
            checkParticipantsOnlineStatus();
        }
        
        function updateUserOnlineStatus() {
            // Send heartbeat to server to indicate we're online
            $.post('/api/user/heartbeat', {
                _token: '{{ csrf_token() }}',
                conversation_id: conversationId
            }).done(function() {
                console.log('Online status updated');
            }).fail(function(xhr) {
                // If route doesn't exist, log but don't show error
                if (xhr.status === 404) {
                    console.log('Heartbeat endpoint not implemented yet');
                } else {
                    console.log('Failed to update online status');
                }
            });
        }
        
        function checkParticipantsOnlineStatus() {
            // Check if other participants are online
            $.get(`/api/conversation/${conversationId}/participants/status`)
                .done(function(response) {
                    updateParticipantsUI(response.participants || []);
                })
                .fail(function(xhr) {
                    // If route doesn't exist, simulate with mock data for now
                    if (xhr.status === 404) {
                        console.log('Participants status endpoint not implemented yet');
                        simulateParticipantsStatus();
                    } else {
                        console.log('Failed to check participants status');
                    }
                });
        }
        
        function simulateParticipantsStatus() {
            // Mock data for demonstration - improved to match your screenshot
            const mockParticipants = [];
            
            // Add the other user in conversation (Phan Nhật Quân from screenshot)
            if (currentUserId !== 1) {
                mockParticipants.push({
                    id: 1,
                    name: 'Phan Nhật Quân',
                    avatar: '/images/default-avatar.png',
                    is_online: true, // Always online for demo
                    last_seen: new Date()
                });
            }
            
            // Add superadmin if current user is not superadmin
            if (currentUserId !== 2) {
                mockParticipants.push({
                    id: 2,
                    name: 'superadmin',
                    avatar: '/images/default-avatar.png',
                    is_online: true, // Online as shown in screenshot
                    last_seen: new Date()
                });
            }
            
            // Add User Demo (sometimes online/offline for variety)
            mockParticipants.push({
                id: 3,
                name: 'User Demo',
                avatar: '/images/default-avatar.png',
                is_online: Math.random() > 0.3, // 70% chance online
                last_seen: new Date(Date.now() - Math.random() * 3600000) // Random within last hour
            });
            
            console.log('Mock participants:', mockParticipants);
            updateParticipantsUI(mockParticipants);
        }
        
        function updateParticipantsUI(participants) {
            const onlineCount = participants.filter(p => p.is_online && p.id !== currentUserId).length;
            const totalParticipants = participants.filter(p => p.id !== currentUserId).length;
            
            // Update main online indicator and avatar
            const onlineStatusEl = $('#online-status');
            const mainOnlineIndicator = $('.user-info-card .online-status');
            
            if (onlineCount > 0) {
                onlineStatusEl.removeClass('text-muted').addClass('text-success')
                    .html('<i class="fas fa-circle me-1"></i>Online');
                mainOnlineIndicator.removeClass('offline').addClass('online').show();
            } else {
                onlineStatusEl.removeClass('text-success').addClass('text-muted')
                    .html('<i class="far fa-circle me-1"></i>Offline');
                mainOnlineIndicator.removeClass('online').addClass('offline').hide();
            }
            
            // Always show current user as online
            mainOnlineIndicator.removeClass('offline').addClass('online').show();
            onlineStatusEl.removeClass('text-muted').addClass('text-success')
                .html('<i class="fas fa-circle me-1"></i>Online');
            
            // Update online counter
            const onlineCountText = totalParticipants > 0
                ? `${onlineCount + 1}/${totalParticipants + 1} người đang trực tuyến` // +1 for current user
                : '1/1 người đang trực tuyến';
            $('#online-count').text(onlineCountText);
            
            // Update participant list
            updateParticipantsList(participants);
            
            console.log(`${onlineCount + 1}/${totalParticipants + 1} participants online (including current user)`);
        }
        
        function updateParticipantsList(participants) {
            // Update sidebar participants list
            const participantsList = $('#participants-list');
            if (participantsList.length === 0) return;
            
            participantsList.empty();
            
            if (participants.length === 0) {
                participantsList.html(`
                    <div class="text-center py-3">
                        <small class="text-muted">Không có thành viên nào</small>
                    </div>
                `);
                return;
            }
            
            participants.forEach(participant => {
                if (participant.id === currentUserId) return; // Skip current user
                
                const isOnline = participant.is_online;
                const lastSeen = participant.last_seen ? new Date(participant.last_seen) : null;
                const timeAgo = lastSeen ? getTimeAgo(lastSeen) : 'Không xác định';
                
                const participantHtml = `
                    <div class="participant-item d-flex align-items-center p-2 mb-2 rounded">
                        <div class="position-relative me-3">
                            <img src="${participant.avatar || '/images/default-avatar.png'}" 
                                 alt="${participant.name}" class="participant-avatar">
                            <span class="participant-status ${isOnline ? 'online' : 'offline'}"></span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="participant-name">${participant.name}</div>
                            <small class="participant-status-text ${isOnline ? 'text-success' : 'text-muted'}">
                                ${isOnline ? 'Online' : `Hoạt động ${timeAgo}`}
                            </small>
                        </div>
                    </div>
                `;
                
                participantsList.append(participantHtml);
            });
            
            // If no participants to show
            if (participantsList.children().length === 0) {
                participantsList.html(`
                    <div class="text-center py-3">
                        <small class="text-muted">Chỉ có bạn trong cuộc trò chuyện</small>
                    </div>
                `);
            }
        }
        
        function getTimeAgo(date) {
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMins / 60);
            const diffDays = Math.floor(diffHours / 24);
            
            if (diffMins < 1) return 'vừa xong';
            if (diffMins < 60) return `${diffMins} phút trước`;
            if (diffHours < 24) return `${diffHours} giờ trước`;
            if (diffDays < 7) return `${diffDays} ngày trước`;
            return date.toLocaleDateString('vi-VN');
        }
        
        function playNotificationSound() {
            // Create and play a subtle notification sound
            try {
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
                
                gainNode.gain.setValueAtTime(0, audioContext.currentTime);
                gainNode.gain.linearRampToValueAtTime(0.1, audioContext.currentTime + 0.01);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.3);
                
            } catch (e) {
                console.log('Could not play notification sound:', e);
            }
        }
        
        function toggleNotifications() {
            if (!('Notification' in window)) {
                showModernNotification('Không hỗ trợ', 'Trình duyệt của bạn không hỗ trợ thông báo', 'warning');
                return;
            }
            
            if (Notification.permission === 'granted') {
                // Toggle notification state
                notificationsEnabled = !notificationsEnabled;
                updateNotificationUI();
                
                if (notificationsEnabled) {
                    showNotification('Thông báo đã bật', 'Bạn sẽ nhận được thông báo tin nhắn mới', null, 3000);
                }
            } else if (Notification.permission === 'default') {
                // Request permission
                Notification.requestPermission().then(function(permission) {
                    if (permission === 'granted') {
                        notificationsEnabled = true;
                        updateNotificationUI();
                        showNotification('Thông báo đã bật', 'Bạn sẽ nhận được thông báo tin nhắn mới', null, 3000);
                    } else {
                        showModernNotification('Cần cấp quyền', 'Vui lòng cho phép thông báo trong cài đặt trình duyệt', 'info');
                    }
                });
            } else {
                showModernNotification('Thông báo bị chặn', 'Vui lòng cho phép thông báo trong cài đặt trình duyệt và tải lại trang', 'warning');
            }
        }
        
        function updateNotificationUI() {
            const btn = $('#toggle-notifications-btn');
            const icon = btn.find('i');
            const text = $('#notification-text');
            
            if (notificationsEnabled) {
                icon.removeClass('fa-bell').addClass('fa-bell-slash').removeClass('text-primary').addClass('text-danger');
                text.text('Tắt thông báo');
            } else {
                icon.removeClass('fa-bell-slash').addClass('fa-bell').removeClass('text-danger').addClass('text-primary');
                text.text('Bật thông báo');
            }
        }
        
        // --- MODERN NOTIFICATION SYSTEM ---
        
        function showModernNotification(title, message, type = 'info', duration = 5000) {
            // Create notification container if doesn't exist
            if (!$('#modern-notifications').length) {
                $('body').append(`
                    <div id="modern-notifications" class="modern-notifications-container"></div>
                `);
            }
            
            const notificationId = 'notif_' + Date.now();
            const iconMap = {
                'success': 'fas fa-check-circle',
                'error': 'fas fa-exclamation-circle', 
                'warning': 'fas fa-exclamation-triangle',
                'info': 'fas fa-info-circle'
            };
            
            const notification = $(`
                <div id="${notificationId}" class="modern-notification modern-notification-${type}">
                    <div class="notification-icon">
                        <i class="${iconMap[type]}"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">${title}</div>
                        <div class="notification-message">${message}</div>
                    </div>
                    <button class="notification-close" onclick="closeNotification('${notificationId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);
            
            $('#modern-notifications').append(notification);
            
            // Animate in
            setTimeout(() => {
                notification.addClass('show');
            }, 100);
            
            // Auto remove
            if (duration > 0) {
                setTimeout(() => {
                    closeNotification(notificationId);
                }, duration);
            }
            
            return notificationId;
        }
        
        function closeNotification(notificationId) {
            const notification = $('#' + notificationId);
            notification.removeClass('show').addClass('hide');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }
        
        function showModernConfirm(title, message, confirmText = 'Xác nhận', cancelText = 'Hủy', onConfirm = null, onCancel = null) {
            const confirmId = 'confirm_' + Date.now();
            
            const confirmModal = $(`
                <div id="${confirmId}" class="modern-confirm-overlay">
                    <div class="modern-confirm-modal">
                        <div class="modern-confirm-header">
                            <div class="modern-confirm-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h4 class="modern-confirm-title">${title}</h4>
                        </div>
                        <div class="modern-confirm-body">
                            <p class="modern-confirm-message">${message}</p>
                        </div>
                        <div class="modern-confirm-footer">
                            <button class="modern-btn modern-btn-secondary cancel-btn">${cancelText}</button>
                            <button class="modern-btn modern-btn-danger confirm-btn">${confirmText}</button>
                        </div>
                    </div>
                </div>
            `);
            
            $('body').append(confirmModal);
            
            // Animate in
            setTimeout(() => {
                confirmModal.addClass('show');
            }, 50);
            
            // Event handlers
            confirmModal.find('.confirm-btn').on('click', function() {
                closeConfirm();
                if (onConfirm) onConfirm();
            });
            
            confirmModal.find('.cancel-btn').on('click', function() {
                closeConfirm();
                if (onCancel) onCancel();
            });
            
            // Close on overlay click
            confirmModal.on('click', function(e) {
                if (e.target === confirmModal[0]) {
                    closeConfirm();
                    if (onCancel) onCancel();
                }
            });
            
            function closeConfirm() {
                confirmModal.removeClass('show');
                setTimeout(() => {
                    confirmModal.remove();
                }, 300);
            }
        }
        
        function scrollToBottom() {
            const container = messagesContainer[0];
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }
        
        // Initialize and scroll to bottom - delay to ensure DOM is ready
        setTimeout(function() {
            scrollToBottom();
            
            // Initialize online status checking immediately
            console.log('Initializing online status...');
            updateOnlineStatus();
            
            // Force show current user as online initially
            $('.user-info-card .online-status').removeClass('offline').addClass('online').show();
            $('#online-status').removeClass('text-muted').addClass('text-success')
                .html('<i class="fas fa-circle me-1"></i>Online');
            
            // Test all functionality after DOM is ready
            console.log('Testing button functionality:');
            console.log('- Attach button exists:', $('#attach-file-btn').length > 0);
            console.log('- File input exists:', $('#file-input').length > 0);
            console.log('- Emoji button exists:', $('.emoji-btn').length > 0);
            console.log('- Send button exists:', $('#send-message-btn').length > 0);
            console.log('- Message input exists:', $('#message-input').length > 0);
            console.log('- Online status elements:', $('.online-status').length);
            console.log('- Participants list:', $('#participants-list').length);
            
            // Test click handlers
            $('#attach-file-btn').off('click').on('click', function() {
                console.log('Direct attach button click test passed');
            });
            
        }, 100);

        // --- EVENT LISTENERS ---

        // Submit message form
        $(document).on('submit', '#message-form', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            sendMessage();
        });
        
        // Send button click event
        $(document).on('click', '#send-message-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Send button clicked');
            sendMessage();
        });

        // Submit on Enter, new line on Shift+Enter
        console.log('Setting up keydown event handler for message input');
        $(document).on('keydown', '#message-input', function(e) {
            console.log('Key pressed:', e.key, 'Shift:', e.shiftKey); // Debug log
            if (e.key === 'Enter') {
                if (e.shiftKey) {
                    // Shift + Enter: Allow new line (default behavior)
                    console.log('Shift+Enter: Allowing new line');
                    return;
                } else {
                    // Enter only: Send message
                    console.log('Enter: Sending message');
                    e.preventDefault();
                    e.stopPropagation();
                    sendMessage();
                }
            }
        });

        // Also try direct event binding as backup
        if (messageInput.length > 0) {
            console.log('Setting up direct keydown event handler');
            messageInput.on('keydown', function(e) {
                console.log('Direct handler - Key pressed:', e.key, 'Shift:', e.shiftKey);
                if (e.key === 'Enter' && !e.shiftKey) {
                    console.log('Direct handler - Enter: Sending message');
                    e.preventDefault();
                    e.stopPropagation();
                    sendMessage();
                }
            });
        }

        // Auto-resize textarea and handle typing indicator
        messageInput.on('input', function() {
            // Reset height to auto to get the correct scrollHeight
            this.style.height = 'auto';
            
            // Set height to scrollHeight, but limit max height
            const maxHeight = 120; // Maximum height in pixels
            const newHeight = Math.min(this.scrollHeight, maxHeight);
            this.style.height = newHeight + 'px';
            
            // Show scrollbar if content exceeds max height
            this.style.overflowY = this.scrollHeight > maxHeight ? 'auto' : 'hidden';
            
            handleTypingIndicator();
            
            // Add subtle animation to send button when there's text
            const sendBtn = $('.send-btn');
            if ($(this).val().trim().length > 0) {
                sendBtn.addClass('btn-pulse').removeClass('disabled');
            } else {
                sendBtn.removeClass('btn-pulse').addClass('disabled');
            }
        });

        // Attach file button - Fixed
        $(document).on('click', '#attach-file-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Attach file button clicked');
            document.getElementById('file-input').click();
        });

        // File input change - Fixed  
        $(document).on('change', '#file-input', function(e) {
            console.log('File input changed', e.target.files);
            const file = e.target.files[0];
            if (file) {
                console.log('File selected:', file.name, 'Size:', file.size);
                if (file.size > 10 * 1024 * 1024) { // 10MB limit
                    showModernNotification('File quá lớn', 'Kích thước file không được vượt quá 10MB', 'error');
                    $(this).val('');
                    return;
                }
                selectedFile = file;
                $('#file-name').text(file.name);
                $('#file-preview').fadeIn();
                showModernNotification('File đã chọn', `Đã chọn file: ${file.name}`, 'success', 3000);
                console.log('File preview shown');
            } else {
                console.log('No file selected');
            }
        });

        // Remove selected file
        $(document).on('click', '#remove-file-btn', function(e) {
            e.preventDefault();
            console.log('Remove file button clicked');
            removeFile();
        });

        // Emoji picker - Fixed positioning and events
        $(document).on('click', '.emoji-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Emoji button clicked');
            const dropdown = $(this).siblings('.dropdown-menu');
            const isVisible = dropdown.hasClass('show');
            
            // Close all dropdowns first
            $('.emoji-dropdown .dropdown-menu').removeClass('show');
            
            // Toggle current dropdown
            if (!isVisible) {
                dropdown.addClass('show');
                console.log('Emoji picker opened');
            } else {
                console.log('Emoji picker closed');
            }
        });
        
        // Close dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.emoji-dropdown').length) {
                $('.emoji-dropdown .dropdown-menu').removeClass('show');
                console.log('Emoji picker closed by outside click');
            }
        });

        // Emoji selection - Fixed
        $(document).on('click', '.emoji-item', function(e) {
            e.preventDefault();
            console.log('Emoji clicked:', $(this).data('emoji'));
            const emoji = $(this).data('emoji');
            const currentVal = messageInput.val();
            const cursorPos = messageInput[0].selectionStart;
            const newVal = currentVal.substring(0, cursorPos) + emoji + currentVal.substring(cursorPos);
            messageInput.val(newVal);
            messageInput.focus();
            messageInput[0].selectionStart = messageInput[0].selectionEnd = cursorPos + emoji.length;
            messageInput.trigger('input');
            // Keep picker open; it will only close when user clicks the emoji icon again or outside
        });

        // Load more messages
        $('#load-more-messages button').on('click', loadMoreMessages);

        // Delegate reaction button clicks (currently disabled)
        messagesList.on('click', '.reaction-btn', function() {
            // TODO: Implement reaction functionality
            return false; // Prevent default action since buttons are disabled
            // const messageId = $(this).closest('.message-item').data('message-id');
            // const emoji = $(this).data('emoji');
            // toggleReaction(messageId, emoji);
        });

        // Leave Conversation buttons
        $('#leave-conversation-btn-sidebar, #leave-conversation-btn-dropdown').on('click', leaveConversation);

        // Clear Chat button
        $('#clear-chat-btn').on('click', clearChat);

        // Notification toggle button
        $('#toggle-notifications-btn').on('click', function(e) {
            e.preventDefault();
            toggleNotifications();
        });

        // Removed refresh online status per requirements

        // Delete Conversation button: delete directly, no confirm modal
        $('#delete-conversation-btn').on('click', function(e) {
            e.preventDefault();
            deleteConversation();
        });

        // Removed confirm delete modal hook


        // --- CORE FUNCTIONS ---

        function sendMessage() {
            const content = messageInput.val().trim();
            
            // Don't send empty messages without file
            if (!content && !selectedFile) {
                return;
            }

            // Disable form elements during sending
            messageInput.prop('disabled', true);
            $('.send-btn').prop('disabled', true).addClass('disabled');
            $('#attach-file-btn').prop('disabled', true);

            // Stop typing indicator immediately
            if (isTyping) {
                isTyping = false;
                updateTypingStatus(false);
                clearTimeout(typingTimeout);
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('content', content);
            formData.append('conversation_id', '{{ $conversation->id }}');
            
            if (selectedFile) {
                formData.append('attachment', selectedFile);
            }

            $.ajax({
                url: `{{ route("chat.send", $conversation->id) }}`,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success || response.status === 'success') {
                        // Clear form and reset textarea
                        messageInput.val('').trigger('input');
                        messageInput.css('height', 'auto');
                        messageInput.css('overflowY', 'hidden');
                        messageInput.focus(); // Keep focus on input
                        removeFile();
                        
                        // Reset notification count since user is actively chatting
                        unreadCount = 0;
                        updatePageTitle();
                        
                        // Add new message if provided
                        if (response.message) {
                            appendMessage(response.message);
                            scrollToBottom();
                        }
                        
                        markAsRead();
                    } else {
                        showModernNotification('Lỗi gửi tin nhắn', response.message || 'Không thể gửi tin nhắn', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Send message error:', {xhr, status, error});
                    let errorMsg = 'Không thể gửi tin nhắn';
                    
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 422) {
                        errorMsg = 'Dữ liệu không hợp lệ';
                    } else if (xhr.status === 413) {
                        errorMsg = 'File quá lớn';
                    }
                    
                    showModernNotification('Lỗi gửi tin nhắn', errorMsg, 'error');
                },
                complete: function() {
                    // Re-enable form elements
                    messageInput.prop('disabled', false).focus();
                    $('.send-btn').prop('disabled', false).removeClass('disabled');
                    $('#attach-file-btn').prop('disabled', false);
                }
            });
        }

        function loadMoreMessages() {
            if (!hasMoreMessages) return;

            const loadMoreButton = $('#load-more-messages');
            loadMoreButton.find('button').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang tải...');

            const oldScrollHeight = messagesContainer[0].scrollHeight;

            $.ajax({
                url: `{{ route("chat.messages", $conversation->id) }}`,
                data: {
                    page: currentPage + 1
                },
                success: function(response) {
                    if (response && response.data) {
                        currentPage = response.current_page || currentPage + 1;
                        hasMoreMessages = response.next_page_url !== null;

                        const messagesHtml = response.data.map(msg => createMessageHtml(msg)).join('');
                        messagesList.prepend(messagesHtml);

                        // Restore scroll position to avoid jumping
                        const newScrollHeight = messagesContainer[0].scrollHeight;
                        messagesContainer.scrollTop(newScrollHeight - oldScrollHeight);

                        if (!hasMoreMessages) {
                            loadMoreButton.hide();
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Error loading more messages:', xhr);
                    showModernNotification('Lỗi tải tin nhắn', 'Không thể tải tin nhắn cũ hơn', 'error');
                },
                complete: function() {
                    loadMoreButton.find('button').prop('disabled', false).html('<i class="fas fa-chevron-up me-1"></i> Tải tin nhắn cũ hơn');
                }
            });
        }

        function toggleReaction(messageId, emoji) {
            // TODO: Implement toggle reaction route and controller method
            showModernNotification('Chức năng chưa sẵn sàng', 'Tính năng reaction hiện chưa được triển khai', 'info');
            return; // Prevent execution until properly implemented

            // This code is commented out until the route is properly implemented
            /*
            $.ajax({
                url: '/chat/message/' + messageId + '/react',
                method: 'POST',
                data: { emoji: emoji },
                success: function(response) {
                    if (response.success) {
                        // Update the message's reactions without reloading the page
                        updateMessageReactions(messageId, response.reactions);
                    }
                },
                error: function() {
                    showModernNotification('Lỗi cập nhật', 'Không thể cập nhật reaction', 'error');
                }
            });
            */
        }

        // --- REAL-TIME POLLING (SHOULD BE REPLACED WITH WEBSOCKETS) ---
        // NOTE: This polling is inefficient for production. Use Laravel Echo & WebSockets.
        // Example with Echo:
        // Echo.private(`chat.${conversationId}`)
        //     .listen('MessageSent', (e) => {
        //         if ($(`[data-message-id="${e.message.id}"]`).length === 0) {
        //             appendMessage(e.message);
        //             scrollToBottom();
        //         }
        //     })
        //     .listenForWhisper('typing', (e) => {
        //         // Handle typing indicator...
        //     });

        // Old polling is now handled by the new auto-update system
        // setInterval(loadNewMessages, 3000);
        // setInterval(checkTypingUsers, 2000);

        function loadNewMessages(forceUpdate = false) {
            const lastMessageId = messagesList.children().last().data('message-id') || 0;

            $.ajax({
                url: `{{ route("chat.messages", $conversation->id) }}`,
                data: {
                    after_id: lastMessageId
                },
                success: function(response) {
                    // Handle different response formats
                    let messages = response.data || response;
                    if (Array.isArray(messages) && messages.length > 0) {
                        const wasScrolledToBottom = isScrolledToBottom();
                        
                        messages.forEach(function(message) {
                            // Only show notification for messages from other users
                            if (message.sender && message.sender.id !== currentUserId) {
                                // Show desktop notification if page is hidden
                                if (documentHidden) {
                                    const senderName = message.sender.name || 'Someone';
                                    const messagePreview = message.content 
                                        ? (message.content.length > 50 ? message.content.substring(0, 50) + '...' : message.content)
                                        : (message.attachment_name ? `📎 ${message.attachment_name}` : 'Gửi một tệp đính kèm');
                                    
                                    showNotification(
                                        `Tin nhắn mới từ ${senderName}`,
                                        messagePreview,
                                        message.sender.avatar || '/images/default-avatar.png',
                                        8000
                                    );
                                    
                                    // Increase unread count and update title
                                    unreadCount++;
                                    updatePageTitle();
                                }
                                
                                // Play notification sound (optional)
                                playNotificationSound();
                            }
                            
                            appendMessage(message);
                        });
                        
                        // Auto-scroll to bottom if user was already at bottom
                        if (wasScrolledToBottom || forceUpdate) {
                            scrollToBottom();
                        }
                        
                        console.log(`${messages.length} new message(s) loaded`);
                    }
                },
                error: function(xhr) {
                    // Only log error if it's not a normal "no new messages" response
                    if (xhr.status !== 404) {
                        console.error('Error loading new messages:', xhr.responseJSON?.message || 'Unknown error');
                    }
                }
            });
        }

        function checkTypingUsers() {
            $.ajax({
                url: `{{ route("chat.typing-users", $conversation->id) }}`,
                success: function(response) {
                    const typingIndicator = $('#typing-indicator');
                    if (response.typing_users && response.typing_users.length > 0) {
                        const names = response.typing_users.map(user => user.name).join(', ');
                        $('#typing-users').text(names);
                        typingIndicator.show();
                    } else {
                        typingIndicator.hide();
                    }
                },
                error: function(xhr) {
                    // Silently handle typing user check errors
                    if (xhr.status !== 404) {
                        console.error('Error checking typing users:', xhr.responseJSON?.message || 'Unknown error');
                    }
                }
            });
        }

        // --- HELPER & UTILITY FUNCTIONS ---

        function createMessageHtml(messageData) {
            const isOwn = messageData.sender.id === currentUserId;
            const alignClass = isOwn ? 'justify-content-end' : 'justify-content-start';
            const bubbleClass = isOwn ? 'own' : 'other';
            const avatar = messageData.sender.avatar || '/images/default-avatar.png';

            let attachmentHtml = '';
            if (messageData.attachment_url) {
                const isImage = messageData.type === 'image' || /\.(jpg|jpeg|png|gif|bmp|svg|webp)$/i.test(messageData.attachment_name || '');
                attachmentHtml = isImage ?
                    `<div class="attachment-preview mb-2">
                        <img src="${messageData.attachment_url}" alt="Hình ảnh" class="img-fluid rounded-3" 
                             style="max-width: 250px; cursor: pointer; border-radius: 12px;"
                             onclick="window.open('${messageData.attachment_url}', '_blank')"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display:none; padding: 10px; background: rgba(255,0,0,0.1); border-radius: 8px;">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            <span class="text-danger">Không thể tải ảnh</span>
                        </div>
                     </div>` :
                    `<div class="attachment-file p-3 rounded-3 mb-2" style="background: rgba(0,0,0,0.05);">
                        <a href="${messageData.attachment_url}" target="_blank" class="text-decoration-none d-flex align-items-center text-primary">
                            <i class="fas fa-file-alt fs-4 me-3"></i>
                            <div>
                                <div class="fw-medium">${messageData.attachment_name || 'Tệp đính kèm'}</div>
                                <small class="opacity-75">File đính kèm</small>
                            </div>
                        </a>
                     </div>`;
            }

            // Reaction buttons (disabled for now)
            const allEmojis = ['👍', '❤️', '😂', '😮', '😢'];
            let reactionsHtml = '<div class="message-reactions mt-2">';
            allEmojis.forEach(emoji => {
                const count = messageData.reactions?.[emoji]?.length || 0;
                const reactedClass = messageData.reactions?.[emoji]?.includes(currentUserId) ? 'reacted' : '';
                reactionsHtml += `
                    <button class="reaction-btn ${reactedClass}" data-emoji="${emoji}" disabled 
                            title="Tính năng reaction sẽ được triển khai sau">
                        ${emoji} ${count > 0 ? count : ''}
                    </button>`;
            });
            reactionsHtml += '</div>';

            return `
<div class="d-flex ${alignClass} mb-4 message-item" data-message-id="${messageData.id}">
    ${!isOwn ? `<img src="${avatar}" alt="${messageData.sender.name}" class="message-avatar me-3 align-self-end">` : ''}
    <div class="message-bubble ${bubbleClass}">
        ${!isOwn ? `<div class="fw-bold text-primary small mb-1">${messageData.sender.name}</div>` : ''}
        ${attachmentHtml}
        <div class="message-content">${messageData.content || ''}</div>
        <div class="message-time mt-2">
            <small class="opacity-75">
                ${messageData.formatted_time}
                ${messageData.is_edited ? '<span class="fst-italic">(đã chỉnh sửa)</span>' : ''}
            </small>
        </div>
        ${reactionsHtml}
    </div>
    ${isOwn ? `<img src="${avatar}" alt="${messageData.sender.name}" class="message-avatar ms-3 align-self-end">` : ''}
</div>`;
        }

        function updateMessageReactions(messageId, reactions) {
            const messageElement = $(`[data-message-id="${messageId}"]`);
            if (!messageElement.length) return;

            const allEmojis = ['👍', '❤️', '😂', '😮', '😢'];
            let newReactionsHtml = '<div class="message-reactions">';
            allEmojis.forEach(emoji => {
                const count = reactions?.[emoji]?.length || 0;
                const reactedClass = reactions?.[emoji]?.includes(currentUserId) ? 'reacted' : '';
                newReactionsHtml += `
        <button class="btn btn-sm reaction-btn ${reactedClass}" data-emoji="${emoji}">
            ${emoji} ${count > 0 ? count : ''}
        </button>`;
            });
            newReactionsHtml += '</div>';

            messageElement.find('.message-reactions').replaceWith(newReactionsHtml);
        }

        function appendMessage(messageData) {
            if ($(`[data-message-id="${messageData.id}"]`).length === 0) {
                messagesList.append(createMessageHtml(messageData));
            }
        }

        function removeFile() {
            selectedFile = null;
            $('#file-input').val('');
            $('#file-preview').fadeOut();
            console.log('File removed');
        }

        function scrollToBottom() {
            messagesContainer.scrollTop(messagesContainer[0].scrollHeight);
        }

        function isScrolledToBottom() {
            const threshold = 50; // pixels
            const container = messagesContainer[0];
            return container.scrollHeight - container.scrollTop - container.clientHeight < threshold;
        }

        function handleTypingIndicator() {
            const message = messageInput.val().trim();
            if (message.length > 0 && !isTyping) {
                isTyping = true;
                updateTypingStatus(true);
            } else if (message.length === 0 && isTyping) {
                isTyping = false;
                updateTypingStatus(false);
            }
            clearTimeout(typingTimeout);
            typingTimeout = setTimeout(() => {
                if (isTyping) {
                    isTyping = false;
                    updateTypingStatus(false);
                }
            }, 3000);
        }

        function updateTypingStatus(typing) {
            $.post(`{{ route("chat.typing", $conversation->id) }}`, {
                is_typing: typing
            });
        }

        function markAsRead() {
            $.post(`{{ route("chat.mark-read", $conversation->id) }}`);
        }

        function leaveConversation() {
            showModernConfirm(
                'Rời khỏi cuộc trò chuyện',
                'Bạn có chắc chắn muốn rời khỏi cuộc trò chuyện này?',
                'Rời khỏi',
                'Hủy',
                function() {
                    $.ajax({
                        url: `{{ route("chat.leave", $conversation->id) }}`,
                        method: 'DELETE',
                        success: function(response) {
                            if (response.success) {
                                window.location.href = '{{ route("chat.index") }}';
                            }
                        }
                    });
                }
            );
        }

        function clearChat() {
            showModernConfirm(
                'Xóa lịch sử chat',
                'Bạn có chắc chắn muốn xóa lịch sử chat này? Hành động này không thể hoàn tác.',
                'Xóa',
                'Hủy',
                function() {
                    $.ajax({
                        url: `{{ route("chat.clear-history", $conversation->id) }}`,
                        method: 'POST',
                    data: { _token: $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        if (response.success) {
                            messagesList.empty();
                            showModernNotification('Thành công', response.message, 'success');
                            // Reload page to show system message
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        showModernNotification('Lỗi xóa lịch sử', xhr.responseJSON?.error || 'Không thể xóa lịch sử chat', 'error');
                    }
                });
                }
            );
        }

        function deleteConversation() {
            $.ajax({
                url: `{{ route("chat.delete", $conversation->id) }}`,
                method: 'DELETE',
                data: { _token: $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    if (response.success) {
                        window.location.href = response.redirect_url;
                    }
                },
                error: function(xhr) {
                    showModernNotification('Lỗi xóa cuộc trò chuyện', xhr.responseJSON?.error || 'Không thể xóa cuộc trò chuyện', 'error');
                }
            });
        }

        function showSendingIndicator() {
            const sendBtn = $('.send-btn');
            sendBtn.find('i').removeClass('fa-paper-plane').addClass('fa-spinner fa-spin');
            sendBtn.addClass('sending');
        }

        function hideSendingIndicator() {
            const sendBtn = $('.send-btn');
            sendBtn.find('i').removeClass('fa-spinner fa-spin').addClass('fa-paper-plane');
            sendBtn.removeClass('sending');
        }

        // Modern Group Chat Modal JavaScript
        let selectedUserIds = [];
        let groupSearchTimeout = null;
        let isSearching = false;

        // Initialize modal
        function initializeGroupModal() {
            resetModalState();
            checkFormValidity();
        }

        // Reset modal state
        function resetModalState() {
            selectedUserIds = [];
            $('#groupName').val('');
            $('#groupDescription').val('');
            $('#groupAvatar').val('');
            $('#userSearch').val('');
            $('#liveSearchResults').hide();
            $('#selectedMembersList').empty();
            $('#emptyMembers').show();
            $('#selectedCount').text('Đã chọn: 0 thành viên');
            $('#clearAllBtn').hide();
            updateFileUploadDisplay();
            checkFormValidity();
        }

        // File upload display update
        function updateFileUploadDisplay() {
            const fileInput = $('#groupAvatar');
            const display = $('.file-upload-display');
            
            fileInput.on('change', function() {
                const file = this.files[0];
                if (file) {
                    $('.upload-main').text(file.name);
                    $('.upload-sub').text(`${(file.size / 1024 / 1024).toFixed(2)} MB`);
                    display.addClass('file-selected');
                } else {
                    $('.upload-main').text('Chọn ảnh đại diện');
                    $('.upload-sub').text('JPG, PNG tối đa 2MB');
                    display.removeClass('file-selected');
                }
            });
        }

        // Form validation
        function checkFormValidity() {
            const groupName = $('#groupName').val().trim();
            const createBtn = $('#createGroupBtn');
            
            if (groupName.length >= 2) {
                createBtn.prop('disabled', false);
            } else {
                createBtn.prop('disabled', true);
            }
        }

        // Progressive search functionality
        function performSearch(query) {
            if (query.length < 2) {
                $('#liveSearchResults').hide();
                return;
            }

            isSearching = true;
            $('#searchSpinner').show();

            clearTimeout(groupSearchTimeout);
            groupSearchTimeout = setTimeout(() => {
                $.ajax({
                    url: '{{ route("chat.search-users") }}',
                    type: 'GET',
                    data: { q: query },
                    success: function(response) {
                        displaySearchResults(response.users, query);
                        isSearching = false;
                        $('#searchSpinner').hide();
                    },
                    error: function() {
                        $('#liveSearchResults').hide();
                        isSearching = false;
                        $('#searchSpinner').hide();
                    }
                });
            }, 300);
        }

        // Display search results
        function displaySearchResults(users, query) {
            const resultsContainer = $('#liveSearchResults');
            const resultsList = $('#searchResultsList');
            const resultsCount = $('#resultsCount');

            // Filter out already selected users
            const availableUsers = users.filter(user => !selectedUserIds.includes(user.id));
            
            if (availableUsers.length === 0) {
                resultsContainer.hide();
                return;
            }

            // Update results count
            resultsCount.text(`${availableUsers.length} kết quả`);
            
            // Clear previous results
            resultsList.empty();

            // Add search results
            availableUsers.forEach(user => {
                const userItem = createSearchResultItem(user, query);
                resultsList.append(userItem);
            });

            resultsContainer.show();
        }

        // Create search result item
        function createSearchResultItem(user, query) {
            const highlightedName = highlightSearchQuery(user.name, query);
            const highlightedEmail = highlightSearchQuery(user.email, query);
            const initials = getInitials(user.name);

            const item = $(`
                <div class="search-result-item" data-user-id="${user.id}">
                    <div class="result-avatar">${initials}</div>
                    <div class="result-info">
                        <div class="result-name">${highlightedName}</div>
                        <div class="result-email">${highlightedEmail}</div>
                    </div>
                    <div class="add-member-btn">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            `);

            item.on('click', () => addUserToGroup(user));
            return item;
        }

        // Highlight search query in text
        function highlightSearchQuery(text, query) {
            if (!query) return text;
            const regex = new RegExp(`(${query})`, 'gi');
            return text.replace(regex, '<mark style="background: rgba(102, 126, 234, 0.3); color: white; padding: 1px 3px; border-radius: 3px;">$1</mark>');
        }

        // Get initials from name
        function getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
        }

        // Add user to group
        function addUserToGroup(user) {
            if (selectedUserIds.includes(user.id)) return;

            selectedUserIds.push(user.id);
            
            // Hide empty state
            $('#emptyMembers').hide();
            
            // Create member tag
            const memberTag = createSelectedMemberTag(user);
            $('#selectedMembersList').append(memberTag);
            
            // Update counter
            updateSelectedCount();
            
            // Remove from search results
            $(`.search-result-item[data-user-id="${user.id}"]`).remove();
            
            // Update results count
            const remainingCount = $('#searchResultsList .search-result-item').length;
            $('#resultsCount').text(`${remainingCount} kết quả`);
            
            if (remainingCount === 0) {
                $('#liveSearchResults').hide();
            }

            // Show clear all button if needed
            if (selectedUserIds.length > 1) {
                $('#clearAllBtn').show();
            }
        }

        // Create selected member tag
        function createSelectedMemberTag(user) {
            const initials = getInitials(user.name);
            
            const tag = $(`
                <div class="selected-member-tag" data-user-id="${user.id}">
                    <div class="member-tag-avatar">${initials}</div>
                    <span class="member-name">${user.name}</span>
                    <button type="button" class="remove-member-btn">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `);

            tag.find('.remove-member-btn').on('click', (e) => {
                e.stopPropagation();
                removeUserFromGroup(user.id);
            });

            return tag;
        }

        // Remove user from group
        function removeUserFromGroup(userId) {
            selectedUserIds = selectedUserIds.filter(id => id !== userId);
            $(`.selected-member-tag[data-user-id="${userId}"]`).remove();
            
            updateSelectedCount();
            
            // Show empty state if no members
            if (selectedUserIds.length === 0) {
                $('#emptyMembers').show();
                $('#clearAllBtn').hide();
            } else if (selectedUserIds.length <= 1) {
                $('#clearAllBtn').hide();
            }

            // Refresh search if there's a query
            const currentQuery = $('#userSearch').val().trim();
            if (currentQuery.length >= 2) {
                performSearch(currentQuery);
            }
        }

        // Update selected count
        function updateSelectedCount() {
            $('#selectedCount').text(`Đã chọn: ${selectedUserIds.length} thành viên`);
        }

        // Clear all selected members
        function clearAllMembers() {
            selectedUserIds = [];
            $('#selectedMembersList').empty();
            $('#emptyMembers').show();
            $('#clearAllBtn').hide();
            updateSelectedCount();

            // Refresh search
            const currentQuery = $('#userSearch').val().trim();
            if (currentQuery.length >= 2) {
                performSearch(currentQuery);
            }
        }

        // Create group functionality
        function createGroup() {
            const createBtn = $('#createGroupBtn');
            const formData = new FormData();
            
            // Show loading state
            createBtn.prop('disabled', true);
            createBtn.find('span').hide();
            createBtn.find('.btn-spinner').show();

            // Prepare form data
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('name', $('#groupName').val().trim());
            formData.append('description', $('#groupDescription').val().trim());
            
            const avatarFile = $('#groupAvatar')[0].files[0];
            if (avatarFile) {
                formData.append('avatar', avatarFile);
            }
            
            // Add selected members
            selectedUserIds.forEach(userId => {
                formData.append('user_ids[]', userId);
            });

            // Submit form
            $.ajax({
                url: '{{ route("chat.create-group") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('#createGroupModal').modal('hide');
                        
                        // Show success message
                        showModernNotification('Thành công', 'Tạo nhóm chat thành công!', 'success');
                        
                        // Redirect to new group
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 1500);
                    } else {
                        showModernNotification('Lỗi tạo nhóm', response.message || 'Có lỗi xảy ra khi tạo nhóm', 'error');
                        resetCreateButton();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.error || 'Có lỗi xảy ra khi tạo nhóm';
                    showModernNotification('Lỗi tạo nhóm', message, 'error');
                    resetCreateButton();
                }
            });
        }

        // Reset create button state
        function resetCreateButton() {
            const createBtn = $('#createGroupBtn');
            createBtn.prop('disabled', false);
            createBtn.find('span').show();
            createBtn.find('.btn-spinner').hide();
            checkFormValidity();
        }

        let searchTimeout;
        function searchUsers() {
            const query = $('#userSearch').val().trim();
            
            if (query.length < 2) {
                $('#userSearchResults').empty();
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $.ajax({
                    url: `{{ route("chat.search-users") }}`,
                    data: { q: query },
                    success: function(response) {
                        displayUserSearchResults(response.users);
                    }
                });
            }, 300);
        }

        function displayUserSearchResults(users) {
            const results = $('#userSearchResults');
            results.empty();

            users.forEach(user => {
                if (selectedUserIds.includes(user.id)) return; // Skip already selected

                const userItem = $(`
                    <div class="user-search-item d-flex align-items-center p-2 border rounded mb-2" 
                         style="cursor: pointer;" data-user-id="${user.id}">
                        <img src="${user.avatar}" alt="${user.name}" 
                             class="rounded-circle me-2" width="32" height="32">
                        <div class="flex-grow-1">
                            <div class="fw-medium">${user.name}</div>
                            <small class="text-muted">${user.email}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                `);

                userItem.on('click', () => addUserToGroup(user));
                results.append(userItem);
            });
        }

        function addUserToGroup(user) {
            if (selectedUserIds.includes(user.id)) return;

            selectedUserIds.push(user.id);
            
            const selectedContainer = $('#selectedUsers');
            if (selectedUserIds.length === 1) {
                selectedContainer.empty(); // Remove "Chưa chọn thành viên nào"
            }

            const userTag = $(`
                <span class="badge bg-primary me-2 mb-2" data-user-id="${user.id}">
                    ${user.name}
                    <button type="button" class="btn-close btn-close-white ms-1" style="font-size: 0.7em;"></button>
                </span>
            `);

            userTag.find('.btn-close').on('click', () => removeUserFromGroup(user.id));
            selectedContainer.append(userTag);

            // Remove from search results
            $(`.user-search-item[data-user-id="${user.id}"]`).remove();
        }

        function removeUserFromGroup(userId) {
            selectedUserIds = selectedUserIds.filter(id => id !== userId);
            $(`.badge[data-user-id="${userId}"]`).remove();

            if (selectedUserIds.length === 0) {
                $('#selectedUsers').html('<small class="text-muted">Chưa chọn thành viên nào</small>');
            }
        }

        function clearChatHistory() {
            // TODO: Implement clear chat route and controller method
            showModernNotification('Chức năng chưa sẵn sàng', 'Tính năng xóa lịch sử chat hiện chưa được triển khai', 'info');
            // if (confirm('Bạn có chắc chắn muốn xóa toàn bộ lịch sử cuộc trò chuyện này không? Hành động này không thể hoàn tác.')) {
            //     // Lưu ý: Bạn cần tạo route và controller cho hành động này
            //     $.ajax({
            //         url: `{{ url('/chat/conversation/' . $conversation->id . '/clear') }}`, // Temporary URL until route is implemented
            //         method: 'DELETE',
            //         success: function(response) {
            //             if (response.success) {
            //                 messagesList.empty();
            //                 alert('Lịch sử trò chuyện đã được xóa.');
            //             }
            //         },
            //         error: function() {
            //             alert('Không thể xóa lịch sử trò chuyện.');
            //         }
            //     });
            // }
        }

        }); // End of $(document).ready()
    } // End of initChat function

    // Start initialization
    waitForJQuery();
</script>

<!-- Modern Create Group Modal -->
<div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content modern-modal">
            <div class="modal-header modern-modal-header">
                <div class="modal-title-wrapper">
                    <div class="modal-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="modal-title-text">
                        <h5 class="modal-title" id="createGroupModalLabel">Tạo nhóm chat mới</h5>
                        <small class="modal-subtitle">Kết nối với bạn bè trong một không gian riêng</small>
                    </div>
                </div>
                <button type="button" class="btn-close modern-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body modern-modal-body">
                <form id="createGroupForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="form-section">
                                <div class="form-group modern-form-group">
                                    <label for="groupName" class="modern-form-label">
                                        <i class="fas fa-tag"></i>
                                        Tên nhóm <span class="required">*</span>
                                    </label>
                                    <input type="text" class="form-control modern-form-control" id="groupName" name="name" required 
                                           placeholder="VD: Team Gaming, Study Group...">
                                    <div class="form-helper">Tên nhóm sẽ hiển thị cho tất cả thành viên</div>
                                </div>

                                <div class="form-group modern-form-group">
                                    <label for="groupDescription" class="modern-form-label">
                                        <i class="fas fa-align-left"></i>
                                        Mô tả nhóm
                                    </label>
                                    <textarea class="form-control modern-form-control" id="groupDescription" name="description" rows="3"
                                              placeholder="Viết vài dòng về nhóm này..."></textarea>
                                    <div class="form-helper">Mô tả ngắn gọn về mục đích của nhóm</div>
                                </div>

                                <div class="form-group modern-form-group">
                                    <label for="groupAvatar" class="modern-form-label">
                                        <i class="fas fa-image"></i>
                                        Avatar nhóm
                                    </label>
                                    <div class="file-upload-wrapper">
                                        <input type="file" class="file-input" id="groupAvatar" name="avatar" accept="image/*">
                                        <div class="file-upload-display">
                                            <div class="upload-icon">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <div class="upload-text">
                                                <span class="upload-main">Chọn ảnh đại diện</span>
                                                <span class="upload-sub">JPG, PNG tối đa 2MB</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="form-section">
                                <div class="form-group modern-form-group">
                                    <label class="modern-form-label">
                                        <i class="fas fa-user-friends"></i>
                                        Thành viên nhóm
                                    </label>
                                    
                                    <!-- Search Input -->
                                    <div class="member-search-wrapper">
                                        <div class="search-input-container">
                                            <i class="fas fa-search search-icon"></i>
                                            <input type="text" class="form-control modern-search-input" id="userSearch" 
                                                   placeholder="Tìm kiếm theo tên hoặc email..." autocomplete="off">
                                            <div class="search-spinner" id="searchSpinner" style="display: none;">
                                                <i class="fas fa-spinner fa-spin"></i>
                                            </div>
                                        </div>
                                        
                                        <!-- Live Search Results -->
                                        <div class="live-search-results" id="liveSearchResults" style="display: none;">
                                            <div class="search-results-header">
                                                <span class="results-count" id="resultsCount">0 kết quả</span>
                                            </div>
                                            <div class="search-results-list" id="searchResultsList">
                                                <!-- Dynamic results will be inserted here -->
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Selected Members -->
                                    <div class="selected-members" id="selectedMembers">
                                        <div class="selected-members-header">
                                            <span class="selected-count" id="selectedCount">Đã chọn: 0 thành viên</span>
                                            <button type="button" class="clear-all-btn" id="clearAllBtn" style="display: none;">
                                                <i class="fas fa-times"></i> Xóa tất cả
                                            </button>
                                        </div>
                                        <div class="selected-members-list" id="selectedMembersList">
                                            <!-- Selected members will appear here -->
                                        </div>
                                        <div class="empty-members" id="emptyMembers">
                                            <i class="fas fa-users"></i>
                                            <p>Chưa chọn thành viên nào</p>
                                            <small>Tìm kiếm và thêm bạn bè vào nhóm</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer modern-modal-footer">
                <div class="footer-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Bạn có thể thêm thành viên sau khi tạo nhóm</span>
                </div>
                <div class="footer-actions">
                    <button type="button" class="btn modern-btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                        <span>Hủy</span>
                    </button>
                    <button type="button" class="btn modern-btn-primary" id="createGroupBtn" disabled>
                        <i class="fas fa-plus"></i>
                        <span>Tạo nhóm</span>
                        <div class="btn-spinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
<div class="modal fade" id="deleteConversationModal" tabindex="-1" aria-labelledby="deleteConversationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteConversationModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác!
                </div>
                <p>Bạn có chắc chắn muốn xóa cuộc trò chuyện này?</p>
                <ul class="text-muted">
                    <li>Tất cả tin nhắn sẽ bị xóa vĩnh viễn</li>
                    <li>Không thể khôi phục lại dữ liệu</li>
                    <li>Tất cả thành viên sẽ bị loại khỏi cuộc trò chuyện</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Xóa vĩnh viễn
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Event listeners for modern group modal
    $(document).ready(function() {
        $('#createGroupModal').on('shown.bs.modal', function() {
            initializeGroupModal();
        });
        
        // Form validation
        $('#groupName').on('input', function() {
            checkFormValidity();
        });
        
        // Search functionality
        $('#userSearch').on('input', function() {
            const query = $(this).val().trim();
            performSearch(query);
        });

        // Hide search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.member-search-wrapper').length) {
                $('#liveSearchResults').hide();
            }
        });

        // Clear all members
        $('#clearAllBtn').on('click', function() {
            clearAllMembers();
        });
        
        // Create group
        $('#createGroupBtn').on('click', function() {
            createGroup();
        });
        
        // Initialize file upload display
        // updateFileUploadDisplay(); // Moved inside initChat function
    });
</script>
@endpush