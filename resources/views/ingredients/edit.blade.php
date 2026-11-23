@extends('layouts.app')

@section('title', 'Ubah bahan baku')
@section('breadcrumb', 'Bahan baku / Ubah data')

@section('content')
    <div class="page-heading">
        <div><span class="eyebrow">DATA MASTER</span><h1>Ubah bahan baku</h1><p>Perbarui informasi atau batas minimum persediaan.</p></div>
        <a class="button button-secondary" href="{{ route('ingredients.index') }}">← <span>Kembali</span></a>
    </div>
    <form class="panel form-panel" method="POST" action="{{ route('ingredients.update', $ingredient) }}">
        @csrf
        @method('PUT')
        <div class="form-section-heading"><span class="form-section-icon">▤</span><div><h2>{{ $ingredient->name }}</h2><p>Perubahan akan langsung tersimpan ke data bahan baku.</p></div></div>
        @include('ingredients.form-fields', ['ingredient' => $ingredient])
        <div class="form-actions"><a class="button button-secondary" href="{{ route('ingredients.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan perubahan <span>→</span></button></div>
    </form>
@endsection
