const guia = document.getElementById('guiaTexto');

if (guia) {
    const categoria = document.getElementById('category_id');
    const actualizarGuia = () => {
        const minimo = categoria.selectedOptions[0]?.dataset.guia;
        guia.textContent = minimo || 'Selecciona una categoría para ver lo que debe incluir.';
    };
    categoria.addEventListener('change', actualizarGuia);
    actualizarGuia();

    // Contador del contenido
    const contenido = document.getElementById('content');
    const contador = document.getElementById('contadorContenido');
    const contarContenido = () => {
        const n = contenido.value.trim().length;
        contador.textContent = `${n} caracteres · mínimo 100`;
        contador.classList.toggle('text-lima-oscuro', n >= 100);
        contador.classList.toggle('text-danger', n > 0 && n < 100);
    };
    contenido.addEventListener('input', contarContenido);
    contarContenido();

    // Contador del resumen
    const resumen = document.getElementById('excerpt');
    const contadorResumen = document.getElementById('contadorResumen');
    const contarResumen = () => { contadorResumen.textContent = resumen.value.length; };
    resumen.addEventListener('input', contarResumen);
    contarResumen();

    // Vista previa de la imagen principal
    document.getElementById('cover_image').addEventListener('change', (e) => {
        const archivo = e.target.files[0];
        if (!archivo) return;
        const img = document.createElement('img');
        img.src = URL.createObjectURL(archivo);
        img.alt = 'Vista previa';
        document.getElementById('vistaPortada').replaceChildren(img);
    });

    // Vista previa de las fotos nuevas de la galeria
    document.getElementById('gallery').addEventListener('change', (e) => {
        const contenedor = document.getElementById('vistaGaleria');
        contenedor.replaceChildren();
        [...e.target.files].forEach((archivo) => {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(archivo);
            img.alt = '';
            contenedor.appendChild(img);
        });
    });
}