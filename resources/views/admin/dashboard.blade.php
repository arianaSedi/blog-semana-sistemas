@extends('layouts.admin')

@section('title', 'Panel')

@section('content')
<div class="p-4 p-md-5 mb-4 rounded-4 bg-marino text-white">
    <h1 class="h2 font-display text-uppercase mb-1">Hola, {{ auth()->user()->name }}</h1>
    <p class="mb-0 text-white-50">Resumen de publicaciones del Blog de la Semana de Sistemas 2026</p>
</div>

<div class="row g-3 mb-4">
    @foreach ($stats as $label => $n)
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 {{ $label === 'Publicadas' ? 'bg-primary' : '' }}">
                <div class="card-body">
                    <p class="display-6 fw-bold font-display mb-0">{{ $n }}</p>
                    <p class="mb-0 {{ $label === 'Publicadas' ? '' : 'text-muted' }}">{{ $label }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold font-display text-uppercase">Entradas por actividad</div>
            <ul class="list-group list-group-flush">
                @foreach ($categories as $category)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $category->name }}
                        <span class="badge rounded-pill {{ $category->posts_count ? 'bg-primary' : 'bg-secondary' }}">
                            {{ $category->posts_count }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white fw-bold font-display text-uppercase">Últimas entradas</div>
            <ul class="list-group list-group-flush">
                @forelse ($latest as $post)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $post->title }}</div>
                            <small class="text-muted">{{ $post->category->name }} · {{ $post->activity_date->format('d/m/Y') }}</small>
                        </div>
                        <span class="badge {{ $post->isPublished() ? 'bg-primary' : 'bg-secondary' }}">
                            {{ \App\Models\post_model::STATUSES[$post->status] }}
                        </span>
                    </li>
                @empty
                    <li class="list-group-item text-muted text-center py-4">Aún no hay entradas.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection