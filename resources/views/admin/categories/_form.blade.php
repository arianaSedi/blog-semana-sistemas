{{-- Se usa en crear y editar --}}
<div class="tarjeta p-4">
    <div class="encabezado-seccion">
        <h2 class="h6 fw-bold mb-0">Datos de la categoría</h2>
    </div>

    <div class="mb-3">
        <label for="name" class="form-label fw-semibold">Nombre *</label>
        <input type="text" id="name" name="name" maxlength="100"
               value="{{ old('name', $category->name) }}"
               class="form-control form-control-lg @error('name') is-invalid @enderror"
               placeholder="Ej.: Feria de proyectos">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <div class="form-text">Dirección en el blog: <span class="fw-semibold">/categorias/<span id="vistaSlug">{{ $category->slug ?: '...' }}</span></span></div>
    </div>

    <div class="mb-4">
        <label for="description" class="form-label fw-semibold">Contenido mínimo</label>
        <textarea id="description" name="description" rows="4" maxlength="500"
                  class="form-control @error('description') is-invalid @enderror"
                  placeholder="Ej.: Proyectos visitados, tecnologías e innovación.">{{ old('description', $category->description) }}</textarea>
        <div class="form-text text-end"><span id="contadorDescripcion">0</span> / 500</div>
        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="d-flex flex-wrap gap-2 pt-3 border-top">
        <button type="submit" class="btn btn-primary">
            {{ $category->exists ? 'Guardar cambios' : 'Crear categoría' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Dirección pública mientras se escribe el nombre
        const nombre = document.getElementById('name');
        const slug = document.getElementById('vistaSlug');
        const crearSlug = (texto) => texto.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '')
            .replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        nombre.addEventListener('input', () => slug.textContent = crearSlug(nombre.value) || '...');

        // Contador de la descripción
        const descripcion = document.getElementById('description');
        const contador = document.getElementById('contadorDescripcion');
        const contar = () => contador.textContent = descripcion.value.length;
        descripcion.addEventListener('input', contar);
        contar();
    });
</script>