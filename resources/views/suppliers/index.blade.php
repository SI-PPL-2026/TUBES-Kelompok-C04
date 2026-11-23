@extends('layouts.app')

@section('title', 'Supplier')
@section('breadcrumb', 'Supplier')

@section('content')
    <div class="page-heading">
        <div><span class="eyebrow">DATA MASTER</span><h1>Supplier</h1><p>Kelola informasi pemasok bahan baku Sekala.</p></div>
        <a class="button button-primary" href="{{ route('suppliers.create') }}"><span class="button-plus">＋</span> Tambah supplier</a>
    </div>
    <section class="summary-banner supplier-summary">
        <div><span class="summary-label">MITRA PEMASOK</span><strong>{{ number_format($suppliers->total()) }} <small>supplier</small></strong></div>
        <div class="summary-divider"></div>
        <div class="summary-message"><span class="summary-check">✓</span><span>Daftar pemasok tersimpan terpusat<br><small>Data dapat dicari dan dilihat kapan saja.</small></span></div>
        <div class="summary-decoration">♧</div>
    </section>
    <section class="panel table-panel">
        <div class="table-toolbar">
            <form class="search-form" method="GET" action="{{ route('suppliers.index') }}">
                <span class="search-icon" aria-hidden="true">⌕</span>
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, kontak, atau email..." aria-label="Cari supplier">
                @if ($search !== '')<a class="clear-search" href="{{ route('suppliers.index') }}" aria-label="Hapus pencarian">×</a>@endif
                <button class="search-submit" type="submit">Cari</button>
            </form>
            <span class="result-caption">{{ number_format($suppliers->total()) }} supplier terdaftar</span>
        </div>
        <div class="table-scroll">
            <table class="data-table">
                <thead><tr><th>Nama supplier</th><th>Kontak</th><th>Telepon</th><th>Email</th><th>Alamat</th></tr></thead>
                <tbody>
                    @forelse ($suppliers as $supplier)
                        <tr>
                            <td><div class="table-name"><span class="supplier-avatar">{{ strtoupper(substr($supplier->name, 0, 1)) }}</span><strong>{{ $supplier->name }}</strong></div></td>
                            <td>{{ $supplier->contact_person ?: '—' }}</td>
                            <td>{{ $supplier->phone ?: '—' }}</td>
                            <td>{{ $supplier->email ?: '—' }}</td>
                            <td class="address-cell">{{ $supplier->address ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5"><div class="table-empty"><span class="empty-icon">♧</span><strong>{{ $search !== '' ? 'Supplier tidak ditemukan' : 'Belum ada data supplier' }}</strong><p>{{ $search !== '' ? 'Coba kata kunci yang berbeda.' : 'Tambahkan supplier untuk mulai mengelola daftar pemasok.' }}</p>@if ($search === '')<a class="button button-secondary button-small" href="{{ route('suppliers.create') }}">Tambah supplier</a>@endif</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($suppliers->hasPages())
            <div class="pagination-wrap">{{ $suppliers->links() }}</div>
        @endif
    </section>
@endsection
