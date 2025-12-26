@extends('layouts.app')

@section('title', 'Global Chat')

@push('styles')
<style>
    /* Remove default main padding for chat page */
    body:has(.chat-index-container) main {
        padding: 0 !important;
    }
    
    /* Alternative method if :has() not supported */
    .chat-page main {
        padding: 0 !important;
    }
    
    /* Allow scrolling to see footer */
    .chat-page {
        height: auto;
        min-height: 100vh;
    }
    
    .chat-page main {
        min-height: calc(100vh - 76px); /* navbar height */
    }
    
    .chat-page .chat-index-container {
        min-height: calc(100vh - 76px);
    }
</style>
@endpush

@section('content')
<div class="chat-index-container">
    <div class="row h-100 g-0">
        <!-- Modern Chat Sidebar -->
        <div class="col-md-4 col-lg-3 chat-sidebar-modern h-100 p-0">
            <div class="d-flex flex-column h-100">
                <!-- Gradient Header -->
                <div class="chat-header-modern">
                    <div class="header-content">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-comments me-2"></i>Global Chat
                        </h5>
                        <small class="opacity-90">Kết nối mọi lúc, mọi nơi</small>
                    </div>
                    <div class="header-overlay"></div>
                </div>

                <!-- Modern Action Section -->
                <div class="action-section">
                    <div class="d-grid mb-3">
                        <button class="create-group-btn" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                            <i class="fas fa-users me-2"></i>Tạo nhóm chat
                            <i class="fas fa-plus ms-auto"></i>
                        </button>
                    </div>
                    
                    <div class="search-container">
                        <div class="search-input-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" id="user-search" class="search-input" placeholder="Tìm kiếm người dùng...">
                            <button class="search-btn" type="button" id="search-btn">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                        <!-- Search Results -->
                        <div id="search-results" class="search-results" style="display: none;"></div>
                    </div>
                </div>

                <!-- Modern Conversations List -->
                <div class="conversations-container">
                    <div class="conversations-header">
                        <span class="section-title">Cuộc trò chuyện</span>
                        <span class="conversation-count">{{ $conversations->count() }}</span>
                    </div>
                    
                    <div class="conversations-list">
                        @forelse($conversations as $conversation)
                        <a href="{{ route('chat.show', $conversation) }}"
                            class="conversation-item {{ request()->route('conversation')?->id == $conversation->id ? 'active' : '' }}">
                            <div class="conversation-avatar">
                                <img src="{{ $conversation->getDisplayAvatar(auth()->id()) }}"
                                    alt="Avatar" class="avatar-img">
                            </div>
                            <div class="conversation-content">
                                <div class="conversation-header">
                                    <h6 class="conversation-name">{{ $conversation->getDisplayName(auth()->id()) }}</h6>
                                    <small class="conversation-time">{{ $conversation->last_message_at?->diffForHumans() ?? '' }}</small>
                                </div>
                                <p class="conversation-preview">{{ $conversation->last_message_preview ?? 'Chưa có tin nhắn nào' }}</p>
                            </div>
                            @if($conversation->getUnreadCount(auth()->id()) > 0)
                            <div class="unread-badge">
                                {{ $conversation->getUnreadCount(auth()->id()) }}
                            </div>
                            @endif
                        </a>
                        @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-comments"></i>
                            </div>
                            <h6 class="empty-title">Chưa có cuộc trò chuyện</h6>
                            <p class="empty-description">Tìm kiếm người dùng để bắt đầu trò chuyện!</p>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                                <i class="fas fa-plus me-1"></i>Tạo nhóm đầu tiên
                            </button>
                        </div>
                        @endforelse
                    </div>
                </div>

                
            </div>
        </div>

        <!-- Modern Main Chat Area -->
        <div class="col-md-8 col-lg-9 p-0">
            <div class="main-content-area">
                <div class="welcome-container">
                    <div class="welcome-icon">
                        <div class="icon-wrapper">
                            <i class="fas fa-comments"></i>
                            <div class="icon-pulse"></div>
                        </div>
                    </div>
                    <div class="welcome-content">
                        <h2 class="welcome-title">Chào mừng đến Global Chat</h2>
                        <p class="welcome-description">Chọn cuộc trò chuyện để bắt đầu nhắn tin hoặc tìm kiếm người dùng để tạo chat mới.</p>
                        <div class="welcome-actions">
                            <button class="action-btn primary" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                                <i class="fas fa-users"></i>
                                <span>Tạo nhóm chat</span>
                            </button>
                            <button class="action-btn secondary" onclick="document.getElementById('user-search').focus()">
                                <i class="fas fa-search"></i>
                                <span>Tìm kiếm user</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Background Animation -->
                <div class="background-animation">
                    <div class="floating-bubble" style="--delay: 0s; --size: 60px;"></div>
                    <div class="floating-bubble" style="--delay: 2s; --size: 80px;"></div>
                    <div class="floating-bubble" style="--delay: 4s; --size: 40px;"></div>
                    <div class="floating-bubble" style="--delay: 6s; --size: 100px;"></div>
                    <div class="floating-bubble" style="--delay: 8s; --size: 50px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .search-result-item {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .search-result-item:hover {
        background-color: #e9ecef;
        transform: translateX(2px);
    }
</style>

@push('scripts')
<script>
    // Wait for jQuery to be available
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded for chat index');
            return;
        }
        
        // Simple search function
        function performUserSearch(query) {
            console.log('Searching for:', query);
            $('#search-results').html('<div class="p-2">Đang tìm kiếm...</div>').show();
            
            $.ajax({
                url: '{{ route("chat.search-users") }}',
                method: 'GET',
                data: { q: query },
                success: function(response) {
                    console.log('Search success:', response);
                    if (response.users && response.users.length > 0) {
                        let html = '';
                        response.users.forEach(user => {
                            html += `
                            <div class="search-result-item p-2 d-flex align-items-center" data-user-id="${user.id}" data-user-name="${user.name}" style="cursor: pointer; border-bottom: 1px solid #eee;">
                                <img src="${user.avatar}" alt="Avatar" class="rounded-circle me-2" width="30" height="30">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">${user.name}</div>
                                    <small class="text-muted">${user.email} • ${user.user_role}</small>
                                </div>
                            </div>
                            `;
                        });
                        $('#search-results').html(html).show();
                    } else {
                        $('#search-results').html('<div class="p-2 text-muted">Không tìm thấy người dùng nào</div>').show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Search error:', xhr, status, error);
                    $('#search-results').html('<div class="p-2 text-danger">Lỗi tìm kiếm. Vui lòng thử lại.</div>').show();
                }
            });
        }

        $(document).ready(function() {
            console.log('Chat index script loaded successfully');
            console.log('jQuery version:', $.fn.jquery);
            console.log('Search input element:', $('#user-search').length);
            console.log('Search button element:', $('#search-btn').length);
            
            let searchTimeout;

            // Simple search handlers
            $('#user-search').on('input', function() {
                const query = $(this).val().trim();
                console.log('Input:', query);
                clearTimeout(searchTimeout);
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => performUserSearch(query), 300);
                } else {
                    $('#search-results').hide();
                }
            });

            $('#user-search').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = $(this).val().trim();
                    console.log('Enter pressed:', query);
                    if (query.length >= 2) {
                        clearTimeout(searchTimeout);
                        performUserSearch(query);
                    }
                }
            });

            $('#search-btn').on('click', function(e) {
                e.preventDefault();
                const query = $('#user-search').val().trim();
                console.log('Button clicked:', query);
                if (query.length >= 2) {
                    clearTimeout(searchTimeout);
                    performUserSearch(query);
                }
            });

            // Start 1v1 chat immediately when clicking a user result
            $(document).on('click', '.search-result-item', function() {
            const userId = $(this).data('user-id');
            const userName = $(this).data('user-name');
            if (!userId) return;
            
            // Show loading state
            $(this).html('<div class="p-2 text-center"><i class="fas fa-spinner fa-spin me-2"></i>Đang tạo cuộc trò chuyện...</div>');
            
            $.ajax({
                url: '{{ route("chat.start") }}',
                method: 'POST',
                data: {
                    user_id: userId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success && response.redirect_url) {
                        window.location.href = response.redirect_url;
                    } else {
                        $('#search-results').html('<div class="alert alert-warning">Không thể tạo cuộc trò chuyện. Vui lòng thử lại.</div>').show();
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'Không thể tạo cuộc trò chuyện. Vui lòng thử lại.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#search-results').html(`<div class="alert alert-danger">${errorMsg}</div>`).show();
                }
            });
            });

            // Removed online users count per requirements

            // Hide search results when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#user-search, #search-results').length) {
                    $('#search-results').hide();
                }
            });

            // Keyboard shortcuts: '/' to focus search, Esc to clear/close
            $(document).on('keydown', function(e) {
            // Focus quick search
            if (e.key === '/' && !$(e.target).is('input, textarea')) {
                e.preventDefault();
                $('#user-search').trigger('focus');
                }
                // Close search results
                if (e.key === 'Escape') {
                    $('#search-results').hide();
                }
            });

            // Arrow navigation + Enter selection for quick search results
            let highlightedIndex = -1;
            $(document).on('keydown', function(e) {
                const items = $('#search-results .search-result-item');
                if (!items.length || !$('#user-search').is(':focus')) return;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    highlightedIndex = (highlightedIndex + 1) % items.length;
                    items.removeClass('active');
                    $(items[highlightedIndex]).addClass('active');
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    highlightedIndex = (highlightedIndex - 1 + items.length) % items.length;
                    items.removeClass('active');
                    $(items[highlightedIndex]).addClass('active');
                } else if (e.key === 'Enter' && highlightedIndex >= 0) {
                    e.preventDefault();
                    $(items[highlightedIndex]).trigger('click');
                }
            });

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
            }, 300); // 300ms delay for better UX
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
                        showSuccessMessage('Nhóm chat đã được tạo thành công!');
                        
                        // Redirect to new group
                        setTimeout(() => {
                            window.location.href = response.redirect_url;
                        }, 1500);
                    } else {
                        showErrorMessage(response.message || 'Có lỗi xảy ra khi tạo nhóm');
                        resetCreateButton();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.error || 'Có lỗi xảy ra khi tạo nhóm';
                    showErrorMessage(message);
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

        // Show success message
        function showSuccessMessage(message) {
            // Create a modern toast notification
            const toast = $(`
                <div class="success-toast">
                    <i class="fas fa-check-circle"></i>
                    <span>${message}</span>
                </div>
            `);
            
            $('body').append(toast);
            setTimeout(() => toast.fadeOut(() => toast.remove()), 3000);
        }

        // Show error message  
        function showErrorMessage(message) {
            // Create a modern toast notification
            const toast = $(`
                <div class="error-toast">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>${message}</span>
                </div>
            `);
            
            $('body').append(toast);
            setTimeout(() => toast.fadeOut(() => toast.remove()), 4000);
        }

        // Event listeners
        $('#createGroupModal').on('shown.bs.modal', initializeGroupModal);
        
        // Form validation
        $('#groupName').on('input', checkFormValidity);
        
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
        $('#clearAllBtn').on('click', clearAllMembers);
        
        // Create group
        $('#createGroupBtn').on('click', createGroup);
        
            // Initialize file upload display
            updateFileUploadDisplay();
        }); // End of $(document).ready
    }); // End of DOMContentLoaded
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
@endpush

