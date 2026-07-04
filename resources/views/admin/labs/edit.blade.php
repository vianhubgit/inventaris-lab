@extends('layouts.app')

@section('title', 'Edit Lab')
@section('page-title', 'Edit Laboratorium')

@section('content')
    <div class="card max-w-xl p-6">
        <form method="POST" action="{{ route('admin.labs.update', $lab) }}">
            @csrf @method('PUT')
            @include('admin.labs._form')
        </form>
    </div>
@endsection
