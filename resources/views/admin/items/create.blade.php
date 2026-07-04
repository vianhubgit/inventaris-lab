@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('page-title', 'Tambah Barang')

@section('content')
    <div class="card max-w-3xl p-6">
        <form method="POST" action="{{ route('admin.items.store') }}">
            @csrf
            @include('admin.items._form')
        </form>
    </div>
@endsection
