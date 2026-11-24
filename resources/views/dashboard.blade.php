@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div><span class="eyebrow">RINGKASAN</span><h1>Dashboard</h1><p>Selamat datang, {{ auth()->user()->name }}. Berikut ringkasan data inventori Anda.</p></div>
        <a class="button button-primary" href="{{ route('ingredients.create') }}"><span class="button-plus">＋</span> Tambah bahan</a>
    </div>

    <section class="stats-grid" aria-label="Ringkasan data">
        <article class="stat-card">
            <div class="stat-icon icon-lilac">▤</div><span class="stat-label">Total bahan baku</span>
            <div class="stat-value">{{ number_format($ingredientCount) }} <span>item</span></div>
            <div class="stat-foot"><span class="stat-foot-dot purple"></span>Terdaftar di inventori</div>
        </article>
        <article class="stat-card">
            <div class="stat-icon icon-mint">♧</div><span class="stat-label">Supplier</span>
            <div class="stat-value">{{ number_format($supplierCount) }} <span>mitra</span></div>
            <div class="stat-foot"><span class="stat-foot-dot green"></span>Mitra pemasok tersimpan</div>
        </article>
        <article class="stat-card stat-card-highlight">
            <div class="highlight-top"><span class="stat-icon icon-white">✳</span><span class="live-pill"><i></i> {{ $lowStockCount > 0 ? number_format($lowStockCount) . ' PERLU RESTOCK' : 'STOK AMAN' }}</span></div>
            <span class="stat-label">Pemantauan stok gudang</span>
            <div class="highlight-copy">{{ $lowStockCount > 0 ? number_format($lowStockCount) . ' bahan di bawah' : 'Semua bahan memenuhi' }}<br>batas minimum stok.</div>
            <a href="{{ $lowStockCount > 0 ? route('stocks.minimum') : route('stocks.index') }}" class="text-link">{{ $lowStockCount > 0 ? 'Lihat stok minimum' : 'Pantau stok gudang' }} <span>→</span></a>
        </article>
    </section>

    <section class="dashboard-grid">
        <article class="panel">
            <div class="panel-heading"><div><h2>Bahan baku terbaru</h2><p>Data terakhir yang ditambahkan ke inventori.</p></div><a class="subtle-link" href="{{ route('ingredients.index') }}">Lihat semua <span>→</span></a></div>
            @if ($recentIngredients->isEmpty())
                <div class="empty-state"><div class="empty-icon">▤</div><strong>Belum ada bahan baku</strong><p>Tambahkan bahan pertama untuk mulai mengisi inventori.</p><a class="button button-secondary button-small" href="{{ route('ingredients.create') }}">Tambah bahan baku</a></div>
            @else
                <div class="compact-list">
                    @foreach ($recentIngredients as $ingredient)
                        <a class="compact-row" href="{{ route('ingredients.edit', $ingredient) }}">
                            <span class="item-symbol">{{ strtoupper(substr($ingredient->name, 0, 1)) }}</span>
                            <span class="compact-main"><strong>{{ $ingredient->name }}</strong><small>{{ $ingredient->code }} · {{ $ingredient->category }}</small></span>
                            <span class="compact-meta">Min. {{ number_format((float) $ingredient->minimum_stock, 2, ',', '.') }} {{ $ingredient->unit }}</span>
                            <span class="row-arrow">↗</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </article>
        <article class="panel supplier-panel">
            <div class="panel-heading"><div><h2>Supplier</h2><p>Mitra pemasok terdaftar.</p></div><a class="round-link" href="{{ route('suppliers.create') }}" aria-label="Tambah supplier">＋</a></div>
            @if ($recentSuppliers->isEmpty())
                <div class="empty-state compact-empty"><div class="empty-icon">♧</div><strong>Belum ada supplier</strong><p>Tambahkan data pemasok untuk memulai.</p></div>
            @else
                <div class="supplier-mini-list">
                    @foreach ($recentSuppliers as $supplier)
                        <div class="supplier-mini-row"><span class="supplier-avatar">{{ strtoupper(substr($supplier->name, 0, 1)) }}</span><span><strong>{{ $supplier->name }}</strong><small>{{ $supplier->contact_person ?: ($supplier->phone ?: 'Supplier') }}</small></span></div>
                    @endforeach
                </div>
                <a class="subtle-link supplier-all-link" href="{{ route('suppliers.index') }}">Kelola supplier <span>→</span></a>
            @endif
        </article>
    </section>
    <div class="dashboard-note"><span>✦</span><p>Catat bahan dan pemasok dengan konsisten untuk mendukung pengelolaan persediaan yang lebih efisien.</p><span class="note-credit">TAKAR INVENTORY</span></div>
@endsection
