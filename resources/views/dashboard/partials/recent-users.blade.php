<div class="space-y-3">
    @foreach($recent_users as $user)
    <div class="user-card group">
        <div class="flex items-center gap-4">
            <!-- Avatar -->
            <div class="relative flex-shrink-0">
                @if($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" class="w-12 h-12 rounded-xl object-cover border-2 border-[rgba(0,229,255,0.2)] group-hover:border-[#00E5FF] transition-all">
                @else
                <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br from-[#000055] to-[#006666] border-2 border-[rgba(0,229,255,0.2)] group-hover:border-[#00E5FF] transition-all">
                    <span class="text-[#00E5FF] font-bold text-lg">{{ strtoupper(substr($user->display_name, 0, 1)) }}</span>
                </div>
                @endif
                @if($user->online_status === 'online')
                <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-[#0d1b2a] shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
                @endif
            </div>
            
            <!-- User Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    <h4 class="text-white font-semibold text-sm truncate">{{ $user->display_name }}</h4>
                    @switch($user->user_role)
                    @case('super_admin')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gradient-to-r from-amber-500/20 to-orange-500/20 text-amber-400 border border-amber-500/30">
                        <i class="fas fa-crown text-[8px]"></i>SUPER
                    </span>
                    @break
                    @case('admin')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gradient-to-r from-emerald-500/20 to-green-500/20 text-emerald-400 border border-emerald-500/30">
                        <i class="fas fa-shield text-[8px]"></i>ADMIN
                    </span>
                    @break
                    @case('player')
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gradient-to-r from-blue-500/20 to-indigo-500/20 text-blue-400 border border-blue-500/30">
                        <i class="fas fa-gamepad text-[8px]"></i>PLAYER
                    </span>
                    @break
                    @default
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-gradient-to-r from-cyan-500/20 to-teal-500/20 text-cyan-400 border border-cyan-500/30">
                        <i class="fas fa-eye text-[8px]"></i>VIEWER
                    </span>
                    @endswitch
                </div>
                <p class="text-[#94a3b8] text-xs truncate">{{ $user->email }}</p>
            </div>

            <!-- Status & Date -->
            <div class="hidden sm:flex items-center gap-4">
                <div class="text-right">
                    <p class="text-white text-sm font-medium">{{ $user->created_at->format('d/m/Y') }}</p>
                    <p class="text-[#94a3b8] text-xs">{{ $user->created_at->format('H:i') }}</p>
                </div>
                @if($user->status === 'active')
                <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                @elseif($user->status === 'suspended')
                <div class="w-2 h-2 rounded-full bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.6)]"></div>
                @else
                <div class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.6)]"></div>
                @endif
            </div>
            
            <!-- Action -->
            <a href="{{ route('admin.users.index') }}?search={{ $user->email }}" class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center bg-[rgba(0,229,255,0.05)] border border-[rgba(0,229,255,0.2)] text-[#00E5FF] hover:bg-[rgba(0,229,255,0.15)] hover:border-[#00E5FF] hover:shadow-[0_0_15px_rgba(0,229,255,0.3)] transition-all">
                <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
    </div>
    @endforeach
</div>

<style>
.user-card {
    background: rgba(0, 229, 255, 0.02);
    border: 1px solid rgba(0, 229, 255, 0.1);
    border-radius: 16px;
    padding: 1rem 1.25rem;
    transition: all 0.3s ease;
}
.user-card:hover {
    background: rgba(0, 229, 255, 0.06);
    border-color: rgba(0, 229, 255, 0.25);
    transform: translateX(5px);
}
</style>

@if($recent_users->hasPages())
<div class="pt-4 mt-4 border-t border-[rgba(0,229,255,0.1)]">
    {{ $recent_users->links() }}
</div>
@endif
