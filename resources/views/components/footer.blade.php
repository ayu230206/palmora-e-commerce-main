<footer class='p-5 border-t bg-dark'>
    <div class="text-center">
        <div class="container mx-auto flex justify-between items-center text-sm text-center">
            <span>&copy; {{ date('Y') }} Palmora. All Rights Reserved.</span>
            <span>Made by Palmora</span>
        </div>
    </div>
</footer>

<script>
    function closeAllModals(event) {
        const form = event.target.closest('form');

        if (form) {
            form.submit();

            const modals = document.querySelectorAll('dialog.modal');

            modals.forEach(modal => {
                if (modal.hasAttribute('open')) {
                    modal.close();
                }
            });
        }
    }
</script>
