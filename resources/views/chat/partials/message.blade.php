@php
$isOwn = $message->sender_id === auth()->id();
$alignClass = $isOwn ? 'justify-content-end' : 'justify-content-start';
$bubbleClass = $isOwn ? 'own' : 'other';
@endphp

<div class="d-flex {{ $alignClass }} mb-4 message-item" data-message-id="{{ $message->id }}">
    @if(!$isOwn)
    <img src="{{ $message->sender->getDisplayAvatar() }}" 
         alt="{{ $message->sender->name }}" 
         class="message-avatar me-3 align-self-end">
    @endif

    <div class="message-bubble {{ $bubbleClass }}">
        @if(!$isOwn)
        <div class="fw-bold text-primary small mb-1">{{ $message->sender->name }}</div>
        @endif

        {{-- Display attachment if present --}}
        @if($message->attachment_path)
        <div class="mb-2">
            @if($message->type === 'image' || $message->isImage())
            <div class="attachment-preview">
                <img src="{{ $message->attachment_url }}"
                    alt="Hình ảnh" class="img-fluid rounded-3"
                    style="max-width: 250px; cursor: pointer; border-radius: 12px;"
                    onclick="window.open('{{ $message->attachment_url }}', '_blank')"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div style="display:none; padding: 10px; background: rgba(255,0,0,0.1); border-radius: 8px;">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    <span class="text-danger">Không thể tải ảnh</span>
                </div>
            </div>
            @else
            <div class="attachment-preview">
                <a href="{{ $message->attachment_url }}" target="_blank" class="text-decoration-none">
                    <i class="fas fa-file me-2"></i>
                    {{ $message->attachment_name ?? 'Tệp đính kèm' }}
                </a>
            </div>
            @endif
        </div>
        @endif

        {{-- Display message content --}}
        @if($message->content)
        <div class="message-text">
            {!! nl2br(e($message->content)) !!}
        </div>
        @endif

        <div class="message-time">
            {{ $message->formatted_time }}
        </div>

        {{-- Message reactions --}}
        <div class="message-reactions mt-2">
            @php
                $allEmojis = ['👍', '❤️', '😂', '😮', '😢'];
            @endphp
            @foreach($allEmojis as $emoji)
                <button class="reaction-btn" data-emoji="{{ $emoji }}" disabled 
                        title="Tính năng reaction sẽ được triển khai sau">
                    {{ $emoji }}
                </button>
            @endforeach
        </div>
    </div>

    @if($isOwn)
    <img src="{{ $message->sender->getDisplayAvatar() }}" 
         alt="{{ $message->sender->name }}" 
         class="message-avatar ms-3 align-self-end">
    @endif
</div>
