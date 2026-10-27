{{-- ============== Keranjang ============== --}}
<div x-data x-show="$store.kupula.cartOpen" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="cart-title">
  <div class="absolute inset-0 bg-black/70" x-show="$store.kupula.cartOpen" x-transition.opacity @click="$store.kupula.cartOpen = false"></div>

  <aside x-show="$store.kupula.cartOpen"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="absolute inset-y-0 right-0 flex w-full max-w-md flex-col border-l border-ivory-100/10 bg-ink-900 shadow-2xl">

    <div class="flex items-center justify-between border-b border-ivory-100/10 px-6 py-5">
      <h2 id="cart-title" class="text-xl font-semibold">Keranjang oleh-oleh</h2>
      <button type="button" class="grid h-9 w-9 place-items-center rounded-full hover:bg-ivory-100/10" @click="$store.kupula.cartOpen = false" aria-label="Tutup keranjang">
        <x-icon name="x" />
      </button>
    </div>

    <div class="flex-1 overflow-y-auto px-6">
      <div x-show="$store.kupula.cart.length === 0" class="py-16 text-center">
        <x-kupula-mark class="mx-auto h-14 w-14 text-ink-600" />
        <p class="mt-5 font-serif text-lg text-ivory-50">Keranjang masih kosong</p>
        <p class="mt-1 text-sm text-ivory-500">Pilih emping, kopi, atau kue keukarah langsung dari perajin Pidie.</p>
        <a href="{{ route('market') }}" class="btn btn-gold mt-6" @click="$store.kupula.cartOpen = false">Lihat pasar oleh-oleh</a>
      </div>

      <ul class="divide-y divide-ivory-100/10">
        <template x-for="item in $store.kupula.cart" :key="item.id">
          <li class="flex gap-4 py-5">
            <img :src="item.image" alt="" class="h-20 w-20 shrink-0 rounded-xl object-cover">
            <div class="min-w-0 flex-1">
              <p class="font-semibold leading-snug text-ivory-50" x-text="item.title"></p>
              <p class="mt-0.5 text-xs text-ivory-500" x-text="item.unit"></p>
              <div class="mt-3 flex items-center justify-between gap-3">
                <div class="inline-flex items-center rounded-full border border-ivory-100/15">
                  <button type="button" class="grid h-8 w-8 place-items-center hover:text-gold-300" @click="$store.kupula.changeQty(item.id, -1)" aria-label="Kurangi jumlah"><x-icon name="minus" class="h-3.5 w-3.5" /></button>
                  <span class="w-7 text-center text-sm font-semibold" x-text="item.qty"></span>
                  <button type="button" class="grid h-8 w-8 place-items-center hover:text-gold-300" @click="$store.kupula.changeQty(item.id, 1)" aria-label="Tambah jumlah"><x-icon name="plus" class="h-3.5 w-3.5" /></button>
                </div>
                <p class="font-semibold text-gold-300" x-text="$store.kupula.money(item.price * item.qty)"></p>
              </div>
            </div>
            <button type="button" class="self-start p-1 text-ivory-500 hover:text-songket-400" @click="$store.kupula.remove(item.id)" aria-label="Hapus dari keranjang"><x-icon name="trash" class="h-4 w-4" /></button>
          </li>
        </template>
      </ul>
    </div>

    <div class="border-t border-ivory-100/10 bg-ink-950/60 px-6 py-5" x-show="$store.kupula.cart.length > 0">
      <div class="flex items-baseline justify-between">
        <span class="text-sm text-ivory-300">Subtotal</span>
        <span class="font-serif text-2xl font-semibold text-gold-300" x-text="$store.kupula.money($store.kupula.cartTotal)"></span>
      </div>
      <p class="mt-1 text-xs text-ivory-500">Ongkos kirim dihitung admin dan dikonfirmasi lewat WhatsApp.</p>
      <button type="button" class="btn btn-gold mt-4 w-full" @click="$store.kupula.openCheckout()">Lanjut ke pemesanan</button>
    </div>
  </aside>
</div>

