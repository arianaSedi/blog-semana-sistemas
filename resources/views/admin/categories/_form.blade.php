{{-- Se usa en crear y editar --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nombre *</label>
            <input type="text" id="name" name="name" maxlength="100"
                   value="{{ old('name', $category->name) }}"
                   class="form-control @error('name') is-invalid @enderror">
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="form-label fw-semibold">Descripción / contenido mínimo</label>
            <textarea id="description" name="description" rows="3" maxlength="500"
                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $category->exists ? 'Guardar cambios' : 'Crear categoría' }}
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
</div>