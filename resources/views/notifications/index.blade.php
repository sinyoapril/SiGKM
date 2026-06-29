@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center py-3 mb-4"><div><h4 class="fw-bold mb-1">Notifikasi</h4><p class="text-muted mb-0">Seluruh pembaruan workflow untuk akun Anda.</p></div><form action="{{ route('notifications.read-all') }}" method="POST">@csrf @method('PATCH')<button class="btn btn-outline-primary">Tandai Semua Dibaca</button></form></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card"><div class="list-group list-group-flush">
@forelse($notifications as $notification)
<a href="{{ route('notifications.read', $notification) }}" class="list-group-item list-group-item-action {{ $notification->read_at ? '' : 'bg-label-primary' }}">
    <div class="d-flex align-items-start"><span class="avatar-initial rounded bg-label-{{ $notification->data['color'] ?? 'primary' }} p-2 me-3"><i class="bx {{ $notification->data['icon'] ?? 'bx-bell' }}"></i></span><div class="flex-grow-1"><div class="d-flex justify-content-between"><strong>{{ $notification->data['title'] ?? 'Notifikasi' }}</strong><small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small></div><div>{{ $notification->data['pesan'] ?? '-' }}</div>@if(!$notification->read_at)<small class="text-primary">Belum dibaca</small>@endif</div></div>
</a>
@empty<div class="p-4 text-center text-muted">Belum ada notifikasi.</div>@endforelse
</div><div class="card-footer">@include('components._pagination', ['paginator' => $notifications])</div></div>
@endsection
