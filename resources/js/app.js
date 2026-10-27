import Alpine from 'alpinejs';

window.Alpine = Alpine;

/* ------------------------------------------------------------------ */
/*  Utilitas kecil                                                      */
/* ------------------------------------------------------------------ */
const boot = window.KUPULA ?? {};

const read = (key, fallback) => {
  try {
    const value = JSON.parse(localStorage.getItem(key));
    return value ?? fallback;
  } catch {
    return fallback;
  }
};

const write = (key, value) => {
  try {
    localStorage.setItem(key, JSON.stringify(value));
  } catch {
    /* mode privat / penyimpanan penuh: abaikan */
  }
};

const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

const rupiah = (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID');

async function postJson(url, payload) {
  const res = await fetch(url, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'X-CSRF-TOKEN': csrf(),
      'X-Requested-With': 'XMLHttpRequest',
    },
    body: JSON.stringify(payload),
  });

  const data = await res.json().catch(() => ({}));

  return { status: res.status, ok: res.ok, data };
}

const emptyOrderForm = () => ({ name: '', phone: '', city: '', address: '', notes: '', website: '' });

/* ------------------------------------------------------------------ */
/*  Store global: keranjang, resep tersimpan, laci & notifikasi         */
/* ------------------------------------------------------------------ */
document.addEventListener('alpine:init', () => {
  Alpine.store('kupula', {
    recipes: boot.recipes ?? {},
    saved: read('bk_saved', []).filter((slug) => slug in (boot.recipes ?? {})),
    cart: read('bk_cart', []),

    cartOpen: false,
    savedOpen: false,
    checkoutOpen: false,

    toast: '',
    toastTimer: null,

    form: emptyOrderForm(),
    errors: {},
    formError: '',
    sending: false,
    order: null,

    money: rupiah,

    /* ----- status tampilan ----- */
    get locked() {
      return this.cartOpen || this.savedOpen || this.checkoutOpen;
    },

    closeAll() {
      this.cartOpen = false;
      this.savedOpen = false;
      this.closeCheckout();
    },

    notify(message) {
      this.toast = message;
      clearTimeout(this.toastTimer);
      this.toastTimer = setTimeout(() => (this.toast = ''), 2600);
    },

    /* ----- resep tersimpan ----- */
    isSaved(slug) {
      return this.saved.includes(slug);
    },

    toggleSave(slug) {
      if (this.isSaved(slug)) {
        this.saved = this.saved.filter((s) => s !== slug);
        this.notify('Resep dihapus dari daftar simpanan');
      } else {
        this.saved.push(slug);
        this.notify('Resep disimpan');
      }
      write('bk_saved', this.saved);
    },

    get savedRecipes() {
      return this.saved.map((slug) => this.recipes[slug]).filter(Boolean);
    },

    /* ----- keranjang ----- */
    get cartCount() {
      return this.cart.reduce((sum, item) => sum + item.qty, 0);
    },

    get cartTotal() {
      return this.cart.reduce((sum, item) => sum + item.price * item.qty, 0);
    },

    inCart(id) {
      return this.cart.some((item) => item.id === id);
    },

    add(product) {
      const found = this.cart.find((item) => item.id === product.id);

      if (found) {
        found.qty = Math.min(50, found.qty + 1);
      } else {
        this.cart.push({
          id: product.id,
          title: product.title,
          price: product.price,
          unit: product.unit,
          artisan: product.artisan,
          image: product.image,
          qty: 1,
        });
      }

      this.persistCart();
      this.notify('Masuk keranjang: ' + product.title);
    },

    changeQty(id, delta) {
      const item = this.cart.find((i) => i.id === id);
      if (!item) return;

      item.qty += delta;
      if (item.qty <= 0) {
        this.remove(id);
        return;
      }
      item.qty = Math.min(50, item.qty);
      this.persistCart();
    },

    remove(id) {
      this.cart = this.cart.filter((item) => item.id !== id);
      this.persistCart();
    },

    persistCart() {
      write('bk_cart', this.cart);
    },

    /* ----- pemesanan ----- */
    openCheckout() {
      if (this.cart.length === 0) return;
      this.cartOpen = false;
      this.errors = {};
      this.formError = '';
      this.checkoutOpen = true;
    },

    closeCheckout() {
      this.checkoutOpen = false;
      if (this.order) {
        this.order = null;
        this.form = emptyOrderForm();
      }
    },

    async submitOrder() {
      if (this.sending || this.cart.length === 0) return;

      this.sending = true;
      this.errors = {};
      this.formError = '';

      try {
        const { status, ok, data } = await postJson(boot.orderUrl, {
          ...this.form,
          items: this.cart.map((item) => ({ id: item.id, qty: item.qty })),
        });

        if (status === 422) {
          this.errors = data.errors ?? {};
          this.formError = this.errors.items?.[0] ?? 'Periksa kembali isian yang ditandai.';
          return;
        }

        if (!ok) {
          throw new Error(data.message ?? 'Gagal mengirim pesanan');
        }

        this.order = data;
        this.cart = [];
        this.persistCart();
      } catch {
        this.formError = 'Pesanan belum terkirim. Periksa koneksi internet, lalu coba lagi.';
      } finally {
        this.sending = false;
      }
    },
  });

  /* ---------------------------------------------------------------- */
  /*  Pencarian & filter Pustaka Rasa                                  */
  /* ---------------------------------------------------------------- */
  Alpine.data('recipeFilter', (items) => ({
    items,
    q: '',
    cat: 'all',
    sort: 'default',

    get visible() {
      const q = this.q.trim().toLowerCase();

      let list = this.items.filter(
        (i) => (this.cat === 'all' || i.category === this.cat) && (!q || i.haystack.includes(q)),
      );

      if (this.sort === 'spicy') list = [...list].sort((a, b) => b.spicy - a.spicy);
      if (this.sort === 'savory') list = [...list].sort((a, b) => b.savory - a.savory);

      return list.map((i) => i.slug);
    },

    show(slug) {
      return this.visible.includes(slug);
    },

    order(slug) {
      return this.visible.indexOf(slug);
    },

    reset() {
      this.q = '';
      this.cat = 'all';
      this.sort = 'default';
    },
  }));

  /* ---------------------------------------------------------------- */
  /*  Formulir kontak                                                  */
  /* ---------------------------------------------------------------- */
  Alpine.data('contactForm', (topics) => ({
    form: { name: '', contact: '', topic: topics[0], body: '', website: '' },
    errors: {},
    error: '',
    sending: false,
    done: false,

    async submit() {
      if (this.sending) return;

      this.sending = true;
      this.errors = {};
      this.error = '';

      try {
        const { status, ok, data } = await postJson(boot.messageUrl, this.form);

        if (status === 422) {
          this.errors = data.errors ?? {};
          this.error = 'Periksa kembali isian yang ditandai.';
          return;
        }

        if (!ok) throw new Error();

        this.done = true;
      } catch {
        this.error = 'Pesan belum terkirim. Periksa koneksi internet, lalu coba lagi.';
      } finally {
        this.sending = false;
      }
    },

    again() {
      this.form = { name: '', contact: '', topic: topics[0], body: '', website: '' };
      this.done = false;
    },
  }));
});

Alpine.start();
