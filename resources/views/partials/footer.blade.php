<footer class="mt-24 border-t border-ivory-100/10 bg-ink-900">
  <div class="motif-band" aria-hidden="true"></div>

  <div class="mx-auto grid max-w-7xl gap-12 px-4 py-16 sm:px-6 md:grid-cols-12 lg:px-8">
    <div class="md:col-span-5">
      <div class="flex items-center gap-3">
        <x-kupula-mark class="h-10 w-10 text-gold-400" />
        <span class="font-serif text-2xl font-semibold text-ivory-50">Bungong Kupula</span>
      </div>
      <p class="mt-5 max-w-md text-sm text-ivory-300">
        Platform kurasi gastronomi dan pelestarian budaya Kabupaten Pidie, Aceh. Kami menuliskan resep pusaka,
        menghubungkan pembeli dengan perajin lokal, dan merawat falsafah memuliakan tamu.
      </p>
      <p class="mt-5 font-serif text-lg italic text-gold-300">&ldquo;Harom Meuseubap, Megah Meunanggroe&rdquo;</p>
    </div>

    <div class="md:col-span-3">
      <h2 class="font-sans text-sm font-bold text-ivory-50">Jelajahi</h2>
      <ul class="mt-4 space-y-2.5 text-sm text-ivory-300">
        <li><a class="hover:text-gold-300" href="{{ route('recipes.index') }}">Pustaka rasa &amp; resep</a></li>
        <li><a class="hover:text-gold-300" href="{{ route('regions') }}">Peta wilayah Pidie</a></li>
        <li><a class="hover:text-gold-300" href="{{ route('market') }}">Pasar oleh-oleh</a></li>
        <li><a class="hover:text-gold-300" href="{{ route('stories.index') }}">Hikayat &amp; budaya</a></li>
        <li><a class="hover:text-gold-300" href="{{ route('contact') }}">Kontak &amp; kolaborasi</a></li>
      </ul>
    </div>

    <div class="md:col-span-4">
      <h2 class="font-sans text-sm font-bold text-ivory-50">Hubungi kami</h2>
      <ul class="mt-4 space-y-2.5 text-sm text-ivory-300">
        <li><a class="inline-flex items-center gap-2 hover:text-gold-300" href="https://wa.me/{{ preg_replace('/\D/', '', config('kupula.whatsapp')) }}" target="_blank" rel="noopener"><x-icon name="chat" class="h-4 w-4 text-gold-400" /> WhatsApp</a></li>
        <li><a class="inline-flex items-center gap-2 hover:text-gold-300" href="mailto:{{ config('kupula.email') }}"><x-icon name="mail" class="h-4 w-4 text-gold-400" /> {{ config('kupula.email') }}</a></li>
        @if (filled(config('kupula.instagram')))
          <li><a class="inline-flex items-center gap-2 hover:text-gold-300" href="https://instagram.com/{{ ltrim(config('kupula.instagram'), '@') }}" target="_blank" rel="noopener"><x-icon name="external" class="h-4 w-4 text-gold-400" /> Instagram</a></li>
        @endif
      </ul>
    </div>
  </div>

  <div class="border-t border-ivory-100/10">
    <div class="mx-auto flex max-w-7xl flex-col items-start justify-between gap-4 px-4 py-6 text-xs text-ivory-500 sm:flex-row sm:items-center sm:px-6 lg:px-8">
      <p>&copy; {{ now()->year }} Bungong Kupula. Memuliakan tamu, merawat warisan rasa.</p>
      <a href="#" onclick="window.scrollTo({ top: 0, behavior: 'smooth' }); return false;" class="inline-flex items-center gap-1.5 hover:text-gold-300">
        <x-icon name="arrow-up" class="h-3.5 w-3.5" /> Kembali ke atas
      </a>
    </div>
  </div>
</footer>
