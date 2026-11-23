<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk · Takar Inventory</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">
    <main class="login-layout">
        <section class="login-panel">
            <a class="brand login-brand" href="{{ route('login') }}">
                <span class="brand-mark">T</span>
                <span><strong>takar</strong><small>Inventory system</small></span>
            </a>
            <div class="login-copy">
                <span class="eyebrow">SEKALA CAFE &amp; SHISHA</span>
                <h1>Selamat datang<br>kembali.</h1>
                <p>Masuk untuk mengelola data bahan baku dan supplier dengan lebih teratur.</p>
            </div>
            <form class="login-form" method="POST" action="{{ route('login.store') }}">
                @csrf
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@sekala.id" autocomplete="username" required autofocus>
                @error('email')<span class="field-error">{{ $message }}</span>@enderror

                <div class="label-row"><label for="password">Kata sandi</label></div>
                <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>
                @error('password')<span class="field-error">{{ $message }}</span>@enderror

                <label class="remember-label"><input type="checkbox" name="remember" value="1"> <span>Ingat saya</span></label>
                <button class="button button-primary login-submit" type="submit">Masuk <span aria-hidden="true">→</span></button>
            </form>
            <p class="login-footnote">Akses khusus untuk tim Sekala Cafe &amp; Shisha.</p>
        </section>
        <aside class="login-aside">
            <div class="login-art">
                <div class="art-orbit orbit-one"></div><div class="art-orbit orbit-two"></div>
                <div class="art-card">
                    <div class="art-card-top"><span class="art-dot"></span><span>Ringkasan inventori</span><span class="art-menu">•••</span></div>
                    <div class="art-stat">Bahan baku <strong>terkelola</strong></div>
                    <div class="art-bars"><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i></div>
                    <div class="art-legend"><span><i></i>Data tersusun</span><span><i></i>Lebih terpantau</span></div>
                </div>
                <div class="art-chip chip-one"><span>✓</span> Data bahan baku</div>
                <div class="art-chip chip-two"><span>↗</span> Supplier terdata</div>
            </div>
            <div class="aside-caption"><span>01</span> INVENTORY MANAGEMENT <i></i> SEKALA</div>
        </aside>
    </main>
</body>
</html>
