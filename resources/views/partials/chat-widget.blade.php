{{-- Chat Widget - Facebook Messenger Style --}}
{{-- Ẩn widget khi đang ở trang chat conversation để không che nút gửi --}}
@auth
@if(!request()->is('chat/conversation/*'))
<div id="chat-widget" class="chat-widget">
    {{-- Chat Toggle Button --}}
    <button id="chat-toggle" class="chat-toggle-btn" title="Tin nhắn">
        <i class="fas fa-comment-dots"></i>
        <span id="chat-unread-badge" class="chat-unread-badge" style="display: none;">0</span>
    </button>

    {{-- Chat Panel --}}
    <div id="chat-panel" class="chat-panel" style="display: none;">
        {{-- Panel Header --}}
        <div class="chat-panel-header">
            <div class="chat-panel-title">
                <i class="fas fa-comments"></i>
                <span>Tin nhắn</span>
            </div>
            <div class="chat-panel-actions">
                <button class="chat-action-btn" onclick="openFullChat()" title="Mở rộng">
                    <i class="fas fa-expand-alt"></i>
                </button>
                <button class="chat-action-btn" onclick="toggleChatPanel()" title="Đóng">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        {{-- Search Box --}}
        <div class="chat-search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="chat-search-input" placeholder="Tìm kiếm cuộc trò chuyện...">
        </div>

        {{-- Conversations List --}}
        <div id="chat-conversations" class="chat-conversations">
            <div class="chat-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Đang tải...</span>
            </div>
        </div>

        {{-- New Chat Button --}}
        <div class="chat-new-btn-wrapper">
            <button class="chat-new-btn" onclick="openNewChat()">
                <i class="fas fa-edit"></i>
                Tin nhắn mới
            </button>
        </div>
    </div>

    {{-- Active Chat Windows (Mini popups) --}}
    <div id="chat-windows" class="chat-windows"></div>
</div>

<style>
/* Chat Widget Container */
.chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
    font-family: 'Inter', sans-serif;
}

/* Toggle Button */
.chat-toggle-btn {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 30px rgba(99, 102, 241, 0.6);
}

.chat-toggle-btn.active {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
}

/* Unread Badge */
.chat-unread-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    min-width: 20px;
    height: 20px;
    background: #ef4444;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 6px;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
}

/* Chat Panel */
.chat-panel {
    position: absolute;
    bottom: 70px;
    right: 0;
    width: 360px;
    max-height: 500px;
    background: linear-gradient(145deg, #0d1b2a, #000022);
    border: 1px solid rgba(0, 229, 255, 0.2);
    border-radius: 16px;
    box-shadow: 0 10px 50px rgba(0, 0, 0, 0.5);
    overflow: hidden;
    display: flex;
    flex-direction: column;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Panel Header */
.chat-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.1));
    border-bottom: 1px solid rgba(0, 229, 255, 0.1);
}

.chat-panel-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #fff;
    font-weight: 600;
    font-size: 1.1rem;
}

.chat-panel-title i {
    color: #00E5FF;
}

.chat-panel-actions {
    display: flex;
    gap: 0.5rem;
}

.chat-action-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #94a3b8;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.chat-action-btn:hover {
    background: rgba(0, 229, 255, 0.2);
    color: #00E5FF;
}

/* Search Box */
.chat-search-box {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: rgba(0, 0, 20, 0.3);
}

.chat-search-box i {
    color: #64748b;
}

.chat-search-box input {
    flex: 1;
    background: none;
    border: none;
    color: #fff;
    font-size: 0.9rem;
    outline: none;
}

.chat-search-box input::placeholder {
    color: #64748b;
}

/* Conversations List */
.chat-conversations {
    flex: 1;
    overflow-y: auto;
    max-height: 350px;
}

.chat-conversations::-webkit-scrollbar {
    width: 6px;
}

.chat-conversations::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.2);
}

.chat-conversations::-webkit-scrollbar-thumb {
    background: rgba(0, 229, 255, 0.3);
    border-radius: 3px;
}

/* Conversation Item */
.chat-conv-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    cursor: pointer;
    transition: all 0.2s;
    border-bottom: 1px solid rgba(0, 229, 255, 0.05);
}

.chat-conv-item:hover {
    background: rgba(0, 229, 255, 0.08);
}

.chat-conv-item.unread {
    background: rgba(99, 102, 241, 0.1);
}

.chat-conv-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e3a5f, #0d1b2a);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    flex-shrink: 0;
}

.chat-conv-avatar img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.chat-conv-avatar .online-dot {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: #22c55e;
    border: 2px solid #0d1b2a;
    border-radius: 50%;
}

