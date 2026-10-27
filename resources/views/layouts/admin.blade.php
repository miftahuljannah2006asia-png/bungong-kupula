<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="robots" content="noindex, nofollow">
  <title>@yield('title', 'Admin') | Bungong Kupula</title>
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400..700&family=Plus+Jakarta+Sans:wght@400..700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-ink-950 font-sans text-ivory-100">
  <header class="border-b border-ivory-100/10 bg-ink-900">
    <div class="mx-auto flex h-16 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6">
      <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
        <x-kupula-mark class="h-8 w-8 text-gold-400" />
        <span class="font-serif text-lg font-semibold text-ivory-50">Bungong Kupula <span class="ml-1 rounded-full bg-gold-400/15 px-2.5 py-0.5 font-sans text-xs font-semibold text-gold-300">Admin</span></span>
      </a>
      <nav class="flex items-center gap-1 text-sm font-medium" aria-label="Menu admin">
        <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 {{ request()->routeIs('admin.dashboard') ? 'bg-gold-400/10 text-gold-300' : 'text-ivory-300 hover:text-gold-300' }}">Ringkasan</a>
        <a href="{{ route('admin.recipes.index') }}" class="rounded-lg px-3 py-2 {{ request()->routeIs('admin.recipes.*') ? 'bg-gold-400/10 text-gold-300' : 'text-ivory-300 hover:text-gold-300' }}">Kuliner</a>
        <a href="{{ route('admin.products.index') }}" class="rounded-lg px-3 py-2 {{ request()->routeIs('admin.products.*') ? 'bg-gold-400/10 text-gold-300' : 'text-ivory-300 hover:text-gold-300' }}">Produk</a>
        <a href="{{ route('home') }}" target="_blank" class="rounded-lg px-3 py-2 text-ivory-300 hover:text-gold-300">Lihat situs</a>
        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="rounded-lg px-3 py-2 text-ivory-300 hover:text-songket-400">Keluar</button>
        </form>
      </nav>
    </div>
  </header>

  <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    @if (session('status'))
      <div role="status" class="mb-8 rounded-xl border border-leaf-400/40 bg-leaf-400/10 px-5 py-4 text-sm text-ivory-50">{{ session('status') }}</div>
    @endif

    @yield('content')
  </main>
</body>
</html>
