@extends('layouts.app')

@section('title', 'Tambah Lab')
@section('page-title', 'Tambah Laboratorium')

@section('content')
    <div class="card max-w-xl p-6">
        <form method="POST" action="{{ route('admin.labs.store') }}">
            @csrf
            @include('admin.labs._form')
        </form>
    </div>
@endsection