<style>
/* Modern Chat Index Styles - Aligned with Header/Footer */
.chat-index-container {
    height: calc(100vh - 76px);
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
    position: relative;
    overflow: hidden;
    padding: 0;
}

.chat-index-container::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: 
        radial-gradient(circle at 25% 25%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
    background-size: 600px 600px;
    animation: float-pattern 20s ease-in-out infinite;
    pointer-events: none;
}

@keyframes float-pattern {
    0%, 100% { transform: translate(0, 0) rotate(0deg); }
    33% { transform: translate(-30px, -30px) rotate(2deg); }
    66% { transform: translate(30px, -20px) rotate(-2deg); }
}

/* Modern Sidebar - Consistent with Header */
.chat-sidebar-modern {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 0;
    box-shadow: 0 0 60px rgba(0, 0, 0, 0.3);
    border: none;
    border-right: 1px solid rgba(255, 255, 255, 0.2);
    height: 100%;
    position: sticky;
    top: 0;
    z-index: 10;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* Modern Header - Matching Navigation Style */
.chat-header-modern {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
    color: white;
    padding: 2rem 1.5rem;
    border-radius: 0;
    position: relative;
    overflow: hidden;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.header-content {
    position: relative;
    z-index: 2;
}

.header-content h5 {
    background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
}

.header-content small {
    color: #94a3b8;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.header-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 100%);
    pointer-events: none;
}

/* Action Section - Consistent Styling */
.action-section {
    padding: 2rem 1.5rem;
    background: rgba(255, 255, 255, 0.95);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.create-group-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 15px;
    padding: 1rem 1.5rem;
    color: white;
    font-weight: 600;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.create-group-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.4);
    color: white;
}

/* Search Container - Modern Design */
.search-container {
    margin-top: 1.5rem;
}

.search-input-wrapper {
    position: relative;
    background: rgba(26, 26, 46, 0.05);
    border-radius: 15px;
    padding: 0.75rem;
    box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.05);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.search-input-wrapper:focus-within {
    border-color: rgba(102, 126, 234, 0.3);
    background: rgba(102, 126, 234, 0.05);
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.search-icon {
    color: #64748b;
    margin-left: 0.5rem;
}

.search-input {
    border: none;
    background: transparent;
    flex: 1;
    padding: 0.5rem;
    font-size: 0.95rem;
    outline: none;
    color: #1e293b;
}

.search-input::placeholder {
    color: #94a3b8;
}

.search-btn {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    border: none;
    border-radius: 12px;
    width: 45px;
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: all 0.3s ease;
}

.search-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(26, 26, 46, 0.4);
}

.search-results {
    background: white;
    border-radius: 15px;
    margin-top: 1rem;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    max-height: 300px;
    overflow-y: auto;
}

/* Conversations Container */
.conversations-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: white;
    min-height: 0;
    overflow: hidden;
}

