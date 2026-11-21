@php
    $autorasMarcadas = old('authors', $post->exists ? $post->authors->pluck('id')->all() : [auth()->id()]);
    $estadoActual = old('status', $post->status);
@endphp

<div class="row g-4 align-items-start">
    {{-- Columna principal --}}
    <div class="col-lg-8">
        <div class="tarjeta p-4 mb-4">
            <div class="encabezado-seccion">
                <h2 class="h6 fw-bold mb-1">Información general</h2>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">Título *</label>
                <input type="text" id="title" name="title" maxlength="200"
                       value="{{ old('title', $post->title) }}"
                       class="form-control form-control-lg @error('title') is-invalid @enderror"
                       placeholder="Ej.: Taller de sensores IoT con Arduino">
                @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="excerpt" class="form-label fw-semibold">Resumen <span class="text-muted fw-normal small">(opcional)</span></label>
                <textarea id="excerpt" name="excerpt" maxlength="300" rows="2"
                          class="form-control @error('excerpt') is-invalid @enderror"
                          placeholder="Una o dos oraciones que se muestran en las tarjetas del blog.">{{ old('excerpt', $post->excerpt) }}</textarea>
                <div class="form-text text-end"><span id="contadorResumen">0</span> / 300</div>
                @error('excerpt') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="tarjeta p-4">
            <div class="encabezado-seccion">
                <h2 class="h6 fw-bold mb-1">Contenido de la entrada</h2>
            </div>

            <div class="guia-contenido mb-3">
                <span class="fw-bold">Contenido mínimo:</span>
                <span id="guiaTexto">Selecciona una categoría para ver lo que debe incluir.</span>
            </div>

            <textarea id="content" name="content" rows="16"
                      class="form-control @error('content') is-invalid @enderror">{{ old('content', $post->content) }}</textarea>
            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="d-flex flex-wrap justify-content-between gap-2 form-text">
                <span>Deja una línea en blanco para separar párrafos.</span>
                <span id="contadorContenido" class="fw-semibold">0 caracteres · mínimo 100</span>
            </div>
        </div>
    </div>

    {{-- Columna lateral --}}
    <div class="col-lg-4">
        <div class="lateral-fija">
            <div class="tarjeta p-4 mb-4">
                <div class="encabezado-seccion">
                    <h2 class="h6 fw-bold mb-0">Publicación</h2>
                </div>

                <p class="form-label fw-semibold mb-2">Estado *</p>
                <div class="selector-estado mb-1">
                    @foreach (\App\Models\post_model::STATUSES as $valor => $texto)
                        <input type="radio" class="btn-check" name="status" id="estado-{{ $valor }}"
                               value="{{ $valor }}" @checked($estadoActual === $valor)>
                        <label class="btn" for="estado-{{ $valor }}">{{ $texto }}</label>
                    @endforeach
                </div>
                <div class="form-text mb-3">Los borradores no se muestran en el blog público.</div>
                @error('status') <div class="text-danger small mb-3">{{ $message }}</div> @enderror

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

                <p class="form-label fw-semibold mb-2">Autoras *</p>
                <div class="d-flex flex-wrap gap-2">
                    @foreach ($users as $user)
                        <input class="btn-check" type="checkbox" name="authors[]" value="{{ $user->id }}"
                               id="autora{{ $user->id }}" @checked(in_array($user->id, $autorasMarcadas))>
                        <label class="btn chip-autora" for="autora{{ $user->id }}">{{ $user->name }}</label>
                    @endforeach
                </div>
                @error('authors') <div class="text-danger small mt-2">{{ $message }}</div> @enderror

                <hr class="my-4">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">{{ $post->exists ? 'Guardar cambios' : 'Crear entrada' }}</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>
            </div>

            <div class="tarjeta p-4">
                <div class="encabezado-seccion">
                    <h2 class="h6 fw-bold mb-0">Imágenes</h2>
                </div>

                <label for="cover_image" class="form-label fw-semibold">Imagen principal {{ $post->exists ? '' : '*' }}</label>
                <div id="vistaPortada" class="zona-portada mb-2">
                    @if ($post->cover_image)
                        <img src="{{ asset('storage/'.$post->cover_image) }}" alt="Imagen principal actual">
                    @else
                        <span>Sin imagen seleccionada</span>
                    @endif
                </div>
                <input type="file" id="cover_image" name="cover_image" accept="image/*"
                       class="form-control form-control-sm @error('cover_image') is-invalid @enderror">
                @error('cover_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @if ($post->cover_image)
                    <div class="form-text">Sube otra solo si quieres reemplazarla.</div>
                @endif

                <label for="gallery" class="form-label fw-semibold mt-4">Agregar a la galería</label>
                <input type="file" id="gallery" name="gallery[]" accept="image/*" multiple
                       class="form-control form-control-sm @error('gallery') is-invalid @enderror @error('gallery.*') is-invalid @enderror">
                @error('gallery') <div class="invalid-feedback">{{ $message }}</div> @enderror
                @error('gallery.*') <div class="invalid-feedback">{{ $message }}</div> @enderror
                <div id="vistaGaleria" class="vista-galeria"></div>
                <div class="form-text">Puedes elegir varias a la vez (máximo 5 MB cada una). Se suben al guardar.</div>
            </div>
        </div>
    </div>
</div>