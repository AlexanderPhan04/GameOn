@extends('layouts.app')

@section('title', 'Kết quả tìm kiếm')

@section('content')
<div class="container py-4">
    <form method="get" action="{{ route('search.view') }}" class="mb-3">
        <div class="input-group">
            <input class="form-control" type="text" name="q" value="{{ $q }}" placeholder="Tìm kiếm người dùng, đội, giải đấu, game...">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search me-1"></i>Tìm</button>
        </div>
    </form>

    <div class="row">
        <aside class="col-md-3">
            <div class="card">
                <div class="card-header fw-semibold">Bộ lọc</div>
                <div class="list-group list-group-flush">
                    @php $current = request('type'); @endphp
                    <a class="list-group-item d-flex justify-content-between align-items-center {{ $current? '' : 'active' }}" href="{{ route('search.view',['q'=>$q]) }}">
                        Tất cả
                        <span class="badge bg-secondary">{{ array_sum($counts) }}</span>
                    </a>
                    <a class="list-group-item d-flex justify-content-between align-items-center {{ $current==='users'?'active':'' }}" href="{{ route('search.view',['q'=>$q,'type'=>'users']) }}">
                        Người dùng
                        <span class="badge bg-secondary">{{ $counts['users'] }}</span>
                    </a>
                    <a class="list-group-item d-flex justify-content-between align-items-center {{ $current==='teams'?'active':'' }}" href="{{ route('search.view',['q'=>$q,'type'=>'teams']) }}">
                        Đội
                        <span class="badge bg-secondary">{{ $counts['teams'] }}</span>
                    </a>
                    <a class="list-group-item d-flex justify-content-between align-items-center {{ $current==='tournaments'?'active':'' }}" href="{{ route('search.view',['q'=>$q,'type'=>'tournaments']) }}">
                        Giải đấu
                        <span class="badge bg-secondary">{{ $counts['tournaments'] }}</span>
                    </a>
                    <a class="list-group-item d-flex justify-content-between align-items-center {{ $current==='games'?'active':'' }}" href="{{ route('search.view',['q'=>$q,'type'=>'games']) }}">
                        Game
                        <span class="badge bg-secondary">{{ $counts['games'] }}</span>
                    </a>
                </div>
            </div>
        </aside>
        <section class="col-md-9">
            @if($q==='')
                <div class="alert alert-info"><i class="fas fa-info-circle me-1"></i> Nhập từ khóa để xem kết quả.</div>
            @endif

            @php
                $blocks = [];
                if($users->count()) $blocks[]=['title'=>'Người dùng','icon'=>'fa-user','items'=>$users, 'link'=>function($u) use ($user){ 
                    if($u->id == $user->id) {
                        return route('profile.show');
                    } else {
                        return route('profile.show-user', $u->id);
                    }
                }];
                if($teams->count()) $blocks[]=['title'=>'Đội','icon'=>'fa-users','items'=>$teams, 'link'=>function($t){ return route('teams.index',['search'=>$t->name]); }];
                if($tournaments->count()) $blocks[]=['title'=>'Giải đấu','icon'=>'fa-trophy','items'=>$tournaments, 'link'=>function($t){ return route('tournaments.index',['search'=>$t->name]); }];
                if($games->count()) $blocks[]=['title'=>'Game','icon'=>'fa-gamepad','items'=>$games, 'link'=>function($g){ return '#'; }];
            @endphp

            @forelse($blocks as $b)
                <div class="card mb-3">
                    <div class="card-header"><i class="fas {{ $b['icon'] }} me-1"></i>{{ $b['title'] }}</div>
                    <div class="list-group list-group-flush">
                        @foreach($b['items'] as $item)
                            <a class="list-group-item list-group-item-action" href="{{ $b['link']($item) }}">
                                <strong>{{ $item->name ?? $item->full_name ?? '' }}</strong>
                                @if(isset($item->email))<div class="small text-muted">{{ $item->email }}</div>@endif
                            </a>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">Không có kết quả phù hợp.</div>
            @endforelse
        </section>
    </div>
</div>
@endsection


