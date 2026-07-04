@extends('layouts.app')

@section('title', 'Catat Perbaikan')
@section('page-title', 'Catat Riwayat Perbaikan')

@section('content')
    <div class="card max-w-3xl p-6">
        <form method="POST" action="{{ route('admin.repairs.store') }}">
            @csrf
            @include('admin.repairs._form')
        </form>
    </div>
@endsection
