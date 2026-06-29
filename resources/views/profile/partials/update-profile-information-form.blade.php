<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Informasi Profil</h5>
        <small class="text-muted">Perbarui nama dan alamat email akun Anda.</small>
    </div>
    <div class="card-body">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    autofocus
                    autocomplete="name"
                >
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    required
                    autocomplete="username"
                >
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="alert alert-warning mt-3 mb-0" role="alert">
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="btn btn-link p-0 align-baseline" type="submit">
                            Kirim ulang email verifikasi.
                        </button>
                    </div>
                @endif
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success" role="alert">
                    Tautan verifikasi baru telah dikirim ke alamat email Anda.
                </div>
            @endif

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-primary" type="submit">
                    <i class="bx bx-save me-1"></i>
                    Simpan
                </button>

                @if (session('status') === 'profile-updated')
                    <span class="text-success">Profil berhasil diperbarui.</span>
                @endif
            </div>
        </form>
    </div>
</div>
