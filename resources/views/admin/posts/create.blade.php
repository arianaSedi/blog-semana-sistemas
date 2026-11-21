@extends('layouts.admin')

@section('title', 'Nueva entrada')

@section('content')
<div class="cabecera-entrada pb-4 mb-4">
    <a href="{{ route('admin.posts.index') }}" class="enlace-volver">Volver a entradas</a>
    <h1 class="h3 fw-bold text-lima mt-3 mb-1">Nueva entrada</h1>
    <p class="mb-0">Puedes guardarla como borrador y publicarla cuando esté lista.</p>
</div>

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @include('admin.posts._form')
</form>
@endsection