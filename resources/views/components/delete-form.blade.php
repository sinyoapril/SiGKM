@props(['action'])
<form action="{{ $action }}" method="POST" class="d-inline" data-confirm-form
    data-confirm-title="Yakin ingin menghapus data ini?" data-confirm-text="Data yang dihapus tidak dapat dikembalikan."
    data-confirm-button-text="Ya, hapus" data-confirm-button-color="#ff3e1d">
    @csrf @method('DELETE')
    <button type="submit" class="btn btn-sm btn-icon btn-danger"><i class="bx bx-trash"></i></button>
</form>
