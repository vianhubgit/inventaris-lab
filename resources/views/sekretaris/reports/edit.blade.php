@extends('layouts.app')

@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan ' . $report->type->label())

@section('content')
    <div class="card max-w-3xl p-6">
        <form method="POST" action="{{ route('sekretaris.reports.update', $report) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            @include('sekretaris.reports._form')
        </form>
    </div>
@endsection
