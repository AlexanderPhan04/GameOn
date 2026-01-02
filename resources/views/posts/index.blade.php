@extends('layouts.app')

@section('title','Bài viết')

@push('styles')
<style>
    .posts-page {
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        min-height: 100vh;
    }
    .feed-container {
        max-width: 680px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    .page-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
        text-align: center;
        margin-bottom: 1.5rem;
        text-shadow: 0 0 20px rgba(0, 229, 255, 0.3);
    }
    
    /* Composer Card */
    .composer-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255,255,255,0.05);
    }
    .composer-inner {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .composer-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2px solid rgba(0, 229, 255, 0.3);
        background-size: cover;
        background-position: center;
        flex-shrink: 0;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }
    .composer-trigger {
        flex: 1;
        background: rgba(0, 0, 85, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 25px;
        padding: 0.875rem 1.25rem;
        color: #94a3b8;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: left;
    }
    .composer-trigger:hover {
        background: rgba(0, 229, 255, 0.08);
        border-color: rgba(0, 229, 255, 0.3);
        color: #00E5FF;
    }

    /* Empty State */
    .empty-state {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }
    .empty-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        filter: grayscale(0.3);
    }
    .empty-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 0.5rem;
    }
    .empty-desc {
        color: #94a3b8;
        font-family: 'Inter', sans-serif;
        margin-bottom: 1.5rem;
    }
    .empty-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    /* Buttons */
    .btn-primary-glow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #000055 0%, rgba(0, 229, 255, 0.2) 100%);
        border: 1px solid rgba(0, 229, 255, 0.4);
        border-radius: 12px;
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 229, 255, 0.15);
    }
    .btn-primary-glow:hover {
        background: linear-gradient(135deg, rgba(0, 229, 255, 0.2) 0%, #000055 100%);
        box-shadow: 0 6px 25px rgba(0, 229, 255, 0.3);
        transform: translateY(-2px);
        color: #00E5FF;
    }
    .btn-secondary-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: transparent;
        border: 1px solid rgba(148, 163, 184, 0.3);
        border-radius: 12px;
        color: #94a3b8;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-secondary-outline:hover {
        border-color: rgba(0, 229, 255, 0.4);
        color: #00E5FF;
        background: rgba(0, 229, 255, 0.05);
    }

    /* Post Card */
    .post-card {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.12);
        border-radius: 20px;
        margin-bottom: 1.5rem;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        transition: all 0.3s ease;
    }
    .post-card:hover {
        border-color: rgba(0, 229, 255, 0.25);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.35), 0 0 20px rgba(0, 229, 255, 0.05);
    }
    .post-body {
        padding: 1.25rem;
    }
    
    /* Post Header */
    .post-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    .post-author {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .post-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid rgba(0, 229, 255, 0.25);
        background-size: cover;
        background-position: center;
        transition: all 0.3s ease;
    }
    .post-avatar:hover {
        border-color: #00E5FF;
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.4);
    }
    .post-author-info {
        display: flex;
        flex-direction: column;
    }
    .post-author-name {
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: color 0.2s;
    }
    .post-author-name:hover {
        color: #00E5FF;
    }
    .post-time {
        color: #64748b;
        font-size: 0.8rem;
        cursor: pointer;
    }
    .post-time:hover {
        text-decoration: underline;
        color: #94a3b8;
    }
    
    /* Post Menu */
    .post-menu-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: transparent;
        border: none;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .post-menu-btn:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
    }
    .post-dropdown {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 0.5rem;
        background: #0d1b2a;
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        padding: 0.5rem;
        min-width: 180px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        z-index: 100;
        display: none;
    }
    .post-dropdown.show {
        display: block;
    }
    .post-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.625rem 0.875rem;
        border-radius: 8px;
        color: #94a3b8;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }
    .post-dropdown-item:hover {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
    }
    .post-dropdown-item.danger {
        color: #f87171;
    }
    .post-dropdown-item.danger:hover {
        background: rgba(248, 113, 113, 0.1);
        color: #f87171;
    }
    .post-dropdown-divider {
        height: 1px;
        background: rgba(0, 229, 255, 0.1);
        margin: 0.375rem 0;
    }

    /* Post Content */
    .post-content {
        color: #e2e8f0;
        font-family: 'Inter', sans-serif;
        font-size: 0.95rem;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
        margin-bottom: 0.75rem;
    }
    
    /* Post Media */
    .post-media-single {
        width: 100%;
        border-radius: 12px;
        margin-top: 0.75rem;
    }
    .post-media-grid {
        display: grid;
        gap: 4px;
        margin-top: 0.75rem;
        border-radius: 12px;
        overflow: hidden;
    }
    .post-media-grid.cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }
    .post-media-grid.cols-3 {
        grid-template-columns: 2fr 1fr;
        grid-template-rows: repeat(2, 1fr);
    }
    .post-media-grid .media-item {
        position: relative;
        aspect-ratio: 1;
        overflow: hidden;
    }
    .post-media-grid.cols-3 .media-item:first-child {
        grid-row: span 2;
        aspect-ratio: auto;
    }
    .post-media-grid .media-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-more-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.75rem;
        font-weight: 700;
    }
    
    /* Shared Post */
    .shared-post {
        background: rgba(0, 0, 85, 0.2);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        padding: 0.875rem;
        margin-top: 0.75rem;
    }
    .shared-author {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }
    .shared-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid rgba(0, 229, 255, 0.2);
    }
    .shared-name {
        color: #fff;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .shared-time {
        color: #64748b;
        font-size: 0.75rem;
    }
    .shared-content {
        color: #94a3b8;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    /* Stats Bar */
    .post-stats {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.625rem 0;
        margin-top: 0.5rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.08);
        font-size: 0.85rem;
        color: #64748b;
    }
    .stats-reactions {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    .reaction-icons {
        display: flex;
    }
    .reaction-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.6rem;
        color: #fff;
        margin-left: -4px;
        border: 2px solid #0d1b2a;
    }
    .reaction-icon:first-child {
        margin-left: 0;
    }
    .reaction-icon.like { background: #3b82f6; }
    .reaction-icon.love { background: #ef4444; }
    .reaction-icon.haha { background: #f59e0b; }
    .reaction-icon.wow { background: #06b6d4; }
    .reaction-icon.sad { background: #64748b; }
    .reaction-icon.angry { background: #f97316; }
    .stats-count {
        cursor: pointer;
    }
    .stats-count:hover {
        text-decoration: underline;
        color: #94a3b8;
    }
    .stats-right {
        display: flex;
        gap: 1rem;
    }
    
    /* Action Buttons */
    .post-actions {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        padding-top: 0.75rem;
    }
    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.625rem;
        background: rgba(0, 0, 85, 0.25);
        border: 1px solid rgba(0, 229, 255, 0.08);
        border-radius: 10px;
        color: #94a3b8;
        font-family: 'Inter', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        background: rgba(0, 229, 255, 0.1);
        border-color: rgba(0, 229, 255, 0.2);
        color: #00E5FF;
    }
    .action-btn.active {
        color: #00E5FF;
    }
    .action-btn.reacted-like { color: #3b82f6; }
    .action-btn.reacted-love { color: #ef4444; }
    .action-btn.reacted-haha { color: #f59e0b; }
    .action-btn.reacted-wow { color: #06b6d4; }
    .action-btn.reacted-sad { color: #64748b; }
    .action-btn.reacted-angry { color: #f97316; }
    
    /* Reactions Popover */
    .like-wrapper {
        position: relative;
    }
    .reactions-popover {
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.25);
        border-radius: 30px;
        padding: 0.5rem 0.625rem;
        display: none;
        gap: 0.25rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5), 0 0 20px rgba(0, 229, 255, 0.1);
        z-index: 50;
    }
    .reactions-popover.show {
        display: flex;
    }
    .reactions-popover .reaction {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        color: #fff;
        font-size: 0.95rem;
    }
    .reactions-popover .reaction:hover {
        transform: scale(1.2) translateY(-4px);
    }
    .reactions-popover .reaction.like { background: #3b82f6; }
    .reactions-popover .reaction.love { background: #ef4444; }
    .reactions-popover .reaction.haha { background: #f59e0b; }
    .reactions-popover .reaction.wow { background: #06b6d4; }
    .reactions-popover .reaction.sad { background: #64748b; }
    .reactions-popover .reaction.angry { background: #f97316; }

    /* Comments Section */
    .comments-section {
        padding-top: 1rem;
        display: none;
    }
    .comments-section.show {
        display: block;
    }
    .comment-form {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .comment-input {
        flex: 1;
        background: rgba(0, 0, 85, 0.3);
        border: 1px solid rgba(0, 229, 255, 0.15);
        border-radius: 20px;
        padding: 0.625rem 1rem;
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        outline: none;
        transition: all 0.2s;
    }
    .comment-input::placeholder {
        color: #64748b;
    }
    .comment-input:focus {
        border-color: rgba(0, 229, 255, 0.4);
        box-shadow: 0 0 0 3px rgba(0, 229, 255, 0.1);
    }
    .comment-submit {
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #000055 0%, rgba(0, 229, 255, 0.15) 100%);
        border: 1px solid rgba(0, 229, 255, 0.3);
        border-radius: 20px;
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .comment-submit:hover {
        background: rgba(0, 229, 255, 0.15);
        box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
    }
    
    /* Comment Item */
    .comment-item {
        display: flex;
        gap: 0.625rem;
        margin-bottom: 0.875rem;
    }
    .comment-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        flex-shrink: 0;
    }
    .comment-bubble {
        background: rgba(0, 0, 85, 0.35);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 16px;
        padding: 0.625rem 0.875rem;
        max-width: calc(100% - 50px);
    }
    .comment-author {
        color: #fff;
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.125rem;
    }
    .comment-text {
        color: #cbd5e1;
        font-size: 0.875rem;
        line-height: 1.4;
        white-space: pre-wrap;
    }
    .comment-actions {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        margin-top: 0.375rem;
        padding-left: 0.25rem;
        font-size: 0.75rem;
        color: #64748b;
    }
    .comment-action {
        cursor: pointer;
        transition: color 0.2s;
    }
    .comment-action:hover {
        color: #00E5FF;
    }
    .comment-action.active {
        color: #3b82f6;
    }

    /* Modal */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        z-index: 10000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .modal-overlay.show {
        display: flex;
    }
    .modal-content {
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 20px;
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6), 0 0 40px rgba(0, 229, 255, 0.1);
        animation: modalIn 0.25s ease;
    }
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid rgba(0, 229, 255, 0.1);
    }
    .modal-title {
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: #fff;
    }
    .modal-close {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        border: none;
        color: #94a3b8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .modal-close:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
    }
    .modal-body {
        padding: 1.25rem;
        overflow-y: auto;
        max-height: calc(90vh - 140px);
    }
    .modal-footer {
        padding: 1rem 1.25rem;
        border-top: 1px solid rgba(0, 229, 255, 0.1);
    }
    
    /* Composer Modal */
    .composer-textarea {
        width: 100%;
        background: transparent;
        border: none;
        color: #fff;
        font-family: 'Inter', sans-serif;
        font-size: 1.1rem;
        line-height: 1.5;
        resize: none;
        outline: none;
    }
    .composer-textarea::placeholder {
        color: #64748b;
    }
    .composer-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.875rem;
        background: rgba(0, 0, 85, 0.2);
        border: 1px solid rgba(0, 229, 255, 0.1);
        border-radius: 12px;
        margin-top: 1rem;
    }
    .toolbar-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .toolbar-right {
        display: flex;
        gap: 0.5rem;
    }
    .toolbar-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(0, 0, 85, 0.4);
        border: 1px dashed rgba(0, 229, 255, 0.2);
        color: #94a3b8;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .toolbar-btn:hover {
        background: rgba(0, 229, 255, 0.1);
        border-color: rgba(0, 229, 255, 0.4);
        color: #00E5FF;
    }
    .toolbar-btn.green { color: #4ade80; }
    .toolbar-btn.blue { color: #60a5fa; }
    .toolbar-btn.yellow { color: #fbbf24; }
    
    .visibility-btn {
        padding: 0.5rem 0.875rem;
        background: rgba(0, 0, 85, 0.5);
        border: 1px solid rgba(0, 229, 255, 0.25);
        border-radius: 8px;
        color: #00E5FF;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }
    .visibility-btn:hover {
        background: rgba(0, 229, 255, 0.1);
    }
    .visibility-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        margin-top: 0.5rem;
        background: #0d1b2a;
        border: 1px solid rgba(0, 229, 255, 0.2);
        border-radius: 12px;
        padding: 0.5rem;
        min-width: 180px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
        z-index: 100;
        display: none;
    }
    .visibility-dropdown.show {
        display: block;
    }
    .visibility-option {
        display: block;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        color: #94a3b8;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .visibility-option:hover {
        background: rgba(0, 229, 255, 0.1);
        color: #00E5FF;
    }
    
    .submit-btn {
        width: 100%;
        padding: 0.875rem;
        background: linear-gradient(135deg, #000055 0%, rgba(0, 229, 255, 0.2) 100%);
        border: 1px solid rgba(0, 229, 255, 0.4);
        border-radius: 12px;
        color: #00E5FF;
        font-family: 'Rajdhani', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    .submit-btn:hover {
        box-shadow: 0 0 25px rgba(0, 229, 255, 0.3);
    }
    
    /* Media Preview */
    .media-preview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .media-preview-item {
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        background: rgba(0, 0, 85, 0.3);
    }
    .media-preview-item img,
    .media-preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Confirm Modal */
    .confirm-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(239, 68, 68, 0.15);
        color: #f87171;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .confirm-actions {
        display: flex;
        gap: 0.75rem;
    }
    .confirm-actions button {
        flex: 1;
        padding: 0.75rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-cancel {
        background: rgba(0, 0, 85, 0.3);
        border: 1px solid rgba(148, 163, 184, 0.2);
        color: #94a3b8;
    }
    .btn-cancel:hover {
        background: rgba(148, 163, 184, 0.1);
        color: #fff;
    }
    .btn-danger {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #f87171;
    }
    .btn-danger:hover {
        background: rgba(239, 68, 68, 0.3);
    }
</style>
@endpush

@section('content')
<div class="posts-page">
    <div class="feed-container">
        <h1 class="page-title">{{ __('app.nav.posts') }}</h1>

        <!-- Composer Card -->
        <div class="composer-card">
            <div class="composer-inner">
                @auth
                @php $me = auth()->user(); $meAvatar = get_avatar_url($me?->avatar); @endphp
                <a href="{{ route('profile.show') }}" title="{{ __('app.nav.profile') }}">
                    <div class="composer-avatar" style="background-image: url('{{ $meAvatar }}')"></div>
                </a>
                @else
                <div class="composer-avatar" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)"></div>
                @endauth
                <button class="composer-trigger" onclick="openComposerModal()">
                    {{ __('app.feed.whats_on_your_mind') }}
                </button>
            </div>
        </div>

        <!-- Empty State -->
        @if($posts->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">📰</div>
            <h2 class="empty-title">Bảng tin của bạn còn trống</h2>
            <p class="empty-desc">Hãy kết bạn để xem thêm bài viết mới từ mọi người.</p>
            <div class="empty-actions">
                <a href="{{ route('search.view') }}" class="btn-primary-glow">
                    <i class="fas fa-user-plus"></i> Tìm bạn bè
                </a>
                <a href="{{ route('search.view') }}" class="btn-secondary-outline">
                    <i class="fas fa-users"></i> Khám phá cộng đồng
                </a>
            </div>
        </div>
        @endif

        <!-- Posts List -->
        @foreach($posts as $post)
        <div class="post-card" data-post-card="{{ $post->id }}">
            <div class="post-body">
                <!-- Header -->
                <div class="post-header">
                    <div class="post-author">
                        <a href="{{ (auth()->id() === $post->user_id) ? route('profile.show') : route('profile.show-user', $post->user_id) }}">
                            @php $avatarUrl = get_avatar_url($post->user?->avatar); @endphp
                            <div class="post-avatar" style="background-image: url('{{ $avatarUrl }}')"></div>
                        </a>
                        <div class="post-author-info">
                            <a href="{{ (auth()->id() === $post->user_id) ? route('profile.show') : route('profile.show-user', $post->user_id) }}" class="post-author-name">
                                {{ $post->user->name ?? 'User' }}
                            </a>
                            <span class="post-time" data-timestamp="{{ $post->created_at->toIso8601String() }}">
                                {{ $post->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    @auth
                    @php $canDelete = (auth()->id() === $post->user_id) || (in_array(auth()->user()->user_role,['admin','super_admin'])); @endphp
                    @if($canDelete)
                    <div style="position: relative;">
                        <button class="post-menu-btn" onclick="toggleDropdown('postMenu{{ $post->id }}')">
                            <i class="fas fa-ellipsis-h"></i>
                        </button>
                        <div class="post-dropdown" id="postMenu{{ $post->id }}">
                            <button class="post-dropdown-item" onclick="openEditModal({{ $post->id }})">
                                <i class="fas fa-pen"></i> Chỉnh sửa
                            </button>
                            <div class="post-dropdown-divider"></div>
                            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="delete-post-form" data-post-id="{{ $post->id }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="post-dropdown-item danger">
                                    <i class="fas fa-trash"></i> Xóa bài viết
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                    @endauth
                </div>

                <!-- Content -->
                @if($post->content)
                <div class="post-content">{{ $post->content }}</div>
                @endif

                <!-- Shared Post -->
                @if($post->shared_post_id && $post->sharedPost)
                @php $sp = $post->sharedPost; $spAvatar = get_avatar_url($sp->user?->avatar); @endphp
                <div class="shared-post">
                    @if($sp->media && $sp->media->count())
                        @php $spImages = $sp->media->where('type','image'); @endphp
                        @if($spImages->count() == 1)
                            <img src="{{ asset('storage/'.$spImages->first()->path) }}" class="post-media-single">
                        @elseif($spImages->count() >= 2)
                            <div class="post-media-grid cols-2">
                                @foreach($spImages->take(4) as $i => $im)
                                <div class="media-item">
                                    <img src="{{ asset('storage/'.$im->path) }}">
                                    @if($i == 3 && $spImages->count() > 4)
                                    <div class="media-more-overlay">+{{ $spImages->count() - 4 }}</div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                    <div class="shared-author">
                        <img src="{{ $spAvatar }}" class="shared-avatar">
                        <div>
                            <div class="shared-name">{{ $sp->user->name ?? 'User' }}</div>
                            <div class="shared-time">{{ optional($sp->created_at)->diffForHumans() }}</div>
                        </div>
                    </div>
                    @if($sp->content)
                    <div class="shared-content">{{ $sp->content }}</div>
                    @endif
                </div>
                @endif

                <!-- Post Media -->
                @if($post->media && $post->media->count())
                    @php
                        $images = $post->media->where('type','image');
                        $videos = $post->media->where('type','!=','image');
                        $imgCount = $images->count();
                    @endphp
                    @if($imgCount == 1)
                        <img src="{{ asset('storage/'.$images->first()->path) }}" class="post-media-single">
                    @elseif($imgCount == 2)
                        <div class="post-media-grid cols-2">
                            @foreach($images as $im)
                            <div class="media-item"><img src="{{ asset('storage/'.$im->path) }}"></div>
                            @endforeach
                        </div>
                    @elseif($imgCount >= 3)
                        <div class="post-media-grid cols-3">
                            @foreach($images->take(5) as $i => $im)
                            <div class="media-item">
                                <img src="{{ asset('storage/'.$im->path) }}">
                                @if($i == 4 && $imgCount > 5)
                                <div class="media-more-overlay">+{{ $imgCount - 5 }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                    @foreach($videos as $v)
                        <video controls class="post-media-single"><source src="{{ asset('storage/'.$v->path) }}"></video>
                    @endforeach
                @endif

                <!-- Stats Bar -->
                <div class="post-stats">
                    @php
                        $currentUserId = auth()->id();
                        $statIconClassMap = ['like'=>'far fa-thumbs-up','love'=>'fas fa-heart','haha'=>'fas fa-laugh','wow'=>'fas fa-surprise','sad'=>'fas fa-sad-tear','angry'=>'fas fa-angry'];
                        $typeCounts = optional($post->reactions)->groupBy('type')->map->count()->sortDesc();
                        $topTypes = $typeCounts ? $typeCounts->keys()->take(2) : collect();
                    @endphp
                    <div class="stats-reactions {{ $post->likes_count > 0 ? '' : 'hidden' }}" id="likes-wrap-{{ $post->id }}">
                        <div class="reaction-icons">
                            @foreach($topTypes as $t)
                            <span class="reaction-icon {{ $t }}"><i class="{{ $statIconClassMap[$t] }}"></i></span>
                            @endforeach
                            @if($topTypes->isEmpty())
                            <span class="reaction-icon like"><i class="far fa-thumbs-up"></i></span>
                            @endif
                        </div>
                        <span class="stats-count" data-reactions-modal="{{ $post->id }}" id="likes-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                    </div>
                    <div class="stats-right">
                        <span class="stats-count" data-toggle-comments="{{ $post->id }}">
                            <span id="comments-count-{{ $post->id }}">{{ $post->comments_count }}</span> bình luận
                        </span>
                        <span class="stats-count">
                            <span id="shares-count-{{ $post->id }}">{{ $post->shares_count ?? 0 }}</span> chia sẻ
                        </span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="post-actions">
                    @php
                        $myReaction = $currentUserId ? $post->reactions->firstWhere('user_id', $currentUserId) : null;
                        $hasLiked = $currentUserId ? $post->likes->contains('user_id', $currentUserId) : false;
                        $reactionType = $myReaction->type ?? ($hasLiked ? 'like' : null);
                        $hasReacted = (bool) $reactionType;
                        $reactionTextMap = ['like'=>'Thích','love'=>'Yêu thích','haha'=>'Haha','wow'=>'Wow','sad'=>'Buồn','angry'=>'Phẫn nộ'];
                        $reactionIconMap = ['like'=>'far fa-thumbs-up','love'=>'fas fa-heart','haha'=>'fas fa-laugh','wow'=>'fas fa-surprise','sad'=>'fas fa-sad-tear','angry'=>'fas fa-angry'];
                    @endphp
                    
                    <div class="like-wrapper">
                        <button class="action-btn like-btn {{ $hasReacted ? 'reacted-'.$reactionType : '' }}"
                                data-react-endpoint="{{ route('posts.react',$post) }}"
                                data-post-id="{{ $post->id }}"
                                data-reaction="{{ $reactionType ?? '' }}">
                            <i class="{{ $reactionIconMap[$reactionType] ?? 'far fa-thumbs-up' }}"></i>
                            <span>{{ $reactionTextMap[$reactionType] ?? 'Thích' }}</span>
                        </button>
                        <div class="reactions-popover" data-for-post="{{ $post->id }}">
                            <div class="reaction like" data-type="like" title="Thích"><i class="far fa-thumbs-up"></i></div>
                            <div class="reaction love" data-type="love" title="Yêu thích"><i class="fas fa-heart"></i></div>
                            <div class="reaction haha" data-type="haha" title="Haha"><i class="fas fa-laugh"></i></div>
                            <div class="reaction wow" data-type="wow" title="Wow"><i class="fas fa-surprise"></i></div>
                            <div class="reaction sad" data-type="sad" title="Buồn"><i class="fas fa-sad-tear"></i></div>
                            <div class="reaction angry" data-type="angry" title="Phẫn nộ"><i class="fas fa-angry"></i></div>
                        </div>
                    </div>

                    <button class="action-btn" data-toggle-comments="{{ $post->id }}">
                        <i class="far fa-comment"></i>
                        <span>Bình luận</span>
                    </button>

                    <form method="POST" action="{{ route('posts.store') }}" style="margin:0">
                        @csrf
                        <input type="hidden" name="shared_post_id" value="{{ $post->id }}">
                        <button type="submit" class="action-btn" style="width:100%">
                            <i class="far fa-share-square"></i>
                            <span>Chia sẻ</span>
                        </button>
                    </form>
                </div>

                <!-- Comments Section -->
                <div class="comments-section" id="comments-{{ $post->id }}">
                    <form method="POST" action="{{ route('posts.comment',$post) }}" class="comment-form">
                        @csrf
                        <input name="content" class="comment-input" placeholder="{{ __('app.feed.write_comment') }}">
                        <button type="submit" class="comment-submit">Gửi</button>
                    </form>
                    
                    @foreach($post->comments as $c)
                    <div class="comment-item">
                        <div class="comment-avatar"></div>
                        <div style="flex:1">
                            <div class="comment-bubble">
                                <div class="comment-author">{{ $c->user->name ?? 'User' }}</div>
                                <div class="comment-text">{{ $c->content }}</div>
                            </div>
                            <div class="comment-actions">
                                <span class="comment-time">{{ $c->created_at->diffForHumans() }}</span>
                                @php $myCReaction = auth()->check() ? optional($c->reactions->firstWhere('user_id', auth()->id()))->type : null; @endphp
                                <span class="comment-action {{ $myCReaction ? 'active' : '' }}"
                                      data-react-endpoint="{{ route('comments.react',$c) }}"
                                      data-comment-id="{{ $c->id }}"
                                      data-reaction="{{ $myCReaction ?? '' }}">
                                    {{ $myCReaction ? (['like'=>'Thích','love'=>'Yêu thích','haha'=>'Haha','wow'=>'Wow','sad'=>'Buồn','angry'=>'Phẫn nộ'][$myCReaction] ?? 'Thích') : 'Thích' }}
                                </span>
                                <span class="comment-action">Trả lời</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div style="margin-top: 2rem;">{{ $posts->links() }}</div>
    </div>
</div>

<!-- Composer Modal -->
<div class="modal-overlay" id="composerModal">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('app.feed.create_post') }}</h3>
            <button class="modal-close" onclick="closeComposerModal()"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <textarea class="composer-textarea" name="content" rows="4" placeholder="{{ __('app.feed.whats_on_your_mind') }}"></textarea>
                <div class="media-preview" id="mediaPreview"></div>
                
                <div class="composer-toolbar">
                    <div class="toolbar-left">
                        <div style="position:relative">
                            <button type="button" class="visibility-btn" onclick="toggleDropdown('visibilityDropdown')">
                                <span id="visibilityText">Công khai</span>
                                <i class="fas fa-chevron-down" style="font-size:0.7rem"></i>
                            </button>
                            <div class="visibility-dropdown" id="visibilityDropdown">
                                <span class="visibility-option" data-vis="public">Công khai</span>
                                <span class="visibility-option" data-vis="friends">Bạn bè</span>
                                <span class="visibility-option" data-vis="only_me">Chỉ mình tôi</span>
                            </div>
                        </div>
                    </div>
                    <div class="toolbar-right">
                        <label class="toolbar-btn green" title="Ảnh/Video">
                            <i class="fas fa-photo-video"></i>
                            <input type="file" name="files[]" multiple style="display:none" id="composerFiles">
                        </label>
                        <button type="button" class="toolbar-btn blue" title="Gắn thẻ"><i class="fas fa-user-tag"></i></button>
                        <button type="button" class="toolbar-btn yellow" title="Cảm xúc"><i class="fas fa-smile"></i></button>
                    </div>
                </div>
                <input type="hidden" name="visibility" id="visibilityField" value="public">
            </div>
            <div class="modal-footer">
                <button type="submit" class="submit-btn">Đăng bài</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Post Modal Template -->
@foreach($posts as $post)
@php $canEdit = auth()->check() && ((auth()->id() === $post->user_id) || in_array(auth()->user()->user_role,['admin','super_admin'])); @endphp
@if($canEdit)
<div class="modal-overlay" id="editModal{{ $post->id }}">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">Chỉnh sửa bài viết</h3>
            <button class="modal-close" onclick="closeEditModal({{ $post->id }})"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('posts.update',$post) }}">
            @csrf @method('PUT')
            <div class="modal-body">
                <textarea class="composer-textarea" name="content" rows="5">{{ $post->content }}</textarea>
            </div>
            <div class="modal-footer">
                <button type="submit" class="submit-btn">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

<!-- Confirm Delete Modal -->
<div class="modal-overlay" id="confirmModal">
    <div class="modal-content" style="max-width:400px" onclick="event.stopPropagation()">
        <div class="modal-body" style="padding:1.5rem">
            <div style="display:flex;gap:1rem;align-items:flex-start">
                <div class="confirm-icon"><i class="fas fa-trash"></i></div>
                <div>
                    <h4 style="color:#fff;margin:0 0 0.25rem 0;font-family:'Rajdhani',sans-serif;font-weight:700">Xóa bài viết?</h4>
                    <p style="color:#94a3b8;margin:0;font-size:0.9rem">Hành động này không thể hoàn tác.</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="confirm-actions">
                <button class="btn-cancel" onclick="closeConfirmModal()">Hủy</button>
                <button class="btn-danger" id="confirmDeleteBtn">Xóa</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Modal functions
function openComposerModal() {
    document.getElementById('composerModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeComposerModal() {
    document.getElementById('composerModal').classList.remove('show');
    document.body.style.overflow = '';
}
function openEditModal(id) {
    document.getElementById('editModal' + id).classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeEditModal(id) {
    document.getElementById('editModal' + id).classList.remove('show');
    document.body.style.overflow = '';
}
function closeConfirmModal() {
    document.getElementById('confirmModal').classList.remove('show');
    document.body.style.overflow = '';
}

// Dropdown toggle
function toggleDropdown(id) {
    const el = document.getElementById(id);
    el.classList.toggle('show');
}

// Close dropdowns on outside click
document.addEventListener('click', (e) => {
    document.querySelectorAll('.post-dropdown.show, .visibility-dropdown.show').forEach(el => {
        if (!el.contains(e.target) && !e.target.closest('[onclick*="toggleDropdown"]')) {
            el.classList.remove('show');
        }
    });
});

// Close modal on overlay click
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    });
});

// Toggle comments
document.querySelectorAll('[data-toggle-comments]').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.toggleComments;
        document.getElementById('comments-' + id).classList.toggle('show');
    });
});

// File preview
document.getElementById('composerFiles')?.addEventListener('change', (e) => {
    const preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';
    [...e.target.files].forEach(file => {
        const div = document.createElement('div');
        div.className = 'media-preview-item';
        if (file.type.startsWith('video')) {
            const v = document.createElement('video');
            v.src = URL.createObjectURL(file);
            v.controls = true;
            div.appendChild(v);
        } else {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            div.appendChild(img);
        }
        preview.appendChild(div);
    });
});

// Visibility selector
document.querySelectorAll('.visibility-option').forEach(opt => {
    opt.addEventListener('click', () => {
        document.getElementById('visibilityField').value = opt.dataset.vis;
        document.getElementById('visibilityText').textContent = opt.textContent;
        document.getElementById('visibilityDropdown').classList.remove('show');
    });
});

// Reactions
const reactionTextMap = { like: 'Thích', love: 'Yêu thích', haha: 'Haha', wow: 'Wow', sad: 'Buồn', angry: 'Phẫn nộ' };
const reactionIconMap = { like: 'far fa-thumbs-up', love: 'fas fa-heart', haha: 'fas fa-laugh', wow: 'fas fa-surprise', sad: 'fas fa-sad-tear', angry: 'fas fa-angry' };

document.querySelectorAll('.like-wrapper').forEach(wrapper => {
    const button = wrapper.querySelector('.like-btn');
    const pop = wrapper.querySelector('.reactions-popover');
    const postId = button.dataset.postId;
    let hideTimer = null;

    function showPop() { clearTimeout(hideTimer); pop.classList.add('show'); }
    function hidePop() { hideTimer = setTimeout(() => pop.classList.remove('show'), 200); }

    button.addEventListener('mouseenter', showPop);
    button.addEventListener('mouseleave', hidePop);
    pop.addEventListener('mouseenter', showPop);
    pop.addEventListener('mouseleave', hidePop);

    button.addEventListener('click', () => {
        const current = button.dataset.reaction;
        if (current) {
            updateReaction('');
            optimisticCount(-1);
            sendReact('none');
        } else {
            updateReaction('like');
            optimisticCount(1);
            sendReact('like');
        }
    });

    pop.querySelectorAll('.reaction').forEach(el => {
        el.addEventListener('click', () => {
            const type = el.dataset.type;
            const prev = button.dataset.reaction;
            if (!prev) optimisticCount(1);
            updateReaction(type);
            sendReact(type);
            pop.classList.remove('show');
        });
    });

    function updateReaction(type) {
        button.dataset.reaction = type;
        const icon = button.querySelector('i');
        icon.className = reactionIconMap[type] || 'far fa-thumbs-up';
        button.querySelector('span').textContent = reactionTextMap[type] || 'Thích';
        
        ['like','love','haha','wow','sad','angry'].forEach(t => button.classList.remove('reacted-'+t));
        if (type) button.classList.add('reacted-' + type);
    }

    function optimisticCount(delta) {
        const countEl = document.getElementById('likes-count-' + postId);
        const wrapEl = document.getElementById('likes-wrap-' + postId);
        let count = parseInt(countEl?.textContent || '0', 10);
        count = Math.max(0, count + delta);
        if (countEl) countEl.textContent = String(count);
        if (wrapEl) wrapEl.classList.toggle('hidden', count === 0);
    }

    function sendReact(type) {
        const url = button.dataset.reactEndpoint;
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const form = new FormData();
        form.append('type', type);
        fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: form });
    }
});

// Delete confirmation
let pendingDeleteForm = null;
document.querySelectorAll('.delete-post-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        pendingDeleteForm = form;
        document.getElementById('confirmModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    });
});

document.getElementById('confirmDeleteBtn')?.addEventListener('click', () => {
    if (pendingDeleteForm) pendingDeleteForm.submit();
    closeConfirmModal();
});

// Auto refresh times
function refreshPostTimes() {
    const now = Date.now();
    document.querySelectorAll('.post-time[data-timestamp]').forEach(el => {
        const ts = Date.parse(el.dataset.timestamp);
        const diffSec = Math.max(1, Math.floor((now - ts) / 1000));
        let text;
        if (diffSec < 60) text = `${diffSec} giây trước`;
        else if (diffSec < 3600) text = `${Math.floor(diffSec / 60)} phút trước`;
        else if (diffSec < 86400) text = `${Math.floor(diffSec / 3600)} giờ trước`;
        else text = `${Math.floor(diffSec / 86400)} ngày trước`;
        el.textContent = text;
    });
}
refreshPostTimes();
setInterval(refreshPostTimes, 60000);
</script>
@endpush
@endsection