.chat-conv-info {
    flex: 1;
    min-width: 0;
}

.chat-conv-name {
    color: #fff;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-conv-preview {
    color: #64748b;
    font-size: 0.85rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.chat-conv-item.unread .chat-conv-preview {
    color: #94a3b8;
    font-weight: 500;
}

.chat-conv-meta {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 0.25rem;
}

.chat-conv-time {
    color: #64748b;
    font-size: 0.75rem;
}

.chat-conv-unread-dot {
    width: 10px;
    height: 10px;
    background: #6366f1;
    border-radius: 50%;
}

/* Loading State */
.chat-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    color: #64748b;
    gap: 0.75rem;
}

/* Empty State */
.chat-empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 3rem 2rem;
    text-align: center;
}

.chat-empty i {
    font-size: 3rem;
    color: #64748b;
    margin-bottom: 1rem;
}

.chat-empty p {
    color: #94a3b8;
    font-size: 0.9rem;
}

/* New Chat Button */
.chat-new-btn-wrapper {
    padding: 0.75rem 1rem;
    border-top: 1px solid rgba(0, 229, 255, 0.1);
}

.chat-new-btn {
    width: 100%;
    padding: 0.75rem;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: all 0.3s;
}

.chat-new-btn:hover {
    box-shadow: 0 4px 20px rgba(99, 102, 241, 0.4);
    transform: translateY(-2px);
}

/* Chat Windows Container */
.chat-windows {
    position: fixed;
    bottom: 20px;
    right: 90px;
    display: flex;
    flex-direction: row-reverse;
    gap: 10px;
    z-index: 9998;
}

/* Mini Chat Window */
.chat-mini-window {
    width: 328px;
    height: 420px;
    background: linear-gradient(145deg, #0d1b2a, #000022);
    border: 1px solid rgba(0, 229, 255, 0.2);
    border-radius: 12px;
    box-shadow: 0 8px 40px rgba(0, 0, 0, 0.4);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

.chat-mini-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(139, 92, 246, 0.1));
    border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    cursor: pointer;
}

.chat-mini-header:hover {
    background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(139, 92, 246, 0.15));
}

.chat-mini-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

.chat-mini-name {
    flex: 1;
    color: #fff;
    font-weight: 600;
    font-size: 0.9rem;
}

.chat-mini-close {
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    color: #94a3b8;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.chat-mini-close:hover {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.chat-mini-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.chat-mini-input-wrapper {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border-top: 1px solid rgba(0, 229, 255, 0.1);
    background: rgba(0, 0, 20, 0.3);
}

.chat-mini-input {
    flex: 1;
    background: rgba(0, 0, 40, 0.5);
    border: 1px solid rgba(0, 229, 255, 0.2);
    border-radius: 20px;
    padding: 0.5rem 1rem;
    color: #fff;
    font-size: 0.9rem;
    outline: none;
}

.chat-mini-input:focus {
    border-color: #00E5FF;
}

