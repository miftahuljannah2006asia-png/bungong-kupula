@extends('layouts.app')

@section('title', 'Kontak & Kolaborasi | Bungong Kupula')
@section('description', 'Kirim resep keluarga, kisah kampung, atau ajak kerja sama sebagai perajin dan UMKM Pidie.')

@php
    $topics = \App\Http\Controllers\MessageController::TOPICS;
@endphp

@section('content')
<section class="mx-auto max-w-7xl px-4 pb-8 pt-14 sm:px-6 lg:px-8 lg:pt-20">
  <h1 class="max-w-3xl text-5xl font-semibold leading-tight sm:text-6xl">Kontak dan kolaborasi</h1>
  <p class="mt-5 max-w-2xl text-lg text-ivory-300">Kirim resep keluarga, kisah kampung, atau ajak kami bekerja sama. Setiap pesan dibaca oleh tim kurator.</p>

  <div class="mt-14 grid gap-12 lg:grid-cols-12">
    <div class="space-y-6 lg:col-span-4">
      <a href="https://wa.me/{{ preg_replace('/\D/', '', config('kupula.whatsapp')) }}" target="_blank" rel="noopener"
         class="flex items-center gap-4 rounded-2xl border border-ivory-100/10 bg-ink-900 p-5 transition-colors hover:border-gold-400/40">
        <span class="grid h-11 w-11 place-items-center rounded-full bg-gold-400/10 text-gold-400"><x-icon name="chat" /></span>
        <span><span class="block font-semibold text-ivory-50">WhatsApp</span><span class="text-sm text-ivory-500">Respons tercepat untuk pesanan</span></span>
      </a>
      <a href="mailto:{{ config('kupula.email') }}" class="flex items-center gap-4 rounded-2xl border border-ivory-100/10 bg-ink-900 p-5 transition-colors hover:border-gold-400/40">
        <span class="grid h-11 w-11 place-items-center rounded-full bg-gold-400/10 text-gold-400"><x-icon name="mail" /></span>
        <span><span class="block font-semibold text-ivory-50">Email</span><span class="text-sm text-ivory-500">{{ config('kupula.email') }}</span></span>
      </a>
    </div>

    <div class="lg:col-span-8" x-data="contactForm(@js($topics))">
      <div x-show="done" x-cloak class="rounded-3xl border border-gold-400/30 bg-ink-900 p-10 text-center">
        <div class="mx-auto grid h-14 w-14 place-items-center rounded-full bg-leaf-400/15 text-leaf-400"><x-icon name="check" class="h-7 w-7" /></div>
        <h2 class="mt-5 text-2xl font-semibold">Pesan Anda sudah terkirim</h2>
        <p class="mt-2 text-ivory-300">Terima kasih. Kami akan menghubungi Anda lewat kontak yang Anda tinggalkan.</p>
        <button type="button" class="btn btn-line mt-6" @click="again()">Kirim pesan lain</button>
      </div>

      <form x-show="!done" @submit.prevent="submit()" novalidate class="space-y-5 rounded-3xl border border-ivory-100/10 bg-ink-900 p-6 sm:p-8">
        <p x-show="error" x-text="error" role="alert" class="rounded-xl border border-songket-400/40 bg-songket-500/10 px-4 py-3 text-sm text-ivory-50"></p>

        <div class="grid gap-5 sm:grid-cols-2">
          <div>
            <label for="c-name" class="mb-1.5 block text-sm font-semibold">Nama</label>
            <input id="c-name" type="text" autocomplete="name" class="field" x-model="form.name" :aria-invalid="!!errors.name">
            <p class="mt-1 text-xs text-songket-400" x-show="errors.name" x-text="errors.name?.[0]"></p>
          </div>
          <div>
            <label for="c-contact" class="mb-1.5 block text-sm font-semibold">WhatsApp atau email</label>
            <input id="c-contact" type="text" class="field" x-model="form.contact" :aria-invalid="!!errors.contact">
            <p class="mt-1 text-xs text-songket-400" x-show="errors.contact" x-text="errors.contact?.[0]"></p>
          </div>
        </div>

        <div>
          <label for="c-topic" class="mb-1.5 block text-sm font-semibold">Topik</label>
          <select id="c-topic" class="field" x-model="form.topic">
            @foreach ($topics as $topic)
              <option value="{{ $topic }}">{{ $topic }}</option>
            @endforeach
          </select>
        </div>

        <div>
          <label for="c-body" class="mb-1.5 block text-sm font-semibold">Pesan</label>
          <textarea id="c-body" rows="6" class="field" x-model="form.body" :aria-invalid="!!errors.body" placeholder="Ceritakan resep, kisah, atau rencana kerja sama Anda"></textarea>
          <p class="mt-1 text-xs text-songket-400" x-show="errors.body" x-text="errors.body?.[0]"></p>
        </div>

        <input type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="absolute -left-[9999px] h-0 w-0 opacity-0" x-model="form.website">

        <button type="submit" class="btn btn-gold" :disabled="sending">
          <x-icon name="send" class="h-4 w-4" />
          <span x-text="sending ? 'Mengirim...' : 'Kirim pesan'"></span>
        </button>
      </form>
    </div>
  </div>
</section>
@endsection
