@extends('layouts.app')

@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')

@section('content')
    <div class="card max-w-3xl p-6">
        <form method="POST" action="{{ route('admin.items.update', $item) }}">
            @csrf @method('PUT')
            @include('admin.items._form')
        </form>
    </div>
@endsection
