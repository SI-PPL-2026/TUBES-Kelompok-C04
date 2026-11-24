@extends('layouts.app')

@section('title', 'Bahan Keluar')
@section('breadcrumb', 'Bahan Keluar')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">CORE TRANSAKSI</span>
            <h1>Bahan Keluar</h1>
            <p>Kelola riwayat pengambilan bahan baku dari gudang oleh outlet.</p>
        </div>
        <a class="button button-primary" href="{{ route('stock-outs.create') }}"><span class="button-plus">＋</span> Catat bahan keluar</a>
    </div>

    <section class="summary-banner">
        <div><span class="summary-label">TOTAL TRANSAKSI KELUAR</span><strong>{{ number_format($stockOuts->count()) }} <small>transaksi</small></strong></div>
        <div class="summary-divider"></div>
        <div class="summary-message"><span class="summary-check">✓</span><span>Validasi stok &amp; pengurangan otomatis<br><small>Jumlah keluar dicek terhadap stok tersedia sebelum disimpan.</small></span></div>
        <div class="summary-decoration">↑</div>
    </section>

    <section class="panel table-panel">
        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode</th>
                        <th>Bahan Baku</th>
                        <th>Outlet / Tujuan</th>
                        <th>Jumlah</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOuts as $item)
                        <tr>
                            <td>{{ optional($item->issued_date)->translatedFormat('d M Y') ?? '—' }}</td>
                            <td><span class="code-pill">{{ $item->ingredient?->code ?? '—' }}</span></td>
                            <td><strong>{{ $item->ingredient?->name ?? 'Bahan terhapus' }}</strong></td>
                            <td>{{ $item->outlet_name ?: '—' }}</td>
                            <td><span class="category-pill">-{{ number_format((float) $item->quantity, 2, ',', '.') }} {{ $item->ingredient?->unit ?? '' }}</span></td>
                            <td class="address-cell">{{ $item->notes ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="table-empty">
                                    <span class="empty-icon">↑</span>
                                    <strong>Belum ada data bahan keluar</strong>
                                    <p>Catat pengambilan bahan baku oleh outlet untuk mengurangi stok gudang.</p>
                                    <a class="button button-secondary button-small" href="{{ route('stock-outs.create') }}">Catat bahan keluar</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection