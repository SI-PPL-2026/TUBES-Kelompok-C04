<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · Takar Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar" id="sidebar">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark">T</span>
                <span><strong>takar</strong><small>Inventory system</small></span>
            </a>

            <div class="nav-caption">MENU UTAMA</div>
            <nav class="main-nav" aria-label="Navigasi utama">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <span class="nav-icon">⌂</span><span>Dashboard</span>
                </a>
                <a class="nav-link {{ request()->routeIs('ingredients.*') ? 'active' : '' }}" href="{{ route('ingredients.index') }}">
                    <span class="nav-icon">▤</span><span>Bahan baku</span>
                </a>
                <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                    <span class="nav-icon">♧</span><span>Supplier</span>
                </a>
                <a class="nav-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}" href="{{ route('purchase-orders.index') }}">
                    <span class="nav-icon">◫</span><span>Pesanan</span>
                </a>
                <a class="nav-link {{ request()->routeIs('stock-ins.*') ? 'active' : '' }}" href="{{ route('stock-ins.index') }}">
                    <span class="nav-icon">↓</span><span>Bahan masuk</span>
                </a>
                <a class="nav-link {{ request()->routeIs('stock-outs.*') ? 'active' : '' }}" href="{{ route('stock-outs.index') }}">
                    <span class="nav-icon">↑</span><span>Bahan keluar</span>
                </a>
            </nav>

            <div class="nav-caption nav-caption-spaced">SISTEM</div>
            <div class="sidebar-note">
                <span class="note-dot"></span>
                <div><strong>Data tersimpan</strong><small>Terhubung ke database</small></div>
            </div>

            <div class="sidebar-user">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</div>
                <div class="user-details">
                    <strong>{{ auth()->user()->name ?? 'Pengguna' }}</strong>
                    <small>{{ auth()->user()->email ?? '' }}</small>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="icon-button logout-button" type="submit" aria-label="Keluar" title="Keluar">↪</button>
                </form>
            </div>
        </aside>

        <main class="main-area">
            <header class="topbar">
                <button class="menu-toggle" type="button" aria-label="Buka navigasi" aria-controls="sidebar" aria-expanded="false">☰</button>
                <div class="breadcrumb">Takar <span>/</span> @yield('breadcrumb', 'Dashboard')</div>
                <div class="topbar-right">
                    <span class="topbar-date">{{ now()->translatedFormat('l, d F Y') }}</span>
                    <span class="topbar-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                </div>
            </header>

            <div class="content">
                @if (session('status'))
                    <div class="alert-success" role="status">{{ session('status') }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>