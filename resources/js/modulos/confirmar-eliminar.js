// Confirmacion con SweetAlert para todos los formularios que tengan data-confirmar
import Swal from 'sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

document.querySelectorAll('form[data-confirmar]').forEach((form) => {
    form.addEventListener('submit', async (evento) => {
        evento.preventDefault();

        const respuesta = await Swal.fire({
            title: form.dataset.titulo || '¿Eliminar?',
            text: form.dataset.confirmar,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            focusCancel: true,
            buttonsStyling: false,
            customClass: {
                popup: 'alerta-sds',
                confirmButton: 'btn btn-danger px-4',
                cancelButton: 'btn btn-outline-secondary px-4',
                actions: 'gap-2',
            },
        });

        if (respuesta.isConfirmed) {
            form.submit();
        }
    });
});