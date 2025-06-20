<div class="d-flex flex-wrap align-items-center gap-2 mt-3">
    <!-- WhatsApp -->
    <a href="https://wa.me/?text={{ urlencode($url) }}" target="_blank" class="btn btn-success btn-sm rounded-pill">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Facebook -->
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}" target="_blank" class="btn btn-primary btn-sm rounded-pill">
        <i class="fab fa-facebook"></i>
    </a>

    <!-- Twitter -->
    <a href="https://twitter.com/intent/tweet?url={{ urlencode($url) }}" target="_blank" class="btn btn-info btn-sm text-white rounded-pill">
        <i class="fab fa-twitter"></i>
    </a>

    <!-- Copy Link -->
    <button onclick="copyShareLink()" class="btn btn-secondary btn-sm rounded-pill">
        <i class="fas fa-link"></i>
    </button>

    <!-- Hidden Input -->
    <input type="text" id="shareLinkInput" value="{{ $url }}" style="position:absolute; left:-9999px;">

    <!-- Toast -->
    <div id="shareToast" class=" align-items-center border-0 ms-2" role="alert" aria-live="assertive" aria-atomic="true" style="display:none;">
        <div class="d-flex">
            <div class="toast-body small">
                disalin!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="hideShareToast()" aria-label="Close"></button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyShareLink() {
        const copyText = document.getElementById("shareLinkInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);

        try {
            document.execCommand('copy');
            showShareToast();
        } catch (err) {
            alert("Gagal menyalin.");
        }
    }

    function showShareToast() {
        const toast = document.getElementById("shareToast");
        toast.style.display = 'flex';
        setTimeout(() => {
            toast.style.display = 'none';
        }, 2500);
    }

    function hideShareToast() {
        document.getElementById("shareToast").style.display = 'none';
    }
</script>

@endpush