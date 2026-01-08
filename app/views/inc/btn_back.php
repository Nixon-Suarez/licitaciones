<p class="has-text-right pt-4 pb-4 gap-3">
    <a href="#" class="btn btn-outline-secondary btn-back">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</p>

<script type="text/javascript">
    const btn_back = document.querySelector(".btn-back");
    if (btn_back) {
        btn_back.addEventListener('click', function(e) {
            e.preventDefault();
            window.history.back();
        });
    }
</script>