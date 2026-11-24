@extends('layouts.app')

@section('title', 'Bahan Masuk')
@section('breadcrumb', 'Bahan Masuk')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">CORE TRANSAKSI</span>
            <h1>Bahan Masuk</h1>
            <p>Kelola riwayat penerimaan bahan baku dari supplier ke gudang.</p>
        </div>
        <a class="button button-primary" href="{{ route('stock-ins.create') }}"><span class="button-plus">＋</span> Catat bahan masuk</a>
    </div>

    <section class="summary-banner">
        <div><span class="summary-label">TOTAL TRANSAKSI MASUK</span><strong>{{ number_format($stockIns->count()) }} <small>transaksi</small></strong></div>
        <div class="summary-divider"></div>
        <div class="summary-message"><span class="summary-check">✓</span><span>Stok bahan baku otomatis bertambah<br><small>Setiap pencatatan masuk memperbarui stok gudang.</small></span></div>
        <div class="summary-decoration">↓</div>
    </section>

    <section class="panel table-panel">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Bahan Baku</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockIns as $item)
                        <tr>
                            <td>{{ optional($item->received_date)->translatedFormat('d M Y') ?? '—' }}</td>
                            <td><span class="code-pill">{{ $item->ingredient?->code ?? '—' }}</span></td>
                            <td><strong>{{ $item->ingredient?->name ?? 'Bahan terhapus' }}</strong></td>
                            <td>{{ $item->supplier?->name ?: '—' }}</td>
                            <td><span class="category-pill">+{{ number_format((float) $item->quantity, 2, ',', '.') }} {{ $item->ingredient?->unit ?? '' }}</span></td>
                            <td class="address-cell">{{ $item->notes ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="table-empty">
                                    <span class="empty-icon">↓</span>
                                    <strong>Belum ada data bahan masuk</strong>
                                    <p>Catat penerimaan barang dari supplier untuk menambah stok bahan baku.</p>
                                    <a class="button button-secondary button-small" href="{{ route('stock-ins.create') }}">Catat bahan masuk</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection