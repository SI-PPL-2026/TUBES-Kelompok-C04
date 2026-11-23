@extends('layouts.app')

@section('title', 'Bahan baku')
@section('breadcrumb', 'Bahan baku')

@section('content')
    <div class="page-heading">
        <div><span class="eyebrow">DATA MASTER</span><h1>Bahan baku</h1><p>Kelola data dan batas minimum persediaan bahan.</p></div>
        <a class="button button-primary" href="{{ route('ingredients.create') }}"><span class="button-plus">＋</span> Tambah bahan</a>
    </div>
    <section class="summary-banner ingredient-summary">
        <div><span class="summary-label">TOTAL BAHAN BAKU</span><strong>{{ number_format($ingredients->total()) }} <small>jenis</small></strong></div>
        <div class="summary-divider"></div>
        <div class="summary-message"><span class="summary-check">✓</span><span>Batas minimum tersedia di setiap data<br><small>Atur ambang persediaan saat bahan dibuat atau diperbarui.</small></span></div>
        <div class="summary-decoration">▤</div>
    </section>
    <section class="panel table-panel">
        <div class="table-toolbar">
            <form class="search-form" method="GET" action="{{ route('ingredients.index') }}">
                <span class="search-icon" aria-hidden="true">⌕</span>
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, kode, atau kategori..." aria-label="Cari bahan baku">
                @if ($search !== '')<a class="clear-search" href="{{ route('ingredients.index') }}" aria-label="Hapus pencarian">×</a>@endif
                <button class="search-submit" type="submit">Cari</button>
            </form>
            <span class="result-caption">{{ number_format($ingredients->total()) }} bahan terdaftar</span>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>Kode</th><th>Nama bahan baku</th><th>Kategori</th><th>Satuan</th><th>Batas minimum</th><th class="action-column">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($ingredients as $ingredient)
                        <tr>
                            <td><span class="code-pill">{{ $ingredient->code }}</span></td>
                            <td><div class="ingredient-name"><span class="ingredient-symbol">{{ strtoupper(substr($ingredient->name, 0, 1)) }}</span><span><strong>{{ $ingredient->name }}</strong>@if ($ingredient->description)<small>{{ $ingredient->description }}</small>@endif</span></div></td>
                            <td><span class="category-pill">{{ $ingredient->category }}</span></td>
                            <td>{{ $ingredient->unit }}</td>
                            <td><strong class="minimum-value">{{ number_format((float) $ingredient->minimum_stock, 2, ',', '.') }}</strong> <span class="unit-muted">{{ $ingredient->unit }}</span></td>
                            <td class="action-column"><a class="edit-link" href="{{ route('ingredients.edit', $ingredient) }}">Ubah <span>↗</span></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><div class="table-empty"><span class="empty-icon">▤</span><strong>{{ $search !== '' ? 'Bahan baku tidak ditemukan' : 'Belum ada data bahan baku' }}</strong><p>{{ $search !== '' ? 'Coba kata kunci yang berbeda.' : 'Tambahkan bahan baku pertama untuk mulai mengelola inventori.' }}</p>@if ($search === '')<a class="button button-secondary button-small" href="{{ route('ingredients.create') }}">Tambah bahan baku</a>@endif</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($ingredients->hasPages())
            <div class="pagination-wrap">{{ $ingredients->links() }}</div>
        @endif
    </section>
@endsection
