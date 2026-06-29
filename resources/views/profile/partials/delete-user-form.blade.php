<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Hapus Akun</h5>
        <small class="text-muted">Tindakan ini akan menghapus akun Anda secara permanen.</small>
    </div>
    <div class="card-body">
        <div class="alert alert-warning mb-4" role="alert">
            Setelah akun dihapus, seluruh data yang terkait dengan akun tidak dapat dipulihkan.
        </div>

        <button class="btn btn-outline-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
            <i class="bx bx-trash me-1"></i>
            Hapus Akun
        </button>
    </div>
</div>

<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content">
            @csrf
            @method('delete')

            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Konfirmasi Hapus Akun</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted">
                    Masukkan password untuk mengonfirmasi bahwa akun ini akan dihapus permanen.
                </p>

                <div class="form-password-toggle">
                    <label class="form-label" for="delete_account_password">Password</label>
                    <div class="input-group input-group-merge">
                        <input
                            type="password"
                            id="delete_account_password"
                            class="form-control @if ($errors->userDeletion->has('password')) is-invalid @endif"
                            name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        >
                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                        @if ($errors->userDeletion->has('password'))
                            <div class="invalid-feedback">{{ $errors->userDeletion->first('password') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-danger">Hapus Akun</button>
            </div>
        </form>
    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('deleteAccountModal'));
                modal.show();
            });
        </script>
    @endpush
@endif
