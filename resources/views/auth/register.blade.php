<x-guest-layout>
    <x-auth-brand />

    <h4 class="mb-2">Mulai Gunakan SiGKM</h4>
    <p class="mb-4">Daftarkan akun Anda untuk melanjutkan.</p>

    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('register') }}"
        enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="nama_dosen" class="form-label">Nama Dosen</label>
            <input type="text" class="form-control @error('nama_dosen') is-invalid @enderror" id="nama_dosen"
                name="nama_dosen" value="{{ old('nama_dosen') }}" placeholder="Masukkan nama dosen" required autofocus
                autocomplete="name">
            @error('nama_dosen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="text" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip"
                value="{{ old('nip') }}" placeholder="Masukkan NIP" autocomplete="off">
            @error('nip')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nidn" class="form-label">NIDN</label>
            <input type="text" class="form-control @error('nidn') is-invalid @enderror" id="nidn" name="nidn"
                value="{{ old('nidn') }}" placeholder="Masukkan NIDN" autocomplete="off">
            @error('nidn')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email') }}" placeholder="Masukkan email" required
                autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required
                    autocomplete="new-password" aria-describedby="password">
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
            <div class="input-group input-group-merge">
                <input type="password" id="password_confirmation"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    name="password_confirmation"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required
                    autocomplete="new-password" aria-describedby="password_confirmation">
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button class="btn btn-primary d-grid w-100" type="submit">Daftar</button>
    </form>

    <p class="text-center">
        <span>Sudah punya akun?</span>
        <a href="{{ route('login') }}">
            <span>Masuk di sini</span>
        </a>
    </p>
</x-guest-layout>
