@extends('layouts.app', ['title' => 'Profil Saya'])

@section('content')
    @php
        $avatarUrl = 'https://ui-avatars.com/api/?' . http_build_query([
            'name' => $user->name,
            'background' => '696cff',
            'color' => 'ffffff',
            'bold' => true,
            'size' => 220,
        ]);
    @endphp

    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Akun /</span> Profil Saya
            </h4>
        </div>

        <div class="col-xl-4 col-lg-5 col-md-5">
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img
                        src="{{ $avatarUrl }}"
                        alt="{{ $user->name }}"
                        class="rounded-circle mb-3"
                        width="110"
                        height="110"
                    >
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <span class="badge bg-label-primary mb-3">{{ $user->role->name ?? 'User' }}</span>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <span class="fw-semibold me-2">Nama:</span>
                            <span>{{ $user->name }}</span>
                        </li>
                        <li class="mb-3">
                            <span class="fw-semibold me-2">Email:</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li>
                            <span class="fw-semibold me-2">Role:</span>
                            <span>{{ $user->role->name ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 col-md-7">
            @include('profile.partials.update-profile-information-form')
            @include('profile.partials.update-password-form')
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection
