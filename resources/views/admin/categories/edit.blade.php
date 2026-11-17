@extends('layouts.admin')

@section('title', 'Editar categoría')

@section('content')
<h1 class="h3 font-display text-uppercase mb-4">Editar categoría</h1>

<form action="{{ route('admin.categories.update', $category) }}" method="POST" novalidate>
    @csrf
    @method('PUT')
    @include('admin.categories._form')
</form>
@endsection