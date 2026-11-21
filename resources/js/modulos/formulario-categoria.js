// Formulario de Nueva / Editar categoría
const vistaSlug = document.getElementById('vistaSlug');

if (vistaSlug) {
    // Direccion publica mientras se escribe el nombre
    const nombre = document.getElementById('name');
    const crearSlug = (texto) => texto.toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
    nombre.addEventListener('input', () => {
        vistaSlug.textContent = crearSlug(nombre.value) || '...';
    });

    // Contador de la descripcion
    const descripcion = document.getElementById('description');
    const contador = document.getElementById('contadorDescripcion');
    const contar = () => { contador.textContent = descripcion.value.length; };
    descripcion.addEventListener('input', contar);
    contar();
}