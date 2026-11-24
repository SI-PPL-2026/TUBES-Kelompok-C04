@extends('layouts.app')

@section('title', 'Catat Bahan Masuk')
@section('breadcrumb', 'Bahan Masuk / Catat bahan masuk')

@section('content')
    <style>
        .tx-select {
            width: 100%;
            height: 2.75rem;
            padding: 0.5rem 2.25rem 0.5rem 0.875rem;
            font-size: 0.9rem;
            line-height: 1.4;
            color: #111827;
            background-color: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 0.625rem;
            box-sizing: border-box;
        }
        .tx-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
    </style>

    <div class="page-heading">
        <div>
            <span class="eyebrow">CORE TRANSAKSI</span>
            <h1>Catat Bahan Masuk</h1>
            <p>Catat penerimaan bahan baku dari supplier ke gudang.</p>
        </div>
        <a class="button button-secondary" href="{{ route('stock-ins.index') }}">← <span>Kembali</span></a>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('stock-ins.store') }}">
        @csrf

        <div class="form-section-heading">
            <span class="form-section-icon">↓</span>
            <div>
                <h2>Informasi Bahan Masuk</h2>
                <p>Kolom bertanda <span class="required-mark">*</span> wajib diisi.</p>
            </div>
        </div>

        <div class="form-grid">
            <div class="field-group field-span-2">
                <label for="ingredient_id">Bahan baku <span class="required-mark">*</span></label>
                <select id="ingredient_id" name="ingredient_id" class="tx-select" required>
                    <option value="">-- Pilih bahan baku --</option>
                    @foreach ($ingredients as$ingredient)
                        <option value="{{ $ingredient->id }}" @selected((string) old('ingredient_id') === (string) $ingredient->id)>
                            {{ $ingredient->code }} - {{$ingredient->name }} (Stok: {{ number_format((float) $ingredient->current_stock, 2, ',', '.') }} {{$ingredient->unit }})
                        </option>
                    @endforeach
                </select>
                @error('ingredient_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field-group field-span-2">
                <label for="supplier_id">Supplier</label>
                <select id="supplier_id" name="supplier_id" class="tx-select">
                    <option value="">-- Pilih supplier (opsional) --</option>
                    @foreach ($suppliers as$supplier)
                        <option value="{{ $supplier->id }}" @selected((string) old('supplier_id') === (string) $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field-group">
                <label for="quantity">Jumlah diterima <span class="required-mark">*</span></label>
                <input
                    id="quantity"
                    name="quantity"
                    type="number"
                    min="0.01"
                    step="0.01"
                    value="{{ old('quantity') }}"
                    placeholder="0.00"
                    required
                >
                @error('quantity')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field-group">
                <label for="received_date">Tanggal penerimaan <span class="required-mark">*</span></label>
                <input
                    id="received_date"
                    name="received_date"
                    type="date"
                    value="{{ old('received_date', now()->toDateString()) }}"
                    required
                >
                @error('received_date')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field-group field-span-2">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                @error('notes')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-actions">
            <a class="button button-secondary" href="{{ route('stock-ins.index') }}">Batal</a>
            <button class="button button-primary" type="submit">Simpan bahan masuk <span>→</span></button>
        </div>
    </form>
@endsection