{{-- ============== Resep tersimpan ============== --}}
<div x-data x-show="$store.kupula.savedOpen" x-cloak class="fixed inset-0 z-50" role="dialog" aria-modal="true" aria-labelledby="saved-title">
  <div class="absolute inset-0 bg-black/70" x-show="$store.kupula.savedOpen" x-transition.opacity @click="$store.kupula.savedOpen = false"></div>

  <aside x-show="$store.kupula.savedOpen"
         x-transition:enter="transition duration-300 ease-out" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
         class="absolute inset-y-0 right-0 flex w-full max-w-md flex-col border-l border-ivory-100/10 bg-ink-900 shadow-2xl">

    <div class="flex items-center justify-between border-b border-ivory-100/10 px-6 py-5">
      <h2 id="saved-title" class="text-xl font-semibold">Resep tersimpan</h2>
      <button type="button" class="grid h-9 w-9 place-items-center rounded-full hover:bg-ivory-100/10" @click="$store.kupula.savedOpen = false" aria-label="Tutup resep tersimpan">
        <x-icon name="x" />
      </button>
    </div>

    <div class="flex-1 overflow-y-auto px-6">
      <div x-show="$store.kupula.savedRecipes.length === 0" class="py-16 text-center">
        <x-icon name="heart" class="mx-auto h-12 w-12 text-ink-600" />
        <p class="mt-5 font-serif text-lg text-ivory-50">Belum ada resep tersimpan</p>
        <p class="mt-1 text-sm text-ivory-500">Ketuk ikon hati pada resep yang ingin Anda masak nanti.</p>
        <a href="{{ route('recipes.index') }}" class="btn btn-gold mt-6" @click="$store.kupula.savedOpen = false">Jelajahi pustaka rasa</a>
      </div>

      <ul class="divide-y divide-ivory-100/10">
        <template x-for="r in $store.kupula.savedRecipes" :key="r.slug">
          <li class="flex items-center gap-4 py-4">
            <img :src="r.image" alt="" class="h-16 w-16 shrink-0 rounded-xl object-cover">
            <a :href="r.url" class="min-w-0 flex-1">
              <span class="block text-xs text-gold-400" x-text="r.category"></span>
              <span class="block font-serif text-base font-semibold leading-snug text-ivory-50 hover:text-gold-300" x-text="r.name"></span>
            </a>
            <button type="button" class="p-1 text-ivory-500 hover:text-songket-400" @click="$store.kupula.toggleSave(r.slug)" aria-label="Hapus dari simpanan"><x-icon name="trash" class="h-4 w-4" /></button>
          </li>
        </template>
      </ul>
    </div>
  </aside>
</div>

