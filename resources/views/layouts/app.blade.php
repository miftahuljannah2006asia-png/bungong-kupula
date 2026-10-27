<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#110d09">

  <title>@yield('title', 'Bungong Kupula - Gastronomi &amp; Budaya Pidie, Aceh')</title>
  <meta name="description" content="@yield('description', 'Platform pelestarian kuliner pusaka dan budaya Kabupaten Pidie, Aceh: resep Kuah Beulangong, emping Beureunuen, kopi Tangse, dan adat peumulia jamee.')">
  <link rel="canonical" href="{{ url()->current() }}">

  <meta property="og:site_name" content="Bungong Kupula">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="id_ID">
  <meta property="og:title" content="@yield('title', 'Bungong Kupula - Gastronomi &amp; Budaya Pidie, Aceh')">
  <meta property="og:description" content="@yield('description', 'Resep pusaka, wilayah, oleh-oleh perajin, dan hikayat budaya Kabupaten Pidie, Aceh.')">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="@yield('og_image', asset('images/hero-kupula.jpg'))">
  <meta name="twitter:card" content="summary_large_image">

  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400..700;1,9..144,400..600&family=Plus+Jakarta+Sans:wght@400..700&display=swap" rel="stylesheet">

  <script>
    window.KUPULA = @js([
        'orderUrl' => route('orders.store'),
        'messageUrl' => route('messages.store'),
        'recipes' => $recipeIndex,
    ]);
  </script>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('head')
</head>
<body x-data
      x-effect="document.body.classList.toggle('overflow-hidden', $store.kupula.locked)"
      @keydown.escape.window="$store.kupula.closeAll()">

  <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-lg focus:bg-gold-400 focus:px-4 focus:py-2 focus:font-semibold focus:text-ink-950">Lompat ke konten</a>

  @include('partials.header')

  <main id="konten">
    @yield('content')
  </main>

  @include('partials.footer')
  @include('partials.drawers')

  @stack('scripts')
</body>
</html>
