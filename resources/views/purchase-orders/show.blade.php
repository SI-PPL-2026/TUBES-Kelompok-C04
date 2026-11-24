@extends('layouts.app')

@section('title', 'Detail Pesanan ' . $purchaseOrder->order_number)
@section('breadcrumb', 'Pesanan / Detail')

@section('content')
    <style>
        .po-detail-card {
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }
        .po-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.15rem 2rem;
        }
        .po-meta-item {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }
        .po-meta-label {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            color: #6b7280;
        }
        .po-meta-value {
            font-size: 0.95rem;
            font-weight: 500;
            color: #111827;
            line-height: 1.5;
        }
        .po-notes-box {
            margin-top: 1.25rem;
            padding-top: 1.15rem;
            border-top: 1px solid #f3f4f6;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }
        .po-items-header {
            padding: 1.15rem 1.5rem 0.9rem;
            border-bottom: 1px solid #f3f4f6;
        }
        .po-items-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: #111827;
        }
        .po-items-subtitle {
            margin: 0.2rem 0 0;
            font-size: 0.825rem;
            color: #6b7280;
        }
        @media (max-width: 640px) {
            .po-detail-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-heading">
        <div>
            <span class="eyebrow">CORE TRANSAKSI</span>
            <h1>{{ $purchaseOrder->order_number }}</h1>
            <p>Rincian pemesanan bahan baku ke supplier.</p>
        </div>
        <a class="button button-secondary" href="{{ route('purchase-orders.index') }}">← <span>Kembali</span></a>
    </div>

    <section class="panel po-detail-card">
        <div class="po-detail-grid">
            <div class="po-meta-item">
                <span class="po-meta-label">SUPPLIER</span>
                <span class="po-meta-value">{{ $purchaseOrder->supplier?->name ?? '—' }}</span>
            </div>

            <div class="po-meta-item">
                <span class="po-meta-label">TANGGAL PESAN</span>
                <span class="po-meta-value">{{ $purchaseOrder->order_date->translatedFormat('d F Y') }}</span>
            </div>

            <div class="po-meta-item">
                <span class="po-meta-label">STATUS</span>
                <div><span class="category-pill">{{ $purchaseOrder->status_label }}</span></div>
            </div>

            <div class="po-meta-item">
                <span class="po-meta-label">DICATAT OLEH</span>
                <span class="po-meta-value">{{ $purchaseOrder->user?->name ?? '—' }}</span>
            </div>
        </div>

        <div class="po-notes-box">
            <span class="po-meta-label">CATATAN</span>
            <span class="po-meta-value">{{ $purchaseOrder->notes ?: '—' }}</span>
        </div>
    </section>

    <section class="panel table-panel">
        <div class="po-items-header">
            <h2 class="po-items-title">Item Bahan Baku</h2>
            <p class="po-items-subtitle">Daftar bahan dan jumlah yang dipesan pada transaksi ini.</p>
        </div>

        <div class="table-scroll">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Bahan Baku</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->items as $item)
                        <tr>
                            <td><span class="code-pill">{{ $item->ingredient?->code ?? '—' }}</span></td>
                            <td><strong>{{ $item->ingredient?->name ?? '—' }}</strong></td>
                            <td><strong>{{ number_format((float) $item->quantity, 2, ',', '.') }}</strong></td>
                            <td>{{ $item->ingredient?->unit ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection