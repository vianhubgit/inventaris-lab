@extends('layouts.app')

@section('title', 'Edit Pengajuan')
@section('page-title', 'Edit Pengajuan')

@section('content')
    <div class="card max-w-2xl p-6">
        <form method="POST" action="{{ route('sekretaris.procurements.update', $procurement) }}">
            @csrf @method('PUT')
            @include('sekretaris.procurements._form')
        </form>
    </div>
@endsection
