@props(['recipe'])
<article class="group relative flex h-full flex-col overflow-hidden rounded-2xl border border-ivory-100/10 bg-ink-900 transition-colors hover:border-gold-400/40">
  <div class="relative aspect-[4/3] overflow-hidden">
    <img src="{{ $recipe['image_url'] }}" alt="" loading="lazy" width="1200" height="896"
         class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
         style="object-position: {{ $recipe['image_position'] }}">
    <div class="absolute inset-0 bg-gradient-to-t from-ink-900 via-transparent to-transparent"></div>
    <span class="absolute left-3 top-3 rounded-full bg-ink-950/80 px-3 py-1 text-xs font-semibold text-gold-300 backdrop-blur">{{ $recipe['category'] }}</span>
    <button type="button"
            class="group/save absolute right-3 top-3 z-10 grid h-9 w-9 place-items-center rounded-full bg-ink-950/80 text-ivory-100 backdrop-blur transition-colors hover:text-songket-400"
            @click="$store.kupula.toggleSave('{{ $recipe['slug'] }}')"
            :aria-pressed="$store.kupula.isSaved('{{ $recipe['slug'] }}')"
            aria-label="Simpan resep {{ $recipe['name'] }}">
      <x-icon name="heart" class="h-[1.1rem] w-[1.1rem] group-aria-pressed/save:fill-songket-400 group-aria-pressed/save:text-songket-400" />
    </button>
  </div>

  <div class="flex flex-1 flex-col p-5">
    <p class="flex items-center gap-1.5 text-xs text-ivory-500">
      <x-icon name="pin" class="h-3.5 w-3.5 shrink-0" /> {{ $recipe['region'] }}
    </p>
    <h3 class="mt-2 text-xl font-semibold leading-snug">
      <a href="{{ route('recipes.show', $recipe['slug']) }}" class="after:absolute after:inset-0 after:content-[''] group-hover:text-gold-300">{{ $recipe['name'] }}</a>
    </h3>
    <p class="mt-2 line-clamp-2 text-sm text-ivory-300">{{ $recipe['short_desc'] }}</p>

    <div class="mt-auto pt-5">
      <div class="flex items-center gap-4 text-xs text-ivory-300">
        <span class="inline-flex items-center gap-1.5"><x-icon name="clock" class="h-3.5 w-3.5 text-gold-400" /> {{ $recipe['cook_time'] }}</span>
        <span class="inline-flex items-center gap-1.5"><x-icon name="chef" class="h-3.5 w-3.5 text-gold-400" /> {{ $recipe['difficulty'] }}</span>
      </div>
      <div class="mt-4 grid grid-cols-2 gap-3 text-[0.7rem] text-ivory-500">
        <div>
          <div class="flex justify-between"><span>Pedas</span><span>{{ $recipe['flavor_profile']['spicy'] }}%</span></div>
          <div class="mt-1 h-1 rounded-full bg-ink-700"><div class="h-full rounded-full bg-songket-400" style="width: {{ $recipe['flavor_profile']['spicy'] }}%"></div></div>
        </div>
        <div>
          <div class="flex justify-between"><span>Aroma rempah</span><span>{{ $recipe['flavor_profile']['aromatic'] }}%</span></div>
          <div class="mt-1 h-1 rounded-full bg-ink-700"><div class="h-full rounded-full bg-leaf-400" style="width: {{ $recipe['flavor_profile']['aromatic'] }}%"></div></div>
        </div>
      </div>
    </div>
  </div>
</article>
