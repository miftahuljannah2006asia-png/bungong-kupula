<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title>Masuk Admin | Bungong Kupula</title>
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400..700&family=Plus+Jakarta+Sans:wght@400..700&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css'])
</head>
<body class="grid min-h-screen place-items-center bg-ink-950 px-4 font-sans text-ivory-100">
  <div class="hero-glow pointer-events-none fixed inset-0" aria-hidden="true"></div>

  <main class="relative w-full max-w-md rounded-3xl border border-ivory-100/10 bg-ink-900 p-8 shadow-2xl sm:p-10">
    <x-kupula-mark class="mx-auto h-12 w-12 text-gold-400" />
    <h1 class="mt-5 text-center text-3xl font-semibold">Masuk admin</h1>
    <p class="mt-2 text-center text-sm text-ivory-500">Kelola kuliner dan konten Bungong Kupula.</p>

    @unless ($configured)
      <p class="mt-6 rounded-xl border border-gold-400/40 bg-gold-400/10 px-4 py-3 text-sm text-ivory-50">
        Akun admin belum diatur. Jalankan <code class="font-semibold text-gold-300">php artisan admin:hash</code>, lalu salin hasilnya ke file <code class="font-semibold text-gold-300">.env</code>.
      </p>
    @endunless

    <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-8 space-y-5">
      @csrf
      <div>
        <label for="email" class="mb-1.5 block text-sm font-semibold">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus class="field" @error('email') aria-invalid="true" @enderror>
        @error('email') <p class="mt-1 text-xs text-songket-400">{{ $message }}</p> @enderror
      </div>
      <div>
        <label for="password" class="mb-1.5 block text-sm font-semibold">Kata sandi</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required class="field" @error('password') aria-invalid="true" @enderror>
        @error('password') <p class="mt-1 text-xs text-songket-400">{{ $message }}</p> @enderror
      </div>
      <button type="submit" class="btn btn-gold w-full">Masuk</button>
    </form>

    <a href="{{ route('home') }}" class="mt-6 block text-center text-sm text-ivory-500 hover:text-gold-300">Kembali ke situs</a>
  </main>
</body>
</html>
