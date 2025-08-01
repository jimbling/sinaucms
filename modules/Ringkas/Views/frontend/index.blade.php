@extends('themes.' . getActiveTheme() . '.app_statis')

@section('title', strtoupper($judul ?? 'Ringkas'))

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden p-5">
        <h1 class="text-2xl font-bold mb-4 uppercase">{{ $judul ?? 'Ringkas' }}</h1>

        <p>Halaman frontend untuk modul Ringkas.</p>
    </div>
</div>
@endsection