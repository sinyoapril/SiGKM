<x-guest-layout>
    <x-auth-brand />

    <h4 class="mb-2">Verifikasi Email</h4>
    <p class="mb-4">
        Terima kasih sudah mendaftar. Sebelum mulai, verifikasi email Anda melalui tautan yang sudah kami kirim.
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-3" role="alert">
            Tautan verifikasi baru telah dikirim ke email yang Anda gunakan saat mendaftar.
        </div>
    @endif

    <form class="mb-3" method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button class="btn btn-primary d-grid w-100" type="submit">Kirim Ulang Email Verifikasi</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="btn btn-outline-secondary d-grid w-100">Keluar</button>
    </form>
</x-guest-layout>
