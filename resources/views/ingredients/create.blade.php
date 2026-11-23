@extends('layouts.app')

@section('title', 'Tambah bahan baku')
@section('breadcrumb', 'Bahan baku / Tambah')

@section('content')
    <div class="page-heading">
        <div><span class="eyebrow">DATA MASTER</span><h1>Tambah bahan baku</h1><p>Catat identitas dan batas minimum persediaan.</p></div>
        <a class="button button-secondary" href="{{ route('ingredients.index') }}">← <span>Kembali</span></a>
    </div>
    <form class="panel form-panel" method="POST" action="{{ route('ingredients.store') }}">
        @csrf
        <div class="form-section-heading"><span class="form-section-icon">▤</span><div><h2>Informasi bahan baku</h2><p>Kolom bertanda <span class="required-mark">*</span> wajib diisi.</p></div></div>
        @include('ingredients.form-fields')
        <div class="form-actions"><a class="button button-secondary" href="{{ route('ingredients.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan bahan <span>→</span></button></div>
    </form>
@endsection