.conversations-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}

.section-title {
    font-weight: 600;
    color: #1e293b;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.conversation-count {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
}

/* Conversations List */
.conversations-list {
    flex: 1;
    overflow-y: auto;
    padding: 0.5rem;
}

.conversations-list::-webkit-scrollbar {
    width: 4px;
}

.conversations-list::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
}

.conversations-list::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-radius: 10px;
}

/* Conversation Items */
.conversation-item {
    display: flex;
    align-items: center;
    padding: 1rem;
    border-radius: 15px;
    margin-bottom: 0.5rem;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    position: relative;
    background: white;
    border: 1px solid transparent;
}

.conversation-item:hover {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    color: inherit;
    text-decoration: none;
}

.conversation-item.active {
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    border-color: #3b82f6;
    transform: translateX(5px);
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.25);
}

.conversation-avatar {
    position: relative;
    margin-right: 1rem;
}

.avatar-img {
    width: 50px;
    height: 50px;
    border-radius: 15px;
    border: 3px solid white;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.conversation-item:hover .avatar-img {
    transform: scale(1.05);
}

.online-indicator {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    background: #10b981;
    border: 2px solid white;
    border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
    animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
    0%, 100% { box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2); }
    50% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.1); }
}

.conversation-content {
    flex: 1;
    min-width: 0;
}

.conversation-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.25rem;
}

.conversation-name {
    margin: 0;
    font-weight: 600;
    color: #1e293b;
    font-size: 1rem;
}

.conversation-time {
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 500;
}