{{-- ============== Formulir pemesanan ============== --}}
<div x-data x-show="$store.kupula.checkoutOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="checkout-title">
  <div class="fixed inset-0 bg-black/80" x-show="$store.kupula.checkoutOpen" x-transition.opacity></div>

  <div class="relative flex min-h-full items-center justify-center p-4" @click.self="$store.kupula.closeCheckout()">
    <div x-show="$store.kupula.checkoutOpen" x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0"
         class="relative w-full max-w-xl overflow-hidden rounded-3xl border border-ivory-100/10 bg-ink-900 shadow-2xl">

      <div class="flex items-center justify-between border-b border-ivory-100/10 px-6 py-5">
        <h2 id="checkout-title" class="text-xl font-semibold" x-text="$store.kupula.order ? 'Pesanan tercatat' : 'Data pemesanan'"></h2>
        <button type="button" class="grid h-9 w-9 place-items-center rounded-full hover:bg-ivory-100/10" @click="$store.kupula.closeCheckout()" aria-label="Tutup formulir"><x-icon name="x" /></button>
      </div>

      {{-- Berhasil --}}
      <div x-show="$store.kupula.order" class="px-6 py-10 text-center">
        <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-leaf-400/15 text-leaf-400"><x-icon name="check" class="h-7 w-7" /></div>
        <p class="mt-5 font-serif text-2xl font-semibold text-ivory-50">Terima kasih, pesanan Anda sudah kami terima</p>
        <p class="mt-3 text-sm text-ivory-300">Kode pesanan</p>
        <p class="font-serif text-3xl font-semibold tracking-wide text-gold-300" x-text="$store.kupula.order?.code"></p>
        <p class="mt-1 text-sm text-ivory-300">Total barang <span class="font-semibold text-ivory-50" x-text="$store.kupula.money($store.kupula.order?.total)"></span> (belum ongkir)</p>
        <p class="mx-auto mt-5 max-w-sm text-sm text-ivory-300">Langkah berikutnya: kirim kode ini lewat WhatsApp agar admin menghitung ongkos kirim dan memberi tahu cara pembayaran.</p>
        <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
          <a x-show="$store.kupula.order?.whatsapp_url" :href="$store.kupula.order?.whatsapp_url" target="_blank" rel="noopener" class="btn btn-gold"><x-icon name="chat" class="h-4 w-4" /> Konfirmasi lewat WhatsApp</a>
          <button type="button" class="btn btn-line" @click="$store.kupula.closeCheckout()">Selesai</button>
        </div>
      </div>

      {{-- Formulir --}}
      <form x-show="!$store.kupula.order" @submit.prevent="$store.kupula.submitOrder()" novalidate class="space-y-5 px-6 py-6">
        <div class="rounded-2xl border border-ivory-100/10 bg-ink-950/60 p-4">
          <ul class="divide-y divide-ivory-100/10 text-sm">
            <template x-for="item in $store.kupula.cart" :key="item.id">
              <li class="flex justify-between gap-4 py-2">
                <span class="text-ivory-300"><span x-text="item.qty"></span>&times; <span x-text="item.title"></span></span>
                <span class="shrink-0 font-semibold text-ivory-50" x-text="$store.kupula.money(item.price * item.qty)"></span>
              </li>
            </template>
          </ul>
          <div class="mt-2 flex items-baseline justify-between border-t border-ivory-100/10 pt-3">
            <span class="text-sm text-ivory-300">Total barang</span>
            <span class="font-serif text-xl font-semibold text-gold-300" x-text="$store.kupula.money($store.kupula.cartTotal)"></span>
          </div>
        </div>

        <p x-show="$store.kupula.formError" x-text="$store.kupula.formError" role="alert" class="rounded-xl border border-songket-400/40 bg-songket-500/10 px-4 py-3 text-sm text-ivory-50"></p>

        <div class="grid gap-4 sm:grid-cols-2">
          <div>
            <label for="o-name" class="mb-1.5 block text-sm font-semibold text-ivory-100">Nama penerima</label>
            <input id="o-name" type="text" autocomplete="name" class="field" placeholder="Contoh: Cut Sarah" x-model="$store.kupula.form.name" :aria-invalid="!!$store.kupula.errors.name">
            <p class="mt-1 text-xs text-songket-400" x-show="$store.kupula.errors.name" x-text="$store.kupula.errors.name?.[0]"></p>
          </div>
          <div>
            <label for="o-phone" class="mb-1.5 block text-sm font-semibold text-ivory-100">Nomor WhatsApp</label>
            <input id="o-phone" type="tel" inputmode="tel" autocomplete="tel" class="field" placeholder="0812 3456 7890" x-model="$store.kupula.form.phone" :aria-invalid="!!$store.kupula.errors.phone">
            <p class="mt-1 text-xs text-songket-400" x-show="$store.kupula.errors.phone" x-text="$store.kupula.errors.phone?.[0]"></p>
          </div>
        </div>

        <div>
          <label for="o-city" class="mb-1.5 block text-sm font-semibold text-ivory-100">Kota / kabupaten tujuan</label>
          <input id="o-city" type="text" autocomplete="address-level2" class="field" placeholder="Contoh: Banda Aceh, Medan, Jakarta Selatan" x-model="$store.kupula.form.city" :aria-invalid="!!$store.kupula.errors.city">
          <p class="mt-1 text-xs text-songket-400" x-show="$store.kupula.errors.city" x-text="$store.kupula.errors.city?.[0]"></p>
        </div>

        <div>
          <label for="o-address" class="mb-1.5 block text-sm font-semibold text-ivory-100">Alamat lengkap</label>
          <textarea id="o-address" rows="3" autocomplete="street-address" class="field" placeholder="Jalan, nomor rumah, gampong/kelurahan, kecamatan, kode pos" x-model="$store.kupula.form.address" :aria-invalid="!!$store.kupula.errors.address"></textarea>
          <p class="mt-1 text-xs text-songket-400" x-show="$store.kupula.errors.address" x-text="$store.kupula.errors.address?.[0]"></p>
        </div>

        <div>
          <label for="o-notes" class="mb-1.5 block text-sm font-semibold text-ivory-100">Catatan <span class="font-normal text-ivory-500">(opsional)</span></label>
          <input id="o-notes" type="text" class="field" placeholder="Contoh: kemasan hadiah, kirim sebelum Jumat" x-model="$store.kupula.form.notes">
        </div>

        {{-- Jebakan bot: manusia tidak melihat kolom ini. --}}
        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute -left-[9999px] h-0 w-0 opacity-0" x-model="$store.kupula.form.website">

        <button type="submit" class="btn btn-gold w-full" :disabled="$store.kupula.sending">
          <x-icon name="send" class="h-4 w-4" />
          <span x-text="$store.kupula.sending ? 'Mengirim pesanan...' : 'Kirim pesanan'"></span>
        </button>
        <p class="text-center text-xs text-ivory-500">Belum ada pembayaran di langkah ini. Admin akan menghubungi Anda lewat WhatsApp.</p>
      </form>
    </div>
  </div>
</div>

{{-- ============== Notifikasi singkat ============== --}}
<div x-data class="pointer-events-none fixed inset-x-0 bottom-6 z-[70] flex justify-center px-4" aria-live="polite">
  <div x-show="$store.kupula.toast" x-cloak
       x-transition:enter="transition duration-200 ease-out" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition duration-200 ease-in" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
       class="rounded-full border border-gold-400/40 bg-ink-800 px-5 py-2.5 text-sm font-medium text-ivory-50 shadow-xl"
       x-text="$store.kupula.toast"></div>
</div>
