@extends('layouts.app')

@section('title', 'Daftar Pesanan')
@section('breadcrumb', 'Pesanan')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">CORE TRANSAKSI</span>
            <h1>Daftar Pesanan</h1>
            <p>Pantau riwayat pemesanan bahan baku ke supplier beserta statusnya.</p>
        </div>
        <a class="button button-primary" href="{{ route('purchase-orders.create') }}">
            <span class="button-plus">＋</span> Buat pesanan
        </a>
    </div>

    <section class="summary-banner">
        <div>
            <span class="summary-label">TOTAL PESANAN</span>
            <strong>{{ number_format($orders->total()) }} <small>transaksi</small></strong>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-message">
            <span class="summary-check">✓</span>
            <span>Rekap pesanan ke supplier<br><small>Cek nomor PO, pemasok, tanggal, dan status terkini.</small></span>
        </div>
    </section>

    <section class="panel table-panel">
        <div class="table-toolbar">
            <form class="search-form" method="GET" action="{{ route('purchase-orders.index') }}">
                <span class="search-icon" aria-hidden="true">⌕</span>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nomor PO atau nama supplier..."
                    aria-label="Cari pesanan"
                >
                <select name="status" aria-label="Filter status pesanan">
                    <option value="">Semua status</option>
                    <option value="dipesan" @selected($status === 'dipesan')>Dipesan</option>
                    <option value="diterima" @selected($status === 'diterima')>Diterima</option>
                    <option value="dibatalkan" @selected($status === 'dibatalkan')>Dibatalkan</option>
                </select>
                @if ($search !== '' || $status !== '')
                    <a class="clear-search" href="{{ route('purchase-orders.index') }}" aria-label="Reset filter">×</a>
                @endif
                <button class="search-submit" type="submit">Cari</button>
            </form>
            <span class="result-caption">{{ number_format($orders->total()) }} pesanan ditemukan</span>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No. Pesanan</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Jumlah Item</th>
                        <th>Status</th>
                        <th>Dicatat Oleh</th>
                        <th class="action-column">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><span class="code-pill">{{ $order->order_number }}</span></td>
                            <td>{{ $order->order_date->translatedFormat('d M Y') }}</td>
                            <td><strong>{{ $order->supplier?->name ?? '—' }}</strong></td>
                            <td>{{ $order->items_count }} item</td>
                            <td><span class="category-pill">{{ $order->status_label }}</span></td>
                            <td>{{ $order->user?->name ?? '—' }}</td>
                            <td class="action-column">
                                <a class="edit-link" href="{{ route('purchase-orders.show', $order) }}">Detail <span>↗</span></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="table-empty">
                                    <span class="empty-icon">▤</span>
                                    <strong>{{ ($search !== '' || $status !== '') ? 'Pesanan tidak ditemukan' : 'Belum ada pesanan' }}</strong>
                                    <p>{{ ($search !== '' || $status !== '') ? 'Coba ubah kata kunci atau filter status.' : 'Catat pemesanan pertama ke supplier untuk memulai.' }}</p>
                                    @if ($search === '' && $status === '')
                                        <a class="button button-secondary button-small" href="{{ route('purchase-orders.create') }}">Buat pesanan</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="pagination-wrap">{{ $orders->links() }}</div>
        @endif
    </section>
@endsection