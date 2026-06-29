<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Ubah Password</h5>
        <small class="text-muted">Gunakan password yang kuat untuk menjaga keamanan akun.</small>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="mb-3 form-password-toggle">
                <label class="form-label" for="update_password_current_password">Password Saat Ini</label>
                <div class="input-group input-group-merge">
                    <input
                        type="password"
                        id="update_password_current_password"
                        class="form-control @if ($errors->updatePassword->has('current_password')) is-invalid @endif"
                        name="current_password"
                        autocomplete="current-password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    >
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    @if ($errors->updatePassword->has('current_password'))
                        <div class="invalid-feedback">{{ $errors->updatePassword->first('current_password') }}</div>
                    @endif
                </div>
            </div>

            <div class="mb-3 form-password-toggle">
                <label class="form-label" for="update_password_password">Password Baru</label>
                <div class="input-group input-group-merge">
                    <input
                        type="password"
                        id="update_password_password"
                        class="form-control @if ($errors->updatePassword->has('password')) is-invalid @endif"
                        name="password"
                        autocomplete="new-password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    >
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    @if ($errors->updatePassword->has('password'))
                        <div class="invalid-feedback">{{ $errors->updatePassword->first('password') }}</div>
                    @endif
                </div>
            </div>

            <div class="mb-3 form-password-toggle">
                <label class="form-label" for="update_password_password_confirmation">Konfirmasi Password Baru</label>
                <div class="input-group input-group-merge">
                    <input
                        type="password"
                        id="update_password_password_confirmation"
                        class="form-control @if ($errors->updatePassword->has('password_confirmation')) is-invalid @endif"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    >
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    @if ($errors->updatePassword->has('password_confirmation'))
                        <div class="invalid-feedback">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                    @endif
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-primary" type="submit">
                    <i class="bx bx-lock-alt me-1"></i>
                    Simpan Password
                </button>

                @if (session('status') === 'password-updated')
                    <span class="text-success">Password berhasil diperbarui.</span>
                @endif
            </div>
        </form>
    </div>
</div>
