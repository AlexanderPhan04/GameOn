@php
$isOwn = $message->sender_id === auth()->id();
@endphp

<div class="message-item {{ $isOwn ? 'own' : 'other' }}" data-message-id="{{ $message->id }}">
    <div class="message-content">
        <img src="{{ $message->sender->getDisplayAvatar() }}" 
             alt="{{ $message->sender->name }}" 
             class="msg-avatar">

        <div class="message-bubble">
            @if(!$isOwn)
            <div class="msg-sender">{{ $message->sender->name }}</div>
            @endif

            {{-- Display attachment if present --}}
            @if($message->attachment_path)
            <div class="msg-attachment">
                @if($message->type === 'image' || $message->isImage())
                <img src="{{ $message->attachment_url }}"
                     alt="Hình ảnh" 
                     class="msg-image"
                     onclick="window.open('{{ $message->attachment_url }}', '_blank')"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="msg-image-error" style="display:none;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>Không thể tải ảnh</span>
                </div>
                @else
                <a href="{{ $message->attachment_url }}" target="_blank" class="msg-file">
                    <i class="fas fa-file"></i>
                    <span>{{ $message->attachment_name ?? 'Tệp đính kèm' }}</span>
                </a>
                @endif
            </div>
            @endif

            {{-- Display message content --}}
            @if($message->content)
            <div class="msg-text">{!! nl2br(e($message->content)) !!}</div>
            @endif

            <div class="msg-time">{{ $message->formatted_time }}</div>
        </div>
    </div>
</div>

<style>
.msg-attachment {
    margin-bottom: 0.5rem;
}

.msg-image {
    max-width: 250px;
    border-radius: 12px;
    cursor: pointer;
    transition: transform 0.2s ease;
}

.msg-image:hover {
    transform: scale(1.02);
}

.msg-image-error {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    background: rgba(239, 68, 68, 0.1);
    border-radius: 8px;
    color: #ef4444;
    font-size: 0.85rem;
}

.msg-file {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: rgba(0, 229, 255, 0.1);
    border: 1px solid rgba(0, 229, 255, 0.2);
    border-radius: 8px;
    color: #00E5FF;
    text-decoration: none;
    font-size: 0.9rem;
    transition: all 0.2s ease;
}

.msg-file:hover {
    background: rgba(0, 229, 255, 0.2);
    color: #00E5FF;
}

.message-item.own .msg-file {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
    color: #fff;
}

.message-item.own .msg-file:hover {
    background: rgba(255, 255, 255, 0.25);
    color: #fff;
}
</style>
