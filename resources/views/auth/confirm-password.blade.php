<x-guest-layout>
    <x-auth-brand />

    <h4 class="mb-2">Konfirmasi Password</h4>
    <p class="mb-4">Area ini aman. Masukkan password Anda untuk melanjutkan.</p>

    <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3 form-password-toggle">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password"
                    class="form-control @error('password') is-invalid @enderror"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    required
                    autocomplete="current-password"
                    aria-describedby="password"
                >
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <button class="btn btn-primary d-grid w-100" type="submit">Konfirmasi</button>
    </form>
</x-guest-layout>