.chat-mini-send {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.chat-mini-send:hover {
    transform: scale(1.1);
}

/* Message Bubbles */
.chat-msg {
    max-width: 80%;
    padding: 0.5rem 0.875rem;
    border-radius: 18px;
    font-size: 0.9rem;
    line-height: 1.4;
}

.chat-msg.sent {
    align-self: flex-end;
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: white;
    border-bottom-right-radius: 4px;
}

.chat-msg.received {
    align-self: flex-start;
    background: rgba(0, 229, 255, 0.1);
    color: #e2e8f0;
    border-bottom-left-radius: 4px;
}

/* Responsive */
@media (max-width: 768px) {
    .chat-panel {
        width: calc(100vw - 40px);
        right: -10px;
        max-height: 70vh;
    }
    
    .chat-windows {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatToggle = document.getElementById('chat-toggle');
    const chatPanel = document.getElementById('chat-panel');
    const chatConversations = document.getElementById('chat-conversations');
    const chatSearchInput = document.getElementById('chat-search-input');
    const chatUnreadBadge = document.getElementById('chat-unread-badge');
    const chatWindows = document.getElementById('chat-windows');
    
    let conversations = [];
    let openChats = [];
    let totalUnread = 0;

    // Helper function to escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    window.escapeHtml = escapeHtml;

    // Toggle chat panel
    chatToggle.addEventListener('click', function() {
        toggleChatPanel();
    });

    window.toggleChatPanel = function() {
        const isVisible = chatPanel.style.display !== 'none';
        
        // If opening panel, close all chat windows first
        if (isVisible) {
            chatPanel.style.display = 'none';
            chatToggle.classList.remove('active');
        } else {
            // Close all open chat windows when opening panel
            openChats.forEach(convId => {
                const chatWindow = document.querySelector(`[data-conv-id="${convId}"]`);
                if (chatWindow) chatWindow.remove();
            });
            openChats = [];
            
            chatPanel.style.display = 'flex';
            chatToggle.classList.add('active');
            loadConversations();
        }
    };

    // Load conversations
    function loadConversations() {
        chatConversations.innerHTML = `
            <div class="chat-loading">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Đang tải...</span>
            </div>
        `;

        fetch('/chat/conversations', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                conversations = data.conversations || [];
                totalUnread = data.unread_count || 0;
                updateUnreadBadge();
                renderConversations();
            }
        })
        .catch(err => {
            chatConversations.innerHTML = `
                <div class="chat-empty">
                    <i class="fas fa-exclamation-circle"></i>
                    <p>Không thể tải tin nhắn</p>
                </div>
            `;
        });
    }

    // Render conversations
    function renderConversations(filter = '') {
        if (conversations.length === 0) {
            chatConversations.innerHTML = `
                <div class="chat-empty">
                    <i class="fas fa-comments"></i>
                    <p>Chưa có cuộc trò chuyện nào</p>
                </div>
            `;
            return;
        }

        const filtered = filter 
            ? conversations.filter(c => c.name.toLowerCase().includes(filter.toLowerCase()))
            : conversations;

        if (filtered.length === 0) {
            chatConversations.innerHTML = `
                <div class="chat-empty">
                    <i class="fas fa-search"></i>
                    <p>Không tìm thấy kết quả</p>
                </div>
            `;
            return;
        }

        chatConversations.innerHTML = filtered.map(conv => `
            <div class="chat-conv-item ${conv.unread ? 'unread' : ''}" onclick="openChatWindow('${conv.id}', '${escapeHtml(conv.name)}', '${conv.avatar || ''}')">
                <div class="chat-conv-avatar">
                    ${conv.avatar 
                        ? `<img src="${conv.avatar}" alt="${escapeHtml(conv.name)}">`
                        : `<i class="fas fa-user" style="color: #64748b;"></i>`
                    }
                    ${conv.online ? '<span class="online-dot"></span>' : ''}
                </div>
                <div class="chat-conv-info">
                    <div class="chat-conv-name">${escapeHtml(conv.name)}</div>
                    <div class="chat-conv-preview">${escapeHtml(conv.last_message || 'Bắt đầu cuộc trò chuyện')}</div>
                </div>
                <div class="chat-conv-meta">
                    <span class="chat-conv-time">${conv.time || ''}</span>
                    ${conv.unread ? '<span class="chat-conv-unread-dot"></span>' : ''}
                </div>
            </div>
        `).join('');
    }

    // Update unread badge
    function updateUnreadBadge() {
        if (totalUnread > 0) {
            chatUnreadBadge.textContent = totalUnread > 99 ? '99+' : totalUnread;
            chatUnreadBadge.style.display = 'flex';
        } else {
            chatUnreadBadge.style.display = 'none';
        }
    }

    // Search conversations
    chatSearchInput.addEventListener('input', function() {
        renderConversations(this.value);
    });

    // Open chat window
    window.openChatWindow = function(convId, name, avatar) {
        // Check if already open
        if (openChats.includes(convId)) {
            return;
        }

        // Limit to 3 open chats
        if (openChats.length >= 3) {
            const oldestId = openChats.shift();
            const oldWindow = document.querySelector(`[data-conv-id="${oldestId}"]`);
            if (oldWindow) oldWindow.remove();
        }

        openChats.push(convId);

        const chatWindow = document.createElement('div');
        chatWindow.className = 'chat-mini-window';
        chatWindow.setAttribute('data-conv-id', convId);
        chatWindow.innerHTML = `
            <div class="chat-mini-header" onclick="window.location.href='/chat/conversation/${convId}'">
                ${avatar 
                    ? `<img src="${avatar}" class="chat-mini-avatar" alt="${escapeHtml(name)}">`
                    : `<div class="chat-mini-avatar" style="background: linear-gradient(135deg, #1e3a5f, #0d1b2a); display: flex; align-items: center; justify-content: center;"><i class="fas fa-user" style="color: #64748b;"></i></div>`
                }
                <span class="chat-mini-name">${escapeHtml(name)}</span>
                <button class="chat-mini-close" onclick="event.stopPropagation(); closeChatWindow('${convId}')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="chat-mini-messages" id="chat-messages-${convId}">
                <div class="chat-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
            <div class="chat-mini-input-wrapper">
                <input type="text" class="chat-mini-input" placeholder="Aa" id="chat-input-${convId}" onkeypress="if(event.key==='Enter') sendMiniMessage('${convId}')">
                <button class="chat-mini-send" onclick="sendMiniMessage('${convId}')">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        `;

        chatWindows.appendChild(chatWindow);
        loadChatMessages(convId);

        // Close panel
        chatPanel.style.display = 'none';
        chatToggle.classList.remove('active');
    };

    // Close chat window
    window.closeChatWindow = function(convId) {
        const index = openChats.indexOf(convId);
        if (index > -1) {
            openChats.splice(index, 1);
        }
        const chatWindow = document.querySelector(`[data-conv-id="${convId}"]`);
        if (chatWindow) {
            chatWindow.remove();
        }
    };

    // Load chat messages
    function loadChatMessages(convId) {
        const messagesContainer = document.getElementById(`chat-messages-${convId}`);
        
        fetch(`/chat/${convId}/messages?limit=20`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => {
            if (!r.ok) {
                throw new Error(`HTTP ${r.status}`);
            }
            return r.json();
        })
        .then(data => {
            if (data.success && data.messages) {
                if (data.messages.length === 0) {
                    messagesContainer.innerHTML = '<div style="text-align: center; color: #64748b; padding: 1rem;">Bắt đầu cuộc trò chuyện</div>';
                } else {
                    messagesContainer.innerHTML = data.messages.map(msg => `
                        <div class="chat-msg ${msg.is_mine ? 'sent' : 'received'}">
                            ${escapeHtml(msg.content)}
                        </div>
                    `).join('');
                }
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            } else {
                messagesContainer.innerHTML = '<div style="text-align: center; color: #ef4444; padding: 1rem;">Không thể tải tin nhắn</div>';
            }
        })
        .catch(err => {
            messagesContainer.innerHTML = '<div style="text-align: center; color: #ef4444; padding: 1rem;">Lỗi tải tin nhắn</div>';
        });
    }

    // Send message from mini window
    window.sendMiniMessage = function(convId) {
        const input = document.getElementById(`chat-input-${convId}`);
        const message = input.value.trim();
        
        if (!message) return;

        const messagesContainer = document.getElementById(`chat-messages-${convId}`);
        
        // Add message to UI immediately (escaped)
        messagesContainer.innerHTML += `
            <div class="chat-msg sent">${escapeHtml(message)}</div>
        `;
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        input.value = '';

        // Send to server
        fetch(`/chat/${convId}/send`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ message: message })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                // Remove the optimistic message if failed
                const lastMsg = messagesContainer.querySelector('.chat-msg.sent:last-child');
                if (lastMsg) lastMsg.remove();
            }
        })
        .catch(err => {
            // Remove the optimistic message if failed
            const lastMsg = messagesContainer.querySelector('.chat-msg.sent:last-child');
            if (lastMsg) lastMsg.remove();
        });
    };

    // Open full chat page
    window.openFullChat = function() {
        window.location.href = '/chat';
    };

    // Open new chat
    window.openNewChat = function() {
        window.location.href = '/chat?new=1';
    };

    // Listen for new messages notification via user channel
    if (typeof Echo !== 'undefined') {
        Echo.private(`user.{{ Auth::id() }}`)
            .listen('.chat.message', (e) => {
                // Update unread count
                totalUnread++;
                updateUnreadBadge();
                
                // Update conversation list if panel is open
                if (chatPanel.style.display !== 'none') {
                    loadConversations();
                }
                
                // Update mini window if open (using conversation_id)
                // Find the mini window by checking all open chats
                openChats.forEach(convSlug => {
                    const messagesContainer = document.getElementById(`chat-messages-${convSlug}`);
                    if (messagesContainer && e.sender_id !== {{ Auth::id() }}) {
                        // Reload messages for this conversation
                        loadChatMessages(convSlug);
                    }
                });
                
                // Show browser notification if permitted
                if (Notification.permission === 'granted' && e.sender_id !== {{ Auth::id() }}) {
                    new Notification('Tin nhắn mới', {
                        body: `${e.sender_name}: ${e.content}`,
                        icon: e.sender_avatar || '/logo_remove_bg.png'
                    });
                }
            });
    }

    // Initial load of unread count
    fetch('/chat/unread-count', {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.count !== undefined) {
            totalUnread = data.count;
            updateUnreadBadge();
        }
    })
    .catch(() => {});
});
</script>
@endif
@endauth
