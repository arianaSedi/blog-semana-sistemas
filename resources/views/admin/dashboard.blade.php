@extends('layouts.admin')

@section('title', 'Panel')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div>
        <p class="eyebrow mb-1">Panel de control</p>
        <h1 class="h2 fw-bold mb-1">Hola, {{ auth()->user()->name }}</h1>
        <p class="text-muted mb-0">Resumen de publicaciones del Blog de la Semana de Sistemas 2026.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Ver entradas</a>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ Nueva entrada</a>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach ($stats as [$label, $numero, $color])
        <div class="col-6 col-md-4 col-xl">
            <div class="tarjeta tarjeta-cifra h-100 p-4" style="--acento: {{ $color }}">
                <p class="text-muted small fw-semibold mb-2">{{ $label }}</p>
                <p class="dato mb-0">{{ $numero }}</p>
            </div>
        </div>
    @endforeach
</div>

<div class="tarjeta p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <h2 class="h6 fw-bold mb-0">Avance general del blog</h2>
        <span class="small fw-semibold">{{ $completas }} de {{ $avance->count() }} actividades completas · {{ $porcentaje }}%</span>
    </div>
    <div class="progress barra-avance" role="progressbar" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100">
        <div class="progress-bar" style="width: {{ $porcentaje }}%"></div>
    </div>
    <p class="small text-muted mt-2 mb-0">Una actividad está completa cuando tiene una entrada publicada y la evidencia de cada integrante.</p>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="tarjeta h-100 overflow-hidden">
            <div class="p-4 pb-3">
                <h2 class="h5 fw-bold mb-1">Avance por actividad</h2>
                <p class="small text-muted mb-0">Estado de cada actividad y evidencias de asistencia.</p>
            </div>
            <div class="table-responsive">
                <table class="table tabla-panel align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            <th>Entrada</th>
                            @foreach ($users as $user)
                                <th>{{ $user->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($avance as $actividad)
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $actividad->name }}</span>
                                    @if ($actividad->completa)
                                        <span class="badge-completa ms-1">Completa</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($actividad->publicadas > 0)
                                        <span class="estado ok">Publicada</span>
                                    @elseif ($actividad->borradores > 0)
                                        <span class="estado pendiente">Borrador</span>
                                    @else
                                        <span class="estado vacio">Sin entrada</span>
                                    @endif
                                </td>
                                @foreach ($users as $user)
                                    <td>
                                        @if (($actividad->evidenciasPorUsuaria[$user->id] ?? 0) > 0)
                                            <span class="estado ok">Lista</span>
                                        @else
                                            <span class="estado falta">Falta</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="tarjeta h-100 overflow-hidden">
            <div class="p-4 pb-3 d-flex justify-content-between align-items-center">
                <h2 class="h5 fw-bold mb-0">Últimas modificadas</h2>
                <a href="{{ route('admin.posts.index') }}" class="small fw-semibold">Ver todas</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse ($latest as $post)
                    <a href="{{ route('admin.posts.edit', $post) }}" class="list-group-item list-group-item-action fila-entrada d-flex align-items-center gap-3 px-4 py-3">
                        @if ($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}" alt="" class="rounded-3 flex-shrink-0"
                                 style="width:64px; height:48px; object-fit:cover">
                        @else
                            <span class="rounded-3 bg-lima-claro flex-shrink-0" style="width:64px; height:48px"></span>
                        @endif
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-semibold text-truncate">{{ $post->title }}</div>
                            <small class="text-muted">{{ $post->category->name }} · {{ $post->activity_date->format('d/m/Y') }}</small>
                        </div>
                        <span class="estado {{ $post->status === 'publicado' ? 'ok' : 'pendiente' }} flex-shrink-0">
                            {{ \App\Models\post_model::STATUSES[$post->status] }}
                        </span>
                    </a>
                @empty
                    <div class="px-4 pb-4 text-muted small">
                        Aún no hay entradas. <a href="{{ route('admin.posts.create') }}">Crea la primera</a>.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection