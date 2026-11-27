@extends('layouts.app')

@section('title', 'Stok Gudang')
@section('breadcrumb', 'Stok Gudang')

@section('content')
    <div class="page-heading">
        <div>
            <span class="eyebrow">PEMANTAUAN STOK</span>
            <h1>Daftar Stok &amp; Ketersediaan Gudang</h1>
            <p>Pantau ketersediaan stok terkini seluruh bahan baku di gudang secara real-time.</p>
        </div>
        <div class="heading-actions">
            <a class="button button-secondary" href="{{ route('stocks.minimum') }}">Stok minimum ({{ number_format($lowStockCount) }})</a>
            <a class="button button-primary" href="{{ route('stock-ins.create') }}"><span class="button-plus">＋</span> Catat bahan masuk</a>
        </div>
    </div>

    <section class="stats-grid stock-stats-grid" aria-label="Ringkasan stok gudang">
        <article class="stat-card">
            <div class="stat-icon icon-lilac">▤</div>
            <span class="stat-label">Total bahan baku</span>
            <div class="stat-value">{{ number_format($totalIngredients) }} <span>item</span></div>
            <div class="stat-foot"><span class="stat-foot-dot purple"></span>Terdaftar di gudang</div>
        </article>
        <article class="stat-card">
            <div class="stat-icon icon-mint">✓</div>
            <span class="stat-label">Stok aman</span>
            <div class="stat-value">{{ number_format($safeStockCount) }} <span>item</span></div>
            <div class="stat-foot"><span class="stat-foot-dot green"></span>Memenuhi batas minimum</div>
        </article>
        <article class="stat-card">
            <div class="stat-icon icon-amber">!</div>
            <span class="stat-label">Di bawah batas minimum</span>
            <div class="stat-value">{{ number_format($lowStockCount) }} <span>item</span></div>
            <div class="stat-foot"><span class="stat-foot-dot amber"></span>{{ number_format($outOfStockCount) }} item habis (0)</div>
        </article>
    </section>

    <section class="panel table-panel stock-table-panel">
        <div class="table-toolbar">
            <form class="filter-bar" method="GET" action="{{ route('stocks.index') }}">
                <div class="search-form">
                    <span class="search-icon" aria-hidden="true">⌕</span>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, kode, atau kategori..." aria-label="Cari stok bahan">
                    @if ($search !== '')
                        <a class="clear-search" href="{{ route('stocks.index', array_filter(['category' => $category, 'status' => $status])) }}" aria-label="Hapus pencarian">×</a>
                    @endif
                </div>

                <select class="filter-select" name="category" aria-label="Filter kategori">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $itemCategory)
                        <option value="{{ $itemCategory }}" @selected($category === $itemCategory)>{{ $itemCategory }}</option>
                    @endforeach
                </select>

                <select class="filter-select" name="status" aria-label="Filter status ketersediaan">
                    <option value="">Semua status</option>
                    <option value="safe" @selected($status === 'safe')>Aman</option>
                    <option value="low" @selected($status === 'low')>Di bawah minimum</option>
                    <option value="out" @selected($status === 'out')>Habis</option>
                </select>

                <button class="search-submit" type="submit">Terapkan</button>

                @if ($search !== '' || $category !== '' || $status !== '')
                    <a class="subtle-link" href="{{ route('stocks.index') }}">Reset</a>
                @endif
            </form>

            <span class="result-caption">{{ number_format($ingredients->total()) }} bahan ditampilkan</span>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama bahan baku</th>
                        <th>Kategori</th>
                        <th>Stok gudang</th>
                        <th>Batas minimum</th>
                        <th>Masuk / Keluar</th>
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
                                <span class="flow-in">+{{ number_format((float) ($ingredient->stock_ins_sum_quantity ?? 0), 2, ',', '.') }}</span>
                                <span class="unit-muted">/</span>
                                <span class="flow-out">-{{ number_format((float) ($ingredient->stock_outs_sum_quantity ?? 0), 2, ',', '.') }}</span>
                                <span class="unit-muted">{{ $ingredient->unit }}</span>
                            </td>
                            <td>
                                <span class="status-badge status-badge-{{ $statusKey }}">{{ $ingredient->stockStatusLabel() }}</span>
                            </td>
                            <td class="action-column">
                                <a class="edit-link" href="{{ route('ingredients.edit', $ingredient) }}">Ubah <span>↗</span></a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="table-empty">
                                    <span class="empty-icon">▤</span>
                                    <strong>{{ ($search !== '' || $category !== '' || $status !== '') ? 'Data stok tidak ditemukan' : 'Belum ada data bahan baku' }}</strong>
                                    <p>{{ ($search !== '' || $category !== '' || $status !== '') ? 'Coba ubah kata kunci pencarian atau filter status.' : 'Tambahkan bahan baku terlebih dahulu untuk memantau ketersediaan stok gudang.' }}</p>
                                    @if ($search === '' && $category === '' && $status === '')
                                        <a class="button button-secondary button-small" href="{{ route('ingredients.create') }}">Tambah bahan baku</a>
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
