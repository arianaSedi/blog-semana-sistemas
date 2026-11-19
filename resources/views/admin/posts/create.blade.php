@extends('layouts.admin')

@section('title', 'Nueva entrada')

@section('content')
<h1 class="h3 font-display text-uppercase mb-4">Nueva entrada</h1>

<form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" novalidate>
    @csrf
    @include('admin.posts._form')
</form>
@endsection