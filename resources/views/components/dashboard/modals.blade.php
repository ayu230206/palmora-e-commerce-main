<dialog id="delete_modal_general" class="modal modal-bottom sm:modal-middle">
    <form method="POST" class="modal-box">
        @csrf
        @method('DELETE')
        <h3 class="modal-title capitalize">
            Hapus <span id="general_data_type"></span>
        </h3>
        <div class="modal-body" id="general_body"></div>
        <div class="modal-action">
            <button class="btn btn-error capitalize text-white" type="submit" onclick="closeAllModals(event)">
                Yakin
            </button>
            <button type="button" onclick="document.getElementById('delete_modal_general').close()" class="btn">
                Batal
            </button>
        </div>
    </form>
</dialog>
<script>
    function initDelete(type, data) {
        const val = data['tanggal'];
        const id = data['id'];

        const formattedType = type.replace(/_/g, ' ');

        document.getElementById('general_data_type').innerText = formattedType;
        document.getElementById('general_body').innerHTML = `
        <p>
            Apakah Anda yakin ingin menghapus data <strong>${formattedType}</strong> 
        </p>
        <p class="text-red-600">
            Tindakan ini tidak dapat diurungkan dan data akan hilang secara permanen.
        </p>
    `;

        const form = document.querySelector('#delete_modal_general form');
        form.action = `/dashboard/${type}/${id}/hapus`;

        document.getElementById('delete_modal_general').showModal();
    }
</script>

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
