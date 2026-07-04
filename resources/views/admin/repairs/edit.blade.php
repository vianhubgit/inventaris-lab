@extends('layouts.app')

@section('title', 'Edit Perbaikan')
@section('page-title', 'Edit Riwayat Perbaikan')

@section('content')
    <div class="card max-w-3xl p-6">
        <form method="POST" action="{{ route('admin.repairs.update', $repair) }}">
            @csrf @method('PUT')
            @include('admin.repairs._form')
        </form>
    </div>
@endsection
