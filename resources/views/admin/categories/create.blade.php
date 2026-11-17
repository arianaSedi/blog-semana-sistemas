@extends('layouts.admin')

@section('title', 'Nueva categoría')

@section('content')
<h1 class="h3 font-display text-uppercase mb-4">Nueva categoría</h1>

<form action="{{ route('admin.categories.store') }}" method="POST" novalidate>
    @csrf
    @include('admin.categories._form')
</form>
@endsection