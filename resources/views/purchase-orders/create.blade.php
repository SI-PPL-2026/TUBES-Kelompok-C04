@extends('layouts.app')

@section('title', 'Buat Pesanan')
@section('breadcrumb', 'Pesanan / Buat pesanan')

@section('content')
    <style>
        .po-select {
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
        .po-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        .order-item-card .field-group input[type="number"] {
            height: 2.75rem;
            box-sizing: border-box;
        }
        .order-item-card .remove-item-btn {
            height: 2.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="page-heading">
        <div>
            <span class="eyebrow">CORE TRANSAKSI</span>
            <h1>Buat Pesanan</h1>
            <p>Catat pemesanan bahan baku baru ke supplier.</p>
        </div>
        <a class="button button-secondary" href="{{ route('purchase-orders.index') }}">← <span>Kembali</span></a>
    </div>

    <form class="panel form-panel" method="POST" action="{{ route('purchase-orders.store') }}">
        @csrf

        <div class="form-section-heading">
            <span class="form-section-icon">▤</span>
            <div>
                <h2>Informasi Pesanan</h2>
                <p>Kolom bertanda <span class="required-mark">*</span> wajib diisi.</p>
            </div>
        </div>

        <div class="form-grid">
            <div class="field-group">
                <label for="order_number">Nomor pesanan <span class="required-mark">*</span></label>
                <input
                    id="order_number"
                    name="order_number"
                    value="{{ old('order_number', $nextOrderNumber) }}"
                    required
                    maxlength="50"
                >
                @error('order_number')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field-group">
                <label for="order_date">Tanggal pesan <span class="required-mark">*</span></label>
                <input
                    id="order_date"
                    name="order_date"
                    type="date"
                    value="{{ old('order_date', now()->toDateString()) }}"
                    required
                >
                @error('order_date')<span class="field-error">{{ $message }}</span>@enderror
            </div>

            <div class="field-group field-span-2">
                <label for="supplier_id">Supplier <span class="required-mark">*</span></label>
                <select id="supplier_id" name="supplier_id" class="po-select" required>
                    <option value="">-- Pilih supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" @selected((string) old('supplier_id') === (string) $supplier->id)>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
                @error('supplier_id')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <hr style="margin: 1.75rem 0; border: 0; border-top: 1px solid #e5e7eb;">

        <div class="form-section-heading" style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span class="form-section-icon">＋</span>
                <div>
                    <h2>Daftar Bahan Baku Dipesan</h2>
                    <p>Pilih bahan baku dan masukkan jumlah (quantity). Minimal 1 bahan baku.</p>
                </div>
            </div>
            <button type="button" id="add-item-btn" class="button button-secondary button-small">
                + Tambah baris bahan
            </button>
        </div>

        @error('items')<div class="field-error" style="margin-bottom: 0.75rem;">{{ $message }}</div>@enderror

        @php
            $oldItems = array_values(old('items', [['ingredient_id' => '', 'quantity' => '']]));
        @endphp

        <div id="order-items-container" style="display: flex; flex-direction: column; gap: 0.875rem; margin-bottom: 1.5rem;">
            @foreach ($oldItems as $index => $oldItem)
                <div class="order-item-card" style="display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) auto; gap: 0.875rem; align-items: end; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.75rem;">
                    <div class="field-group" style="margin-bottom: 0;">
                        <label class="item-label" for="items_{{ $index }}_ingredient_id">Bahan baku #{{ $index + 1 }} <span class="required-mark">*</span></label>
                        <select
                            class="po-select item-ingredient-select"
                            id="items_{{ $index }}_ingredient_id"
                            name="items[{{ $index }}][ingredient_id]"
                            required
                        >
                            <option value="">-- Pilih bahan baku --</option>
                            @foreach ($ingredients as $ingredient)
                                <option
                                    value="{{ $ingredient->id }}"
                                    @selected((string) ($oldItem['ingredient_id'] ?? '') === (string) $ingredient->id)
                                >
                                    {{ $ingredient->code }} - {{ $ingredient->name }} ({{ $ingredient->unit }})
                                </option>
                            @endforeach
                        </select>
                        @error("items.{$index}.ingredient_id")<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="field-group" style="margin-bottom: 0;">
                        <label class="item-qty-label" for="items_{{ $index }}_quantity">Jumlah <span class="required-mark">*</span></label>
                        <input
                            class="item-qty-input"
                            id="items_{{ $index }}_quantity"
                            name="items[{{ $index }}][quantity]"
                            type="number"
                            min="0.01"
                            step="0.01"
                            value="{{ $oldItem['quantity'] ?? '' }}"
                            placeholder="0.00"
                            required
                        >
                        @error("items.{$index}.quantity")<span class="field-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="field-group" style="margin-bottom: 0;">
                        <button
                            type="button"
                            class="button button-secondary button-small remove-item-btn"
                            title="Hapus baris bahan"
                            aria-label="Hapus baris bahan"
                        >
                            ✕ Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-grid">
            <div class="field-group field-span-2">
                <label for="notes">Catatan</label>
                <textarea id="notes" name="notes" rows="3" placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                @error('notes')<span class="field-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-actions">
            <a class="button button-secondary" href="{{ route('purchase-orders.index') }}">Batal</a>
            <button class="button button-primary" type="submit">Simpan pesanan <span>→</span></button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('order-items-container');
            const addBtn = document.getElementById('add-item-btn');
            const ingredientOptionsHtml = `
                <option value="">-- Pilih bahan baku --</option>
                @foreach ($ingredients as $ingredient)
                    <option value="{{ $ingredient->id }}">{{ addslashes($ingredient->code . ' - ' . $ingredient->name . ' (' . $ingredient->unit . ')') }}</option>
                @endforeach
            `;

            function refreshRows() {
                const cards = container.querySelectorAll('.order-item-card');
                cards.forEach((card, idx) => {
                    const label = card.querySelector('.item-label');
                    const select = card.querySelector('.item-ingredient-select');
                    const qtyLabel = card.querySelector('.item-qty-label');
                    const qtyInput = card.querySelector('.item-qty-input');
                    const removeBtn = card.querySelector('.remove-item-btn');

                    const selectId = `items_${idx}_ingredient_id`;
                    const qtyId = `items_${idx}_quantity`;

                    if (label) {
                        label.setAttribute('for', selectId);
                        label.innerHTML = `Bahan baku #${idx + 1} <span class="required-mark">*</span>`;
                    }
                    if (select) {
                        select.id = selectId;
                        select.name = `items[${idx}][ingredient_id]`;
                    }
                    if (qtyLabel) {
                        qtyLabel.setAttribute('for', qtyId);
                    }
                    if (qtyInput) {
                        qtyInput.id = qtyId;
                        qtyInput.name = `items[${idx}][quantity]`;
                    }
                    if (removeBtn) {
                        removeBtn.disabled = cards.length === 1;
                        removeBtn.style.opacity = cards.length === 1 ? '0.45' : '1';
                        removeBtn.style.cursor = cards.length === 1 ? 'not-allowed' : 'pointer';
                    }
                });
            }

            addBtn.addEventListener('click', function () {
                const idx = container.querySelectorAll('.order-item-card').length;
                const card = document.createElement('div');
                card.className = 'order-item-card';
                card.style.cssText = 'display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr) auto; gap: 0.875rem; align-items: end; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.75rem;';
                card.innerHTML = `
                    <div class="field-group" style="margin-bottom: 0;">
                        <label class="item-label" for="items_${idx}_ingredient_id">Bahan baku #${idx + 1} <span class="required-mark">*</span></label>
                        <select class="po-select item-ingredient-select" id="items_${idx}_ingredient_id" name="items[${idx}][ingredient_id]" required>
                            ${ingredientOptionsHtml}
                        </select>
                    </div>
                    <div class="field-group" style="margin-bottom: 0;">
                        <label class="item-qty-label" for="items_${idx}_quantity">Jumlah <span class="required-mark">*</span></label>
                        <input class="item-qty-input" id="items_${idx}_quantity" name="items[${idx}][quantity]" type="number" min="0.01" step="0.01" placeholder="0.00" required>
                    </div>
                    <div class="field-group" style="margin-bottom: 0;">
                        <button type="button" class="button button-secondary button-small remove-item-btn" title="Hapus baris bahan" aria-label="Hapus baris bahan">
                            ✕ Hapus
                        </button>
                    </div>
                `;
                container.appendChild(card);
                refreshRows();
            });

            container.addEventListener('click', function (event) {
                const removeBtn = event.target.closest('.remove-item-btn');
                if (!removeBtn) return;

                const cards = container.querySelectorAll('.order-item-card');
                if (cards.length <= 1) return;

                removeBtn.closest('.order-item-card')?.remove();
                refreshRows();
            });

            refreshRows();
        });
    </script>
@endsection