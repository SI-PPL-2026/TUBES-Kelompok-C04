@extends('layouts.app')

@section('title', 'Stok Minimum')
@section('breadcrumb', 'Stok Minimum')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">PEMANTAUAN STOK</span>
            <h1>Bahan di Bawah Batas Minimum</h1>
            <p>Daftar bahan baku yang stok gudangnya berada di bawah batas minimum dan perlu segera diisi ulang.</p>
        </div>
        <div class="heading-actions">
            <a class="button button-secondary" href="{{ route('stocks.index') }}">Semua stok gudang</a>
            <a class="button button-primary" href="{{ route('purchase-orders.create') }}"><span class="button-plus">＋</span> Buat pesanan</a>
        </div>
    </div>

    <section class="summary-banner {{ $totalLowStock > 0 ? 'summary-banner-warning' : '' }}">
        <div>
            <span class="summary-label">BAHAN PERLU RESTOCK</span>
            <strong>{{ number_format($totalLowStock) }} <small>jenis</small></strong>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-message">
            <span class="summary-check {{ $totalLowStock > 0 ? 'summary-check-warning' : '' }}">{{ $totalLowStock > 0 ? '!' : '✓' }}</span>
            @if ($totalLowStock > 0)
                <span>
                    {{ number_format($totalLowStock) }} bahan berada di bawah batas minimum ({{ number_format($outOfStockCount) }} habis)<br>
                    <small>Segera buat pesanan ke supplier atau catat penerimaan barang masuk.</small>
                </span>
            @else
                <span>
                    Semua stok bahan baku dalam kondisi aman<br>
                    <small>Tidak ada bahan baku yang berada di bawah batas minimum saat ini.</small>
                </span>
            @endif
        </div>
        <div class="summary-decoration">!</div>
    </section>

    <section class="panel table-panel">
        <div class="table-toolbar">
            <form class="filter-bar" method="GET" action="{{ route('stocks.minimum') }}">
                <div class="search-form">
                    <span class="search-icon" aria-hidden="true">⌕</span>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, kode, atau kategori..." aria-label="Cari bahan di bawah minimum">
                    @if ($search !== '')
                        <a class="clear-search" href="{{ route('stocks.minimum', array_filter(['category' => $category])) }}" aria-label="Hapus pencarian">×</a>
                    @endif
                </div>

                <select class="filter-select" name="category" aria-label="Filter kategori">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $itemCategory)
                        <option value="{{ $itemCategory }}" @selected($category === $itemCategory)>{{ $itemCategory }}</option>
                    @endforeach
                </select>

                <button class="search-submit" type="submit">Cari</button>

                @if ($search !== '' || $category !== '')
                    <a class="subtle-link" href="{{ route('stocks.minimum') }}">Reset</a>
                @endif
            </form>

            <span class="result-caption">{{ number_format($ingredients->total()) }} bahan di bawah minimum</span>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama bahan baku</th>
                        <th>Kategori</th>
                        <th>Stok saat ini</th>
                        <th>Batas minimum</th>
                        <th>Kekurangan</th>
                        <th>Status</th>
                        <th class="action-column">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ingredients as $ingredient)
                        @php
                            $statusKey = $ingredient->stockStatus();
                        @endphp
                        <tr>
                            <td><span class="code-pill">{{ $ingredient->code }}</span></td>
                            <td>
                                <div class="ingredient-name">
                                    <span class="ingredient-symbol">{{ strtoupper(substr($ingredient->name, 0, 1)) }}</span>
                                    <span>
                                        <strong>{{ $ingredient->name }}</strong>
                                        @if ($ingredient->description)
                                            <small>{{ $ingredient->description }}</small>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td><span class="category-pill">{{ $ingredient->category }}</span></td>
                            <td>
                                <strong class="stock-value stock-value-{{ $statusKey }}">{{ number_format((float) $ingredient->current_stock, 2, ',', '.') }}</strong>
                                <span class="unit-muted">{{ $ingredient->unit }}</span>
                            </td>
                            <td>
                                <strong class="minimum-value">{{ number_format((float) $ingredient->minimum_stock, 2, ',', '.') }}</strong>
                                <span class="unit-muted">{{ $ingredient->unit }}</span>
                            </td>
                            <td>
                                <strong class="deficit-value">-{{ number_format($ingredient->stockDeficit(), 2, ',', '.') }}</strong>
                                <span class="unit-muted">{{ $ingredient->unit }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-badge-{{ $statusKey }}">{{ $ingredient->stockStatusLabel() }}</span>
                            </td>
                            <td class="action-column">
                                <a class="edit-link" href="{{ route('stock-ins.create') }}">Restock <span>↗</span></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="table-empty">
                                    <span class="empty-icon">✓</span>
                                    <strong>{{ ($search !== '' || $category !== '') ? 'Bahan tidak ditemukan' : 'Tidak ada bahan di bawah batas minimum' }}</strong>
                                    <p>{{ ($search !== '' || $category !== '') ? 'Coba kata kunci atau kategori lain.' : 'Seluruh bahan baku di gudang saat ini memenuhi batas minimum persediaan.' }}</p>
                                    @if ($search === '' && $category === '')
                                        <a class="button button-secondary button-small" href="{{ route('stocks.index') }}">Lihat semua stok gudang</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($ingredients->hasPages())
            <div class="pagination-wrap">{{ $ingredients->links() }}</div>
        @endif
    </section>
@endsection
