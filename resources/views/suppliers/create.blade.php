@extends('layouts.app')

@section('title', 'Tambah supplier')
@section('breadcrumb', 'Supplier / Tambah supplier')

@section('content')
    <div class="page-heading">
        <div><span class="eyebrow">DATA MASTER</span><h1>Tambah supplier</h1><p>Lengkapi informasi pemasok bahan baku.</p></div>
        <a class="button button-secondary" href="{{ route('suppliers.index') }}">← <span>Kembali</span></a>
    </div>
    <form class="panel form-panel" method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        <div class="form-section-heading"><span class="form-section-icon">♧</span><div><h2>Informasi supplier</h2><p>Kolom bertanda <span class="required-mark">*</span> wajib diisi.</p></div></div>
        <div class="form-grid">
            <div class="field-group field-span-2"><label for="name">Nama supplier <span class="required-mark">*</span></label><input id="name" name="name" value="{{ old('name') }}" placeholder="Contoh: Sumber Rezeki Sejahtera" required maxlength="120">@error('name')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group"><label for="contact_person">Nama kontak</label><input id="contact_person" name="contact_person" value="{{ old('contact_person') }}" placeholder="Nama narahubung" maxlength="120">@error('contact_person')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group"><label for="phone">Nomor telepon</label><input id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xx xxxx xxxx" maxlength="40">@error('phone')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group"><label for="email">Email</label><input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="supplier@email.com" maxlength="160">@error('email')<span class="field-error">{{ $message }}</span>@enderror</div>
            <div class="field-group field-span-2"><label for="address">Alamat</label><textarea id="address" name="address" rows="3" placeholder="Alamat supplier">{{ old('address') }}</textarea>@error('address')<span class="field-error">{{ $message }}</span>@enderror</div>
        </div>
        <div class="form-actions"><a class="button button-secondary" href="{{ route('suppliers.index') }}">Batal</a><button class="button button-primary" type="submit">Simpan supplier <span>→</span></button></div>
    </form>
@endsection
