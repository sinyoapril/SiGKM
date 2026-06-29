<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ $action }}" method="POST">
                @csrf
                @method($method ?? 'PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <div class="modal-body">
                    <p class="text-muted">
                        {{ $description }}
                    </p>

                    <div class="mb-3">
                        <label class="form-label">
                            Catatan
                            @if ($required)
                                <span class="text-danger">*</span>
                            @endif
                        </label>

                        <textarea name="{{ $fieldName }}" rows="4" class="form-control" placeholder="Masukkan catatan verifikasi"
                            {{ $required ? 'required' : '' }}></textarea>

                        @if (!$required)
                            <small class="text-muted">
                                Catatan bersifat opsional.
                            </small>
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn {{ $buttonClass }}">
                        <i class="{{ $buttonIcon }}"></i>
                        {{ $buttonText }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