.conversation-preview {
    margin: 0;
    color: #64748b;
    font-size: 0.85rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.unread-badge {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-left: 0.5rem;
    min-width: 20px;
    text-align: center;
    animation: bounce 1s infinite;
}

@keyframes bounce {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: #64748b;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #94a3b8;
}

.empty-title {
    color: #1e293b;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.empty-description {
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Sidebar Footer - Consistent with Main Footer */
.sidebar-footer {
    padding: 0.75rem 1.5rem;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 0;
    margin-top: auto;
    flex-shrink: 0;
}

.online-status {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-indicator {
    position: relative;
}

.status-dot {
    width: 14px;
    height: 14px;
    background: #10b981;
    border-radius: 50%;
    position: relative;
    z-index: 2;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.status-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 14px;
    height: 14px;
    background: #10b981;
    border-radius: 50%;
    transform: translate(-50%, -50%);
    animation: pulse-status 2s infinite;
}

@keyframes pulse-status {
    0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    100% { transform: translate(-50%, -50%) scale(3); opacity: 0; }
}

.status-text {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    font-weight: 500;
}

/* Main Content Area - Consistent with Footer Design */
.main-content-area {
    height: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
    border-radius: 0;
    margin-top: 0;
    box-shadow: none;
    border: none;
    border-left: 1px solid rgba(0, 0, 0, 0.1);
}

.welcome-container {
    text-align: center;
    z-index: 2;
    position: relative;
    max-width: 600px;
    padding: 3rem;
}

.welcome-icon {
    margin-bottom: 3rem;
}

.icon-wrapper {
    width: 150px;
    height: 150px;
    margin: 0 auto;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
    position: relative;
    box-shadow: 0 30px 80px rgba(26, 26, 46, 0.4);
    border: 3px solid rgba(255, 255, 255, 0.1);
}

.icon-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 150px;
    height: 150px;
    border: 3px solid #667eea;
    border-radius: 30px;
    transform: translate(-50%, -50%);
    animation: pulse-icon 3s infinite;
    opacity: 0.4;
}

@keyframes pulse-icon {
    0% { transform: translate(-50%, -50%) scale(1); opacity: 0.4; }
    100% { transform: translate(-50%, -50%) scale(1.3); opacity: 0; }
}

.welcome-title {
    color: #1a1a2e;
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    background: linear-gradient(135deg, #1a1a2e 0%, #667eea 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.welcome-description {
    color: #64748b;
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 3rem;
}

/* Action Buttons - Consistent with Header Style */
.welcome-actions {
    display: flex;
    gap: 1.5rem;
    justify-content: center;
    flex-wrap: wrap;
}

.action-btn {
    padding: 1rem 2rem;
    border-radius: 15px;
    border: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: all 0.3s ease;
    text-decoration: none;
    min-width: 180px;
    justify-content: center;
    font-size: 1rem;
}

.action-btn.primary {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f0f23 100%);
    color: white;
    box-shadow: 0 8px 25px rgba(26, 26, 46, 0.3);
    border: 2px solid transparent;
}

.action-btn.primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(26, 26, 46, 0.4);
    color: white;
    border-color: rgba(102, 126, 234, 0.3);
}

.action-btn.secondary {
    background: rgba(255, 255, 255, 0.8);
    color: #1a1a2e;
    border: 2px solid rgba(26, 26, 46, 0.2);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.action-btn.secondary:hover {
    background: rgba(26, 26, 46, 0.05);
    border-color: #667eea;
    transform: translateY(-3px);
    color: #1a1a2e;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

/* Background Animation - Consistent with Footer */
.background-animation {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    pointer-events: none;
    opacity: 0.6;
}

.floating-bubble {
    position: absolute;
    background: linear-gradient(135deg, rgba(26, 26, 46, 0.1) 0%, rgba(102, 126, 234, 0.1) 100%);
    border-radius: 50%;
    animation: float 15s infinite linear;
    animation-delay: var(--delay);
    width: var(--size);
    height: var(--size);
}

@keyframes float {
    0% {
        transform: translateY(100vh) rotate(0deg);
        opacity: 0;
    }
    10% {
        opacity: 1;
    }
    90% {
        opacity: 1;
    }
    100% {
        transform: translateY(-100px) rotate(360deg);
        opacity: 0;
    }
}

/* Modern Group Creation Modal Styles */
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
    z-index: 2;
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
    background: rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    margin-top: 10px;
    max-height: 200px;
    overflow-y: auto;
    backdrop-filter: blur(10px);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.search-results-header {
    padding: 12px 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(255, 255, 255, 0.05);
}

.results-count {
    color: rgba(255, 255, 255, 0.7);
    font-size: 12px;
    font-weight: 500;
}

.search-results-list {
    padding: 8px;
}

.search-result-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.search-result-item:hover {
    background: rgba(102, 126, 234, 0.1);
}

.result-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 500;
    font-size: 14px;
}

.result-info {
    flex: 1;
}

.result-name {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 500;
    font-size: 14px;
    margin: 0;
}

.result-email {
    color: rgba(255, 255, 255, 0.6);
    font-size: 12px;
    margin: 0;
}

/* Selected Members Styling */
.selected-members {
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 16px;
    min-height: 160px;
}

.selected-members-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.selected-count {
    color: rgba(255, 255, 255, 0.8);
    font-size: 13px;
    font-weight: 500;
}

.clear-all-btn {
    background: none;
    border: none;
    color: rgba(255, 107, 107, 0.8);
    font-size: 12px;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.clear-all-btn:hover {
    background: rgba(255, 107, 107, 0.1);
    color: #ff6b6b;
}

.selected-members-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.selected-member-tag {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
    border: 1px solid rgba(102, 126, 234, 0.3);
    border-radius: 20px;
    padding: 6px 12px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 13px;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* File Upload Selected State */
.file-upload-display.file-selected {
    background: rgba(102, 126, 234, 0.1);
    border-color: rgba(102, 126, 234, 0.4);
}

.file-upload-display.file-selected .upload-icon {
    color: #667eea;
}

.file-upload-display.file-selected .upload-main {
    color: #667eea;
}

/* Toast Notifications */
.success-toast, .error-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    color: white;
    font-weight: 500;
    z-index: 10000;
    min-width: 300px;
    backdrop-filter: blur(20px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    animation: toastSlideIn 0.4s ease;
}

.success-toast {
    background: linear-gradient(135deg, rgba(52, 199, 89, 0.9) 0%, rgba(48, 176, 199, 0.9) 100%);
    border: 1px solid rgba(52, 199, 89, 0.3);
}

.error-toast {
    background: linear-gradient(135deg, rgba(255, 59, 48, 0.9) 0%, rgba(255, 107, 107, 0.9) 100%);
    border: 1px solid rgba(255, 59, 48, 0.3);
}

@keyframes toastSlideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Additional Modal Improvements */
.add-member-btn {
    color: rgba(102, 126, 234, 0.7);
    font-size: 14px;
    transition: all 0.2s ease;
}

.search-result-item:hover .add-member-btn {
    color: #667eea;
    transform: scale(1.1);
}

/* Scrollbar Improvements for Search Results */
.live-search-results::-webkit-scrollbar {
    width: 6px;
}

.live-search-results::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.live-search-results::-webkit-scrollbar-thumb {
    background: rgba(102, 126, 234, 0.5);
    border-radius: 3px;
}

.live-search-results::-webkit-scrollbar-thumb:hover {
    background: rgba(102, 126, 234, 0.7);
}

/* Modal Animation Improvements */
.modal.fade .modal-dialog {
    transition: transform 0.4s ease, opacity 0.4s ease;
    transform: scale(0.8) translateY(0);
}

.modal.show .modal-dialog {
    transform: scale(1) translateY(0);
}

/* Fix modal z-index to be above header */
.modal {
    z-index: 100000 !important;
}

.modal-backdrop {
    z-index: 99999 !important;
}

/* Ensure modal is properly positioned */
.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100vh - 1rem);
    margin: 0.5rem auto;
}

/* Fix modal positioning on mobile */
@media (max-width: 576px) {
    .modal-dialog-centered {
        min-height: calc(100vh - 2rem);
        margin: 1rem;
    }
}

/* Responsive Improvements */
@media (max-width: 992px) {
    .modern-modal {
        margin: 10px;
    }
    
    .modern-modal-body {
        padding: 20px;
    }
    
    .modern-modal-header {
        padding: 20px;
    }
    
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
    
    .success-toast, .error-toast {
        min-width: calc(100% - 40px);
        margin: 0 20px;
        font-size: 14px;
    }
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
    font-size: 13px;
}

.footer-info i {
    color: rgba(102, 126, 234, 0.7);
}

.footer-actions {
    display: flex;
    gap: 12px;
}

.modern-btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: rgba(255, 255, 255, 0.8);
    padding: 12px 24px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
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
    padding: 12px 24px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.modern-btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}

.modern-btn-primary:disabled {
    background: rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.4);
    cursor: not-allowed;
}

