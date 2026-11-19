@php
    $autorasMarcadas = old('authors', $post->exists ? $post->authors->pluck('id')->all() : [auth()->id()]);
@endphp

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="title" class="form-label fw-semibold">Título *</label>
                    <input type="text" id="title" name="title" maxlength="200"
                           value="{{ old('title', $post->title) }}"
                           class="form-control @error('title') is-invalid @enderror">
                    @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="excerpt" class="form-label fw-semibold">Resumen <small class="text-muted fw-normal">(opcional, se muestra en el listado)</small></label>
                    <input type="text" id="excerpt" name="excerpt" maxlength="300"
                           value="{{ old('excerpt', $post->excerpt) }}"
                           class="form-control @error('excerpt') is-invalid @enderror">
                    @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label for="content" class="form-label fw-semibold">Contenido *</label>
                    <div id="guia" class="alert alert-success small py-2 mb-2">
                        Describe hechos reales e incluye una reflexión propia.
                    </div>
                    <textarea id="content" name="content" rows="14"
                              class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
                    <div class="form-text">Mínimo 100 caracteres. Deja una línea en blanco para separar párrafos.</div>
                    @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="status" class="form-label fw-semibold">Estado *</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                        @foreach (\App\Models\post_model::STATUSES as $valor => $texto)
                            <option value="{{ $valor }}" @selected(old('status', $post->status) === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Los borradores no se muestran en el blog público.</div>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label fw-semibold">Categoría *</label>
                    <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">Selecciona una actividad</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" data-guia="{{ $category->description }}"
                                    @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="activity_date" class="form-label fw-semibold">Fecha de la actividad *</label>
                    <input type="date" id="activity_date" name="activity_date"
                           value="{{ old('activity_date', optional($post->activity_date)->format('Y-m-d')) }}"
                           class="form-control @error('activity_date') is-invalid @enderror">
                    @error('activity_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div>
                    <p class="form-label fw-semibold mb-1">Autoras *</p>
                    @foreach ($users as $user)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="authors[]" value="{{ $user->id }}"
                                   id="autora{{ $user->id }}" @checked(in_array($user->id, $autorasMarcadas))>
                            <label class="form-check-label" for="autora{{ $user->id }}">{{ $user->name }}</label>
                        </div>
                    @endforeach
                    @error('authors') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="cover_image" class="form-label fw-semibold">Imagen principal {{ $post->exists ? '' : '*' }}</label>
                    @if ($post->cover_image)
                        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="Imagen actual" class="img-fluid rounded mb-2">
                        <div class="form-text mb-1">Sube otra solo si quieres reemplazarla.</div>
                    @endif
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                           class="form-control @error('cover_image') is-invalid @enderror">
                    @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label for="gallery" class="form-label fw-semibold">Agregar imágenes a la galería</label>
                    <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple
                           class="form-control @error('gallery') is-invalid @enderror @error('gallery.*') is-invalid @enderror">
                    <div class="form-text">Puedes seleccionar varias a la vez (máximo 5 MB cada una).</div>
                    @error('gallery') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('gallery.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">{{ $post->exists ? 'Guardar cambios' : 'Crear entrada' }}</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const categoria = document.getElementById('category_id');
        const guia = document.getElementById('guia');
        const textoBase = guia.textContent.trim();

        const actualizar = () => {
            const minimo = categoria.selectedOptions[0]?.dataset.guia;
            guia.textContent = minimo ? `${textoBase} Contenido mínimo: ${minimo}` : textoBase;
        };

        categoria.addEventListener('change', actualizar);
        actualizar();
    });
</script>