@extends('layouts.app')

@section('title', 'Lapor Barang ' . $type->label())
@section('page-title', 'Laporan ' . $type->label())
@section('breadcrumb', 'Pilih lokasi dan barang menggunakan dropdown')

@section('content')
    <div class="card max-w-3xl p-6">
        <div class="mb-4 rounded-lg bg-{{ $type->value === 'rusak' ? 'red' : 'gray' }}-50 p-3 text-sm dark:bg-gray-700/40">
            Anda sedang membuat laporan <strong>{{ $type->label() }}</strong>.
        </div>
        <form method="POST" action="{{ route('sekretaris.reports.store') }}" enctype="multipart/form-data">
            @csrf
            @include('sekretaris.reports._form')
        </form>
    </div>
@endsection
