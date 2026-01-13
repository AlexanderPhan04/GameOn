@extends('layouts.app')

@section('title','Bài viết')

@push('styles')
<style>
    .posts-page {
        background: linear-gradient(180deg, #000814 0%, #0d1b2a 100%);
        min-height: 100vh;
        padding-left: 280px; /* Space for left sidebar */
    }
    @media (max-width: 1200px) {
        .posts-page {
            padding-left: 0;
        }
    }
    .feed-container {
        max-width: 680px;
        margin: 0 auto;
        padding: 0.5rem 1rem 2rem 1rem;
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
        display: flex;
    }
    .like-wrapper .action-btn {
        flex: 1;
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
    /* Comment Like Wrapper */
    .comment-like-wrapper {
        position: relative;
        display: inline-block;
    }
    .comment-like-btn.reacted-like { color: #3b82f6; }
    .comment-like-btn.reacted-love { color: #ef4444; }
    .comment-like-btn.reacted-haha { color: #f59e0b; }
    .comment-like-btn.reacted-wow { color: #06b6d4; }
    .comment-like-btn.reacted-sad { color: #64748b; }
    .comment-like-btn.reacted-angry { color: #f97316; }
    
    /* Comment Reactions Popover */
    .comment-reactions-popover {
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, #0d1b2a 0%, #000022 100%);
        border: 1px solid rgba(0, 229, 255, 0.25);
        border-radius: 20px;
        padding: 0.35rem 0.5rem;
        display: none;
        gap: 0.15rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5), 0 0 15px rgba(0, 229, 255, 0.1);
        z-index: 100;
    }
    .comment-reactions-popover.show {
        display: flex;
    }
    .comment-reactions-popover .reaction {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        color: #fff;
        font-size: 0.75rem;
    }
    .comment-reactions-popover .reaction:hover {
        transform: scale(1.3) translateY(-3px);
    }
    .comment-reactions-popover.small .reaction {
        width: 24px;
        height: 24px;
        font-size: 0.65rem;
    }
    .comment-reactions-popover .reaction.like { color: #3b82f6; }
    .comment-reactions-popover .reaction.love { color: #ef4444; }
    .comment-reactions-popover .reaction.haha { color: #f59e0b; }
    .comment-reactions-popover .reaction.wow { color: #06b6d4; }
    .comment-reactions-popover .reaction.sad { color: #64748b; }
    .comment-reactions-popover .reaction.angry { color: #f97316; }
    
    /* Comment Likes Count */
    .comment-likes-count {
        color: #64748b;
        font-size: 0.7rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .comment-likes-count.hidden {
        display: none;
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
<!-- Left Sidebar -->
@include('posts.partials.left-sidebar')

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
                @if($post->shared_post_id)
                    @if($post->sharedPost)
                    @php $sp = $post->sharedPost; $spAvatar = get_avatar_url($sp->user?->avatar); @endphp
                    <div class="shared-post">
                        @if($sp->media && $sp->media->count())
                            @php $spImages = $sp->media->where('type','image'); @endphp
                            @if($spImages->count() == 1)
                                <img src="{{ asset('uploads/'.$spImages->first()->path) }}" class="post-media-single">
                            @elseif($spImages->count() >= 2)
                                <div class="post-media-grid cols-2">
                                    @foreach($spImages->take(4) as $i => $im)
                                    <div class="media-item">
                                        <img src="{{ asset('uploads/'.$im->path) }}">
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
                    @else
                    {{-- Original post was deleted --}}
                    <div class="shared-post" style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem;">
                            <div style="width: 40px; height: 40px; background: rgba(239,68,68,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-exclamation-triangle" style="color: #ef4444; font-size: 1rem;"></i>
                            </div>
                            <div>
                                <div style="color: #ef4444; font-weight: 600; font-size: 0.9rem;">Nội dung không khả dụng</div>
                                <div style="color: #94a3b8; font-size: 0.8rem;">Bài viết gốc đã bị xóa hoặc không còn tồn tại.</div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif

                <!-- Post Media -->
                @if($post->media && $post->media->count())
                    @php
                        $images = $post->media->where('type','image');
                        $videos = $post->media->where('type','!=','image');
                        $imgCount = $images->count();
                    @endphp
                    @if($imgCount == 1)
                        <img src="{{ asset('uploads/'.$images->first()->path) }}" class="post-media-single">
                    @elseif($imgCount == 2)
                        <div class="post-media-grid cols-2">
                            @foreach($images as $im)
                            <div class="media-item"><img src="{{ asset('uploads/'.$im->path) }}"></div>
                            @endforeach
                        </div>
                    @elseif($imgCount >= 3)
                        <div class="post-media-grid cols-3">
                            @foreach($images->take(5) as $i => $im)
                            <div class="media-item">
                                <img src="{{ asset('uploads/'.$im->path) }}">
                                @if($i == 4 && $imgCount > 5)
                                <div class="media-more-overlay">+{{ $imgCount - 5 }}</div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @endif
                    @foreach($videos as $v)
                        <video controls class="post-media-single"><source src="{{ asset('uploads/'.$v->path) }}"></video>
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
                        <div class="reaction-icons" id="reaction-icons-{{ $post->id }}">
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
                            <span id="comments-count-{{ $post->id }}">{{ $post->comments_count }}</span> {{ __('app.feed.comments') }}
                        </span>
                        <span class="stats-count {{ auth()->id() === $post->user_id && ($post->shares_count ?? 0) > 0 ? 'clickable' : '' }}" 
                              @if(auth()->id() === $post->user_id && ($post->shares_count ?? 0) > 0)
                              data-shares-modal="{{ $post->id }}"
                              onclick="openSharesModal({{ $post->id }})"
                              title="{{ __('app.feed.view_who_shared') }}"
                              @endif>
                            <span id="shares-count-{{ $post->id }}">{{ $post->shares_count ?? 0 }}</span> {{ __('app.feed.shares') }}
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
                        $reactionTextMap = ['like'=>__('app.feed.like'),'love'=>__('app.feed.love'),'haha'=>__('app.feed.haha'),'wow'=>__('app.feed.wow'),'sad'=>__('app.feed.sad'),'angry'=>__('app.feed.angry')];
                        $reactionIconMap = ['like'=>'far fa-thumbs-up','love'=>'fas fa-heart','haha'=>'fas fa-laugh','wow'=>'fas fa-surprise','sad'=>'fas fa-sad-tear','angry'=>'fas fa-angry'];
                    @endphp
                    
                    <div class="like-wrapper">
                        <button class="action-btn like-btn {{ $hasReacted ? 'reacted-'.$reactionType : '' }}"
                                data-react-endpoint="{{ route('posts.react',$post) }}"
                                data-post-id="{{ $post->id }}"
                                data-reaction="{{ $reactionType ?? '' }}">
                            <i class="{{ $reactionIconMap[$reactionType] ?? 'far fa-thumbs-up' }}"></i>
                            <span>{{ $reactionTextMap[$reactionType] ?? __('app.feed.like') }}</span>
                        </button>
                        <div class="reactions-popover" data-for-post="{{ $post->id }}">
                            <div class="reaction like" data-type="like" title="{{ __('app.feed.like') }}"><i class="far fa-thumbs-up"></i></div>
                            <div class="reaction love" data-type="love" title="{{ __('app.feed.love') }}"><i class="fas fa-heart"></i></div>
                            <div class="reaction haha" data-type="haha" title="{{ __('app.feed.haha') }}"><i class="fas fa-laugh"></i></div>
                            <div class="reaction wow" data-type="wow" title="{{ __('app.feed.wow') }}"><i class="fas fa-surprise"></i></div>
                            <div class="reaction sad" data-type="sad" title="{{ __('app.feed.sad') }}"><i class="fas fa-sad-tear"></i></div>
                            <div class="reaction angry" data-type="angry" title="{{ __('app.feed.angry') }}"><i class="fas fa-angry"></i></div>
                        </div>
                    </div>

                    <button class="action-btn" data-toggle-comments="{{ $post->id }}">
                        <i class="far fa-comment"></i>
                        <span>{{ __('app.feed.comment') }}</span>
                    </button>

                    <button type="button" class="action-btn" onclick="openShareModal({{ $post->id }})">
                        <i class="far fa-share-square"></i>
                        <span>{{ __('app.feed.share') }}</span>
                    </button>
                </div>

                <!-- Comments Section -->
                <div class="comments-section" id="comments-{{ $post->id }}">
                    <form method="POST" action="{{ route('posts.comment',$post) }}" class="comment-form" data-post-id="{{ $post->id }}">
                        @csrf
                        <input type="hidden" name="parent_id" value="" class="reply-parent-id">
                        <div class="reply-indicator" style="display:none; padding: 0.5rem; background: rgba(0,229,255,0.1); border-radius: 8px 8px 0 0; font-size: 0.85rem; color: #00E5FF;">
                            <span class="reply-to-text"></span>
                            <button type="button" class="cancel-reply" style="background:none;border:none;color:#ef4444;cursor:pointer;margin-left:0.5rem;"><i class="fas fa-times"></i></button>
                        </div>
                        <input name="content" class="comment-input" placeholder="{{ __('app.feed.write_comment') }}">
                        <button type="submit" class="comment-submit">Gửi</button>
                    </form>
                    
                    @php 
                        $parentComments = $post->comments->whereNull('parent_id');
                        $initialLimit = 3;
                        $totalComments = $parentComments->count();
                        $showLoadMore = $totalComments > $initialLimit;
                        $limitedComments = $parentComments->take($initialLimit);
                    @endphp
                    
                    @if($showLoadMore)
                    <button class="load-more-comments-btn" 
                            data-post-id="{{ $post->id }}" 
                            data-offset="{{ $initialLimit }}"
                            data-total="{{ $totalComments }}"
                            onclick="loadMoreComments({{ $post->id }}, this)"
                            style="width: 100%; padding: 0.75rem; background: transparent; border: 1px dashed rgba(0,229,255,0.3); border-radius: 8px; color: #00E5FF; cursor: pointer; font-size: 0.85rem; margin-bottom: 0.75rem; transition: all 0.2s;">
                        <i class="fas fa-comments"></i> {{ __('app.feed.view_more_comments', ['count' => $totalComments - $initialLimit]) }}
                    </button>
                    @endif
                    
                    <div class="comments-list" id="comments-list-{{ $post->id }}">
                    @foreach($limitedComments as $c)
                    @php $cAvatar = get_avatar_url($c->user?->avatar); @endphp
                    <div class="comment-item" id="comment-{{ $c->id }}">
                        <div class="comment-avatar"><img src="{{ $cAvatar }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;"></div>
                        <div style="flex:1">
                            <div class="comment-bubble">
                                <div class="comment-author">{{ $c->user->name ?? 'User' }}</div>
                                <div class="comment-text">{{ $c->content }}</div>
                            </div>
                            <div class="comment-actions">
                                <span class="comment-time">{{ $c->created_at->diffForHumans() }}</span>
                                @php 
                                    $myCReaction = auth()->check() ? optional($c->reactions->firstWhere('user_id', auth()->id()))->type : null;
                                    $commentReactionTextMap = ['like'=>__('app.feed.like'),'love'=>__('app.feed.love'),'haha'=>__('app.feed.haha'),'wow'=>__('app.feed.wow'),'sad'=>__('app.feed.sad'),'angry'=>__('app.feed.angry')];
                                @endphp
                                <div class="comment-like-wrapper">
                                    <span class="comment-action comment-like-btn {{ $myCReaction ? 'reacted-'.$myCReaction : '' }}"
                                          data-react-endpoint="{{ route('comments.react',$c) }}"
                                          data-comment-id="{{ $c->id }}"
                                          data-reaction="{{ $myCReaction ?? '' }}">
                                        {{ $myCReaction ? ($commentReactionTextMap[$myCReaction] ?? __('app.feed.like')) : __('app.feed.like') }}
                                    </span>
                                    <div class="comment-reactions-popover">
                                        <div class="reaction like" data-type="like" title="{{ __('app.feed.like') }}"><i class="far fa-thumbs-up"></i></div>
                                        <div class="reaction love" data-type="love" title="{{ __('app.feed.love') }}"><i class="fas fa-heart"></i></div>
                                        <div class="reaction haha" data-type="haha" title="{{ __('app.feed.haha') }}"><i class="fas fa-laugh"></i></div>
                                        <div class="reaction wow" data-type="wow" title="{{ __('app.feed.wow') }}"><i class="fas fa-surprise"></i></div>
                                        <div class="reaction sad" data-type="sad" title="{{ __('app.feed.sad') }}"><i class="fas fa-sad-tear"></i></div>
                                        <div class="reaction angry" data-type="angry" title="{{ __('app.feed.angry') }}"><i class="fas fa-angry"></i></div>
                                    </div>
                                </div>
                                <span class="comment-likes-count {{ $c->likes_count > 0 ? '' : 'hidden' }}" id="comment-likes-{{ $c->id }}">
                                    <i class="far fa-thumbs-up"></i> <span class="count">{{ $c->likes_count }}</span>
                                </span>
                                <span class="comment-action reply-btn" data-comment-id="{{ $c->id }}" data-comment-author="{{ $c->user->name ?? 'User' }}" data-post-id="{{ $post->id }}">{{ __('app.feed.reply') }}</span>
                            </div>
                            
                            {{-- Show replies --}}
                            @if($c->replies && $c->replies->count() > 0)
                            <div class="replies-container" style="margin-top: 0.75rem; margin-left: 1rem; border-left: 2px solid rgba(0,229,255,0.2); padding-left: 0.75rem;">
                                @foreach($c->replies as $reply)
                                @php $myReplyReaction = auth()->check() ? optional($reply->reactions->firstWhere('user_id', auth()->id()))->type : null; @endphp
                                @php $replyAvatar = get_avatar_url($reply->user?->avatar); @endphp
                                <div class="comment-item reply-item" style="margin-bottom: 0.5rem;">
                                    <div class="comment-avatar" style="width:28px;height:28px;"><img src="{{ $replyAvatar }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;"></div>
                                    <div style="flex:1">
                                        <div class="comment-bubble" style="padding: 0.5rem 0.75rem;">
                                            <div class="comment-author" style="font-size:0.8rem;">{{ $reply->user->name ?? 'User' }}</div>
                                            <div class="comment-text" style="font-size:0.85rem;"><span style="color: #00E5FF; font-weight: 500; margin-right: 4px;">&#64;{{ $c->user->name ?? 'User' }}</span>{{ $reply->content }}</div>
                                        </div>
                                        <div class="comment-actions" style="gap:0.5rem;">
                                            <span class="comment-time" style="font-size:0.7rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                            <div class="comment-like-wrapper" style="font-size:0.75rem;">
                                                <span class="comment-action comment-like-btn {{ $myReplyReaction ? 'reacted-'.$myReplyReaction : '' }}"
                                                      data-react-endpoint="{{ route('comments.react', $reply) }}"
                                                      data-comment-id="{{ $reply->id }}"
                                                      data-reaction="{{ $myReplyReaction ?? '' }}">
                                                    {{ $myReplyReaction ? ($commentReactionTextMap[$myReplyReaction] ?? __('app.feed.like')) : __('app.feed.like') }}
                                                </span>
                                                <div class="comment-reactions-popover small">
                                                    <div class="reaction like" data-type="like" title="{{ __('app.feed.like') }}"><i class="far fa-thumbs-up"></i></div>
                                                    <div class="reaction love" data-type="love" title="{{ __('app.feed.love') }}"><i class="fas fa-heart"></i></div>
                                                    <div class="reaction haha" data-type="haha" title="{{ __('app.feed.haha') }}"><i class="fas fa-laugh"></i></div>
                                                    <div class="reaction wow" data-type="wow" title="{{ __('app.feed.wow') }}"><i class="fas fa-surprise"></i></div>
                                                    <div class="reaction sad" data-type="sad" title="{{ __('app.feed.sad') }}"><i class="fas fa-sad-tear"></i></div>
                                                    <div class="reaction angry" data-type="angry" title="{{ __('app.feed.angry') }}"><i class="fas fa-angry"></i></div>
                                                </div>
                                            </div>
                                            <span class="comment-likes-count {{ $reply->likes_count > 0 ? '' : 'hidden' }}" id="comment-likes-{{ $reply->id }}" style="font-size:0.7rem;">
                                                <i class="far fa-thumbs-up"></i> <span class="count">{{ $reply->likes_count }}</span>
                                            </span>
                                            <span class="comment-action reply-btn" data-comment-id="{{ $c->id }}" data-comment-author="{{ $reply->user->name ?? 'User' }}" data-post-id="{{ $post->id }}" style="font-size:0.75rem;">{{ __('app.feed.reply') }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    </div>
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
                            <input type="file" name="files[]" multiple accept="image/*,video/*" style="display:none" id="composerFiles">
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
            <h3 class="modal-title">{{ $post->shared_post_id ? __('app.feed.edit_shared_post') : __('app.feed.edit_post') }}</h3>
            <button class="modal-close" onclick="closeEditModal({{ $post->id }})"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('posts.update',$post) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-body" style="padding: 1.25rem; max-height: 60vh; overflow-y: auto;">
                <textarea class="composer-textarea" name="content" rows="4" style="width: 100%; min-height: 100px; background: rgba(0,0,85,0.3); border: 1px solid rgba(0,229,255,0.2); border-radius: 8px; padding: 1rem; color: #fff; font-family: 'Inter', sans-serif; font-size: 1rem; resize: vertical; box-sizing: border-box;" placeholder="{{ $post->shared_post_id ? __('app.feed.enter_share_content') : __('app.feed.enter_content') }}">{{ $post->content }}</textarea>
                
                @if(!$post->shared_post_id)
                {{-- Existing Media - only for non-shared posts --}}
                @if($post->media->count() > 0)
                <div style="margin-top: 1rem;">
                    <label style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Ảnh/Video hiện tại:</label>
                    <div class="edit-media-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 0.75rem;">
                        @foreach($post->media as $media)
                        <div class="edit-media-item" style="position: relative; border-radius: 8px; overflow: hidden; aspect-ratio: 1; background: rgba(0,0,85,0.3);">
                            @if($media->type === 'video')
                            <video src="{{ asset('uploads/' . $media->path) }}" style="width: 100%; height: 100%; object-fit: cover;"></video>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; font-size: 1.5rem;"><i class="fas fa-play-circle"></i></div>
                            @else
                            <img src="{{ asset('uploads/' . $media->path) }}" style="width: 100%; height: 100%; object-fit: cover;" alt="">
                            @endif
                            <label style="position: absolute; top: 4px; right: 4px; background: rgba(239,68,68,0.9); color: #fff; width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; font-size: 0.7rem;">
                                <input type="checkbox" name="delete_media[]" value="{{ $media->id }}" style="display: none;" onchange="this.parentElement.style.background = this.checked ? '#ef4444' : 'rgba(239,68,68,0.9)'; this.parentElement.querySelector('i').className = this.checked ? 'fas fa-check' : 'fas fa-times';">
                                <i class="fas fa-times"></i>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <p style="color: #64748b; font-size: 0.75rem; margin-top: 0.5rem;"><i class="fas fa-info-circle"></i> Click vào <i class="fas fa-times"></i> để đánh dấu xóa ảnh</p>
                </div>
                @endif
                
                {{-- Add New Media - only for non-shared posts --}}
                <div style="margin-top: 1rem;">
                    <label style="color: #94a3b8; font-size: 0.85rem; margin-bottom: 0.5rem; display: block;">Thêm ảnh/video mới:</label>
                    <div class="edit-upload-area" style="border: 2px dashed rgba(0,229,255,0.3); border-radius: 8px; padding: 1rem; text-align: center; cursor: pointer; transition: all 0.3s;" onclick="document.getElementById('editFiles{{ $post->id }}').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; color: #00E5FF; margin-bottom: 0.5rem;"></i>
                        <p style="color: #94a3b8; font-size: 0.85rem; margin: 0;">Click để chọn ảnh/video</p>
                        <input type="file" id="editFiles{{ $post->id }}" name="files[]" multiple accept="image/*,video/*" style="display: none;" onchange="previewEditFiles(this, {{ $post->id }})">
                    </div>
                    <div id="editPreview{{ $post->id }}" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.5rem; margin-top: 0.75rem;"></div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="submit" class="submit-btn">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

<!-- Share Post Modal -->
<div class="modal-overlay" id="shareModal">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title">{{ __('app.feed.share_post') }}</h3>
            <button class="modal-close" onclick="closeShareModal()"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('posts.store') }}" id="shareForm">
            @csrf
            <input type="hidden" name="shared_post_id" id="sharePostId" value="">
            <div class="modal-body" style="padding: 1.25rem;">
                <textarea class="composer-textarea" name="content" rows="3" placeholder="{{ __('app.feed.enter_share_content') }}" style="width: 100%; min-height: 80px; background: rgba(0,0,85,0.3); border: 1px solid rgba(0,229,255,0.2); border-radius: 8px; padding: 1rem; color: #fff; font-family: 'Inter', sans-serif; font-size: 1rem; resize: vertical; box-sizing: border-box;"></textarea>
                
                {{-- Preview of shared post --}}
                <div id="sharePreview" style="margin-top: 1rem; padding: 1rem; background: rgba(0,0,85,0.2); border: 1px solid rgba(0,229,255,0.1); border-radius: 8px;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem;">
                        <div id="sharePreviewAvatar" style="width: 32px; height: 32px; border-radius: 50%; background: linear-gradient(135deg, #00E5FF 0%, #000055 100%);"></div>
                        <div>
                            <div id="sharePreviewName" style="color: #fff; font-weight: 600; font-size: 0.85rem;"></div>
                            <div id="sharePreviewTime" style="color: #64748b; font-size: 0.75rem;"></div>
                        </div>
                    </div>
                    <div id="sharePreviewContent" style="color: #94a3b8; font-size: 0.9rem;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="submit-btn">
                    <i class="fas fa-share"></i> {{ __('app.feed.share_now') }}
                </button>
            </div>
        </form>
    </div>
</div>

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

<!-- Shares List Modal -->
<div class="modal-overlay" id="sharesListModal">
    <div class="modal-content" style="max-width:450px" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 class="modal-title"><i class="fas fa-share-alt"></i> {{ __('app.feed.shared_by') }}</h3>
            <button class="modal-close" onclick="closeSharesListModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="padding: 0; max-height: 400px; overflow-y: auto;">
            <div id="sharesListLoading" style="text-align: center; padding: 2rem; color: #94a3b8;">
                <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                <p style="margin: 0;">{{ __('app.common.loading') }}</p>
            </div>
            <div id="sharesListContent" style="display: none;"></div>
            <div id="sharesListEmpty" style="display: none; text-align: center; padding: 2rem; color: #64748b;">
                <i class="fas fa-share" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                <p style="margin: 0;">{{ __('app.feed.no_shares_yet') }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Translation strings for JavaScript
const feedTranslations = {
    like: @json(__('app.feed.like')),
    love: @json(__('app.feed.love')),
    haha: @json(__('app.feed.haha')),
    wow: @json(__('app.feed.wow')),
    sad: @json(__('app.feed.sad')),
    angry: @json(__('app.feed.angry')),
    comments: @json(__('app.feed.comments')),
    shares: @json(__('app.feed.shares'))
};

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

// Share modal functions
function openShareModal(postId) {
    // Get post info from the page using correct selectors
    const postElement = document.querySelector(`[data-post-card="${postId}"]`);
    if (postElement) {
        const authorName = postElement.querySelector('.post-author-name')?.textContent?.trim() || 'User';
        const postTime = postElement.querySelector('.post-time')?.textContent?.trim() || '';
        const postContent = postElement.querySelector('.post-content')?.textContent?.trim() || '';
        const avatarDiv = postElement.querySelector('.post-avatar');
        
        document.getElementById('sharePreviewName').textContent = authorName;
        document.getElementById('sharePreviewTime').textContent = postTime;
        document.getElementById('sharePreviewContent').textContent = postContent.length > 150 ? postContent.substring(0, 150) + '...' : postContent;
        
        // Get avatar from background-image style
        if (avatarDiv) {
            const bgImage = avatarDiv.style.backgroundImage;
            const avatarUrl = bgImage.replace(/url\(['"]?([^'"]+)['"]?\)/, '$1');
            if (avatarUrl && avatarUrl !== 'none') {
                document.getElementById('sharePreviewAvatar').innerHTML = `<img src="${avatarUrl}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">`;
            }
        }
    }
    
    document.getElementById('sharePostId').value = postId;
    document.getElementById('shareModal').classList.add('show');
    document.body.style.overflow = 'hidden';
}
function closeShareModal() {
    document.getElementById('shareModal').classList.remove('show');
    document.body.style.overflow = '';
    document.getElementById('shareForm').reset();
}

// Shares List Modal functions
function openSharesModal(postId) {
    const modal = document.getElementById('sharesListModal');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
    
    // Show loading, hide content
    document.getElementById('sharesListLoading').style.display = 'block';
    document.getElementById('sharesListContent').style.display = 'none';
    document.getElementById('sharesListEmpty').style.display = 'none';
    
    // Fetch shares list
    fetch(`/posts/${postId}/shares`, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('sharesListLoading').style.display = 'none';
        
        const currentUserId = {{ auth()->id() ?? 'null' }};
        
        if (data.data && data.data.length > 0) {
            const content = document.getElementById('sharesListContent');
            content.innerHTML = data.data.map(share => {
                const profileUrl = share.user?.id === currentUserId 
                    ? '/profile' 
                    : `/profile/${share.user?.id}`;
                return `
                <a href="${profileUrl}" style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(0,229,255,0.1); text-decoration: none; transition: background 0.2s;" onmouseover="this.style.background='rgba(0,229,255,0.1)'" onmouseout="this.style.background='transparent'">
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #00E5FF 0%, #000055 100%); overflow: hidden; flex-shrink: 0;">
                        ${share.user?.avatar ? `<img src="${share.user.avatar}" style="width: 100%; height: 100%; object-fit: cover;">` : '<i class="fas fa-user" style="display: flex; align-items: center; justify-content: center; height: 100%; color: white;"></i>'}
                    </div>
                    <div style="flex: 1;">
                        <div style="color: #fff; font-weight: 600; font-size: 0.9rem;">${share.user?.name || 'Người dùng'}</div>
                        <div style="color: #64748b; font-size: 0.75rem;">${share.created_at || ''}</div>
                    </div>
                </a>
            `}).join('');
            content.style.display = 'block';
        } else {
            document.getElementById('sharesListEmpty').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error fetching shares:', error);
        document.getElementById('sharesListLoading').style.display = 'none';
        document.getElementById('sharesListEmpty').style.display = 'block';
    });
}

function closeSharesListModal() {
    document.getElementById('sharesListModal').classList.remove('show');
    document.body.style.overflow = '';
}

// Load more comments function
async function loadMoreComments(postId, button) {
    const offset = parseInt(button.dataset.offset);
    const total = parseInt(button.dataset.total);
    const remaining = total - offset;
    
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tải...';
    button.disabled = true;
    
    try {
        // Fetch comments starting after the already displayed ones
        const response = await fetch(`/posts/${postId}/comments?offset=${offset}&limit=${remaining}`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const data = await response.json();
        
        if (data.comments && data.comments.length > 0) {
            const commentsList = document.getElementById('comments-list-' + postId);
            const currentUserId = {{ auth()->id() ?? 'null' }};
            const reactionTextMap = feedTranslations;
            
            // Build HTML for new comments and prepend
            const commentsHtml = data.comments.map(c => {
                const profileUrl = c.user_id === currentUserId ? '/profile' : `/profile/${c.user_id}`;
                const reactionClass = c.my_reaction ? `reacted-${c.my_reaction}` : '';
                const reactionText = c.my_reaction ? (reactionTextMap[c.my_reaction] || feedTranslations.like) : feedTranslations.like;
                
                let repliesHtml = '';
                if (c.replies && c.replies.length > 0) {
                    repliesHtml = `
                        <div class="replies-container" style="margin-top: 0.75rem; margin-left: 1rem; border-left: 2px solid rgba(0,229,255,0.2); padding-left: 0.75rem;">
                            ${c.replies.map(reply => {
                                const replyProfileUrl = reply.user_id === currentUserId ? '/profile' : `/profile/${reply.user_id}`;
                                const replyReactionClass = reply.my_reaction ? `reacted-${reply.my_reaction}` : '';
                                const replyReactionText = reply.my_reaction ? (reactionTextMap[reply.my_reaction] || feedTranslations.like) : feedTranslations.like;
                                return `
                                    <div class="comment-item reply-item" style="margin-bottom: 0.5rem;">
                                        <div class="comment-avatar" style="width:28px;height:28px;">
                                            <a href="${replyProfileUrl}"><img src="${reply.user_avatar}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;"></a>
                                        </div>
                                        <div style="flex:1">
                                            <div class="comment-bubble" style="padding: 0.5rem 0.75rem;">
                                                <div class="comment-author" style="font-size:0.8rem;">${reply.user_name}</div>
                                                <div class="comment-text" style="font-size:0.85rem;"><span style="color: #00E5FF; font-weight: 500; margin-right: 4px;">@${reply.parent_user_name}</span>${reply.content}</div>
                                            </div>
                                            <div class="comment-actions" style="gap:0.5rem;">
                                                <span class="comment-time" style="font-size:0.7rem;">${reply.created_at}</span>
                                                <div class="comment-like-wrapper" style="font-size:0.75rem;">
                                                    <span class="comment-action comment-like-btn ${replyReactionClass}"
                                                          data-react-endpoint="/comments/${reply.id}/react"
                                                          data-comment-id="${reply.id}"
                                                          data-reaction="${reply.my_reaction || ''}">${replyReactionText}</span>
                                                    <div class="comment-reactions-popover small">
                                                        <div class="reaction like" data-type="like" title="Thích"><i class="far fa-thumbs-up"></i></div>
                                                        <div class="reaction love" data-type="love" title="Yêu thích"><i class="fas fa-heart"></i></div>
                                                        <div class="reaction haha" data-type="haha" title="Haha"><i class="fas fa-laugh"></i></div>
                                                        <div class="reaction wow" data-type="wow" title="Wow"><i class="fas fa-surprise"></i></div>
                                                        <div class="reaction sad" data-type="sad" title="Buồn"><i class="fas fa-sad-tear"></i></div>
                                                        <div class="reaction angry" data-type="angry" title="Phẫn nộ"><i class="fas fa-angry"></i></div>
                                                    </div>
                                                </div>
                                                ${reply.likes_count > 0 ? `<span class="comment-likes-count" style="font-size:0.7rem;"><i class="far fa-thumbs-up"></i> <span class="count">${reply.likes_count}</span></span>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    `;
                }
                
                return `
                    <div class="comment-item" id="comment-${c.id}">
                        <div class="comment-avatar"><a href="${profileUrl}"><img src="${c.user_avatar}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;"></a></div>
                        <div style="flex:1">
                            <div class="comment-bubble">
                                <div class="comment-author">${c.user_name}</div>
                                <div class="comment-text">${c.content}</div>
                            </div>
                            <div class="comment-actions">
                                <span class="comment-time">${c.created_at}</span>
                                <div class="comment-like-wrapper">
                                    <span class="comment-action comment-like-btn ${reactionClass}"
                                          data-react-endpoint="/comments/${c.id}/react"
                                          data-comment-id="${c.id}"
                                          data-reaction="${c.my_reaction || ''}">${reactionText}</span>
                                    <div class="comment-reactions-popover">
                                        <div class="reaction like" data-type="like" title="Thích"><i class="far fa-thumbs-up"></i></div>
                                        <div class="reaction love" data-type="love" title="Yêu thích"><i class="fas fa-heart"></i></div>
                                        <div class="reaction haha" data-type="haha" title="Haha"><i class="fas fa-laugh"></i></div>
                                        <div class="reaction wow" data-type="wow" title="Wow"><i class="fas fa-surprise"></i></div>
                                        <div class="reaction sad" data-type="sad" title="Buồn"><i class="fas fa-sad-tear"></i></div>
                                        <div class="reaction angry" data-type="angry" title="Phẫn nộ"><i class="fas fa-angry"></i></div>
                                    </div>
                                </div>
                                ${c.likes_count > 0 ? `<span class="comment-likes-count"><i class="far fa-thumbs-up"></i> <span class="count">${c.likes_count}</span></span>` : ''}
                                <span class="comment-action reply-btn" data-comment-id="${c.id}" data-comment-author="${c.user_name}" data-post-id="${postId}">Trả lời</span>
                            </div>
                            ${repliesHtml}
                        </div>
                    </div>
                `;
            }).join('');
            
            // Append new comments at the end of the list (after already displayed ones)
            commentsList.insertAdjacentHTML('beforeend', commentsHtml);
            
            // Remove the load more button
            button.remove();
            
            // Re-attach comment reaction listeners
            attachCommentReactionListeners();
            attachReplyListeners();
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        button.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Lỗi, thử lại';
        button.disabled = false;
    }
}

// Preview files for edit modal
function previewEditFiles(input, postId) {
    const preview = document.getElementById('editPreview' + postId);
    preview.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const div = document.createElement('div');
            div.style.cssText = 'position: relative; border-radius: 6px; overflow: hidden; aspect-ratio: 1; background: rgba(0,0,85,0.3);';
            
            if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(file);
                video.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
                div.appendChild(video);
                const icon = document.createElement('div');
                icon.innerHTML = '<i class="fas fa-play-circle"></i>';
                icon.style.cssText = 'position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; font-size: 1.2rem;';
                div.appendChild(icon);
            } else {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.cssText = 'width: 100%; height: 100%; object-fit: cover;';
                div.appendChild(img);
            }
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.style.cssText = 'position: absolute; top: 2px; right: 2px; background: rgba(239,68,68,0.9); color: #fff; width: 20px; height: 20px; border-radius: 50%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 0.6rem;';
            removeBtn.onclick = function() { div.remove(); };
            div.appendChild(removeBtn);
            
            preview.appendChild(div);
        });
    }
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
const reactionTextMap = feedTranslations;
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
        button.querySelector('span').textContent = reactionTextMap[type] || feedTranslations.like;
        
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
        fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: form })
            .then(res => res.json())
            .then(data => {
                if (data.topTypes) {
                    updateReactionIcons(data.topTypes);
                }
                if (typeof data.totalCount !== 'undefined') {
                    const countEl = document.getElementById('likes-count-' + postId);
                    const wrapEl = document.getElementById('likes-wrap-' + postId);
                    if (countEl) countEl.textContent = String(data.totalCount);
                    if (wrapEl) wrapEl.classList.toggle('hidden', data.totalCount === 0);
                }
            })
            .catch(() => {});
    }

    function updateReactionIcons(topTypes) {
        const iconsContainer = document.getElementById('reaction-icons-' + postId);
        if (!iconsContainer) return;
        
        const statIconClassMap = {
            like: 'far fa-thumbs-up',
            love: 'fas fa-heart',
            haha: 'fas fa-laugh',
            wow: 'fas fa-surprise',
            sad: 'fas fa-sad-tear',
            angry: 'fas fa-angry'
        };
        
        let html = '';
        if (topTypes && topTypes.length > 0) {
            topTypes.forEach(t => {
                html += `<span class="reaction-icon ${t}"><i class="${statIconClassMap[t]}"></i></span>`;
            });
        } else {
            html = '<span class="reaction-icon like"><i class="far fa-thumbs-up"></i></span>';
        }
        iconsContainer.innerHTML = html;
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

// Reply comment functionality
document.querySelectorAll('.reply-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const commentId = this.dataset.commentId;
        const authorName = this.dataset.commentAuthor;
        const postId = this.dataset.postId;
        
        const commentsSection = document.getElementById('comments-' + postId);
        const form = commentsSection.querySelector('.comment-form');
        const parentIdInput = form.querySelector('.reply-parent-id');
        const replyIndicator = form.querySelector('.reply-indicator');
        const replyToText = form.querySelector('.reply-to-text');
        const commentInput = form.querySelector('.comment-input');
        
        // Set parent_id
        parentIdInput.value = commentId;
        
        // Show reply indicator
        replyToText.textContent = 'Đang trả lời ' + authorName;
        replyIndicator.style.display = 'block';
        
        // Focus input
        commentInput.focus();
        commentInput.placeholder = 'Trả lời ' + authorName + '...';
    });
});

// Cancel reply
document.querySelectorAll('.cancel-reply').forEach(btn => {
    btn.addEventListener('click', function() {
        const form = this.closest('.comment-form');
        const parentIdInput = form.querySelector('.reply-parent-id');
        const replyIndicator = form.querySelector('.reply-indicator');
        const commentInput = form.querySelector('.comment-input');
        
        parentIdInput.value = '';
        replyIndicator.style.display = 'none';
        commentInput.placeholder = '{{ __("app.feed.write_comment") }}';
    });
});

// AJAX Comment Submit
document.querySelectorAll('.comment-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const postId = this.dataset.postId;
        const input = this.querySelector('.comment-input');
        const content = input.value.trim();
        const parentIdInput = this.querySelector('.reply-parent-id');
        const parentId = parentIdInput.value;
        
        if (!content) return;
        
        const submitBtn = this.querySelector('.comment-submit');
        submitBtn.disabled = true;
        submitBtn.textContent = '...';
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content, parent_id: parentId || null })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Clear input
                input.value = '';
                parentIdInput.value = '';
                this.querySelector('.reply-indicator').style.display = 'none';
                input.placeholder = '{{ __("app.feed.write_comment") }}';
                
                // Create new comment element
                const commentHtml = createCommentHtml(data.comment, parentId);
                
                if (parentId) {
                    // Add reply to parent comment
                    let repliesContainer = document.querySelector(`#comment-${parentId} .replies-container`);
                    if (!repliesContainer) {
                        const parentComment = document.querySelector(`#comment-${parentId}`);
                        if (parentComment) {
                            repliesContainer = document.createElement('div');
                            repliesContainer.className = 'replies-container';
                            repliesContainer.style.cssText = 'margin-top: 0.75rem; margin-left: 1rem; border-left: 2px solid rgba(0,229,255,0.2); padding-left: 0.75rem;';
                            parentComment.querySelector('[style*="flex:1"]').appendChild(repliesContainer);
                        }
                    }
                    if (repliesContainer) {
                        repliesContainer.insertAdjacentHTML('beforeend', commentHtml);
                    }
                } else {
                    // Add new comment at end
                    const commentsSection = document.getElementById('comments-' + postId);
                    commentsSection.insertAdjacentHTML('beforeend', commentHtml);
                }
                
                // Update comment count
                const countEl = document.querySelector(`#comments-count-${postId}`);
                if (countEl) {
                    countEl.textContent = parseInt(countEl.textContent) + 1;
                }
                
                // Re-attach reply button listeners
                attachReplyListeners();
            }
        } catch (error) {
            console.error('Error posting comment:', error);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Gửi';
        }
    });
});

function createCommentHtml(comment, parentId) {
    const isReply = !!parentId;
    const sizeStyle = isReply ? 'width:28px;height:28px;' : '';
    const fontSize = isReply ? 'font-size:0.8rem;' : '';
    const textSize = isReply ? 'font-size:0.85rem;' : '';
    const actionSize = isReply ? 'font-size:0.75rem;' : '';
    const gapStyle = isReply ? 'gap:0.5rem;' : '';
    const paddingStyle = isReply ? 'padding: 0.5rem 0.75rem;' : '';
    const avatarUrl = comment.user_avatar || '';
    
    return `
        <div class="comment-item ${isReply ? 'reply-item' : ''}" id="comment-${comment.id}" style="${isReply ? 'margin-bottom: 0.5rem;' : ''}">
            <div class="comment-avatar" style="${sizeStyle}"><img src="${avatarUrl}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;"></div>
            <div style="flex:1">
                <div class="comment-bubble" style="${paddingStyle}">
                    <div class="comment-author" style="${fontSize}">${comment.user_name}</div>
                    <div class="comment-text" style="${textSize}">${comment.content}</div>
                </div>
                <div class="comment-actions" style="${gapStyle}">
                    <span class="comment-time" style="${isReply ? 'font-size:0.7rem;' : ''}">vừa xong</span>
                    <span class="comment-action" style="${actionSize}">Thích</span>
                    <span class="comment-action reply-btn" data-comment-id="${parentId || comment.id}" data-comment-author="${comment.user_name}" data-post-id="${comment.post_id}" style="${actionSize}">Trả lời</span>
                </div>
            </div>
        </div>
    `;
}

function attachReplyListeners() {
    document.querySelectorAll('.reply-btn').forEach(btn => {
        btn.onclick = function() {
            const commentId = this.dataset.commentId;
            const authorName = this.dataset.commentAuthor;
            const postId = this.dataset.postId;
            
            const commentsSection = document.getElementById('comments-' + postId);
            const form = commentsSection.querySelector('.comment-form');
            const parentIdInput = form.querySelector('.reply-parent-id');
            const replyIndicator = form.querySelector('.reply-indicator');
            const replyToText = form.querySelector('.reply-to-text');
            const commentInput = form.querySelector('.comment-input');
            
            parentIdInput.value = commentId;
            replyToText.textContent = 'Đang trả lời ' + authorName;
            replyIndicator.style.display = 'block';
            commentInput.focus();
            commentInput.placeholder = 'Trả lời ' + authorName + '...';
        };
    });
}

// Comment Reaction Handler with Popover
function attachCommentReactionListeners() {
    const reactionTextMap = feedTranslations;
    const reactionClasses = ['reacted-like', 'reacted-love', 'reacted-haha', 'reacted-wow', 'reacted-sad', 'reacted-angry'];
    
    document.querySelectorAll('.comment-like-wrapper').forEach(wrapper => {
        const button = wrapper.querySelector('.comment-like-btn');
        const popover = wrapper.querySelector('.comment-reactions-popover');
        if (!button || !popover) return;
        
        let hideTimer = null;
        
        function showPopover() {
            clearTimeout(hideTimer);
            popover.classList.add('show');
        }
        
        function hidePopover() {
            hideTimer = setTimeout(() => popover.classList.remove('show'), 200);
        }
        
        // Hover to show popover
        button.addEventListener('mouseenter', showPopover);
        button.addEventListener('mouseleave', hidePopover);
        popover.addEventListener('mouseenter', showPopover);
        popover.addEventListener('mouseleave', hidePopover);
        
        // Click on button to toggle like
        button.addEventListener('click', async (e) => {
            e.preventDefault();
            const currentReaction = button.dataset.reaction;
            const newType = currentReaction ? 'none' : 'like';
            await sendCommentReaction(button, newType);
        });
        
        // Click on reaction in popover
        popover.querySelectorAll('.reaction').forEach(reaction => {
            reaction.addEventListener('click', async (e) => {
                e.preventDefault();
                const type = reaction.dataset.type;
                await sendCommentReaction(button, type);
                popover.classList.remove('show');
            });
        });
    });
    
    async function sendCommentReaction(button, type) {
        const endpoint = button.dataset.reactEndpoint;
        const commentId = button.dataset.commentId;
        const currentReaction = button.dataset.reaction;
        const token = document.querySelector('meta[name="csrf-token"]').content;
        const likesCountEl = document.getElementById('comment-likes-' + commentId);
        
        try {
            const formData = new FormData();
            formData.append('type', type);
            
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token },
                body: formData
            });
            
            if (response.ok) {
                // Remove old classes
                reactionClasses.forEach(cls => button.classList.remove(cls));
                
                if (type === 'none') {
                    button.dataset.reaction = '';
                    button.textContent = 'Thích';
                    // Decrease count
                    if (likesCountEl) {
                        const countSpan = likesCountEl.querySelector('.count') || likesCountEl;
                        let count = parseInt(countSpan.textContent) || 1;
                        count = Math.max(0, count - 1);
                        if (count === 0) {
                            likesCountEl.classList.add('hidden');
                        } else {
                            countSpan.textContent = count;
                        }
                    }
                } else {
                    button.dataset.reaction = type;
                    button.classList.add('reacted-' + type);
                    button.textContent = reactionTextMap[type] || feedTranslations.like;
                    // Increase count if new reaction
                    if (!currentReaction && likesCountEl) {
                        likesCountEl.classList.remove('hidden');
                        const countSpan = likesCountEl.querySelector('.count') || likesCountEl;
                        let count = parseInt(countSpan.textContent) || 0;
                        countSpan.textContent = count + 1;
                    }
                }
            }
        } catch (error) {
            console.error('Error reacting to comment:', error);
        }
    }
}

// Initialize comment reaction listeners
attachCommentReactionListeners();
</script>
@endpush
@endsection