.btn-spinner {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background: inherit;
    border-radius: inherit;
}

/* Mobile Responsive - Consistent Design */
@media (max-width: 768px) {
    .chat-index-container {
        padding: 0;
        min-height: calc(100vh - 120px);
    }
    
    .chat-sidebar-modern {
        height: auto;
        min-height: calc(50vh);
        position: relative;
        top: 0;
        margin-bottom: 0;
        border-radius: 0;
        border-right: none;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        display: block;
    }
    
    .main-content-area {
        margin-top: 0;
        min-height: calc(50vh);
        border-radius: 0;
        border-left: none;
    }
    
    .welcome-title {
        font-size: 2.2rem;
    }
    
    .welcome-description {
        font-size: 1rem;
    }
    
    .welcome-actions {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .action-btn {
        width: 100%;
        max-width: 280px;
        padding: 1rem 1.5rem;
    }
    
    .icon-wrapper {
        width: 120px;
        height: 120px;
        border-radius: 30px;
        font-size: 3rem;
    }
    
    .icon-pulse {
        width: 120px;
        height: 120px;
        border-radius: 30px;
    }
    
    .conversation-item {
        padding: 1rem;
        border-radius: 15px;
    }
    
    .avatar-img {
        width: 45px;
        height: 45px;
        border-radius: 12px;
    }
    
    .chat-header-modern {
        padding: 1.5rem;
        border-radius: 20px 20px 0 0;
    }
    
    .action-section {
        padding: 1.5rem;
    }
    
    .search-input-wrapper {
        border-radius: 20px;
    }
    
    .search-btn {
        width: 40px;
        height: 40px;
        border-radius: 15px;
    }
}
</style>

@endsection