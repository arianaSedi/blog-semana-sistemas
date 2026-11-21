@extends('layouts.admin')

@section('title', 'Nueva categoría')

@section('content')
<div class="cabecera-entrada pb-4 mb-4">
    <a href="{{ route('admin.categories.index') }}" class="enlace-volver">Volver a categorías</a>
    <h1 class="h3 fw-bold text-lima mt-3 mb-1">Nueva categoría</h1>
    <p class="mb-0">Agrega una actividad de la semana para clasificar las entradas.</p>
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-8">
        <form action="{{ route('admin.categories.store') }}" method="POST" novalidate>
            @csrf
            @include('admin.categories._form')
        </form>
    </div>
    <div class="col-lg-4">
        <div class="tarjeta p-4">
            <div class="encabezado-seccion">
                <h2 class="h6 fw-bold mb-0">Contenido mínimo</h2>
            </div>
            <p class="small text-muted mb-0">
                Lo que escribas aquí aparece como guía en el formulario de entradas,
                para recordar qué debe incluir cada publicación de esta actividad.
            </p>
        </div>
    </div>
</div>
@endsection