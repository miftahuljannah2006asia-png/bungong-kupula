{{-- Bunga kupula (bunga tanjung): delapan kelopak bintang. Warna mengikuti text-* pada elemen. --}}
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" aria-hidden="true" {{ $attributes->merge(['class' => 'h-8 w-8']) }}>
  <g fill="currentColor">
    @for ($i = 0; $i < 8; $i++)
      <path transform="rotate({{ $i * 45 }} 24 24)" d="M24 2c3.6 7.4 3.6 13.6 0 19-3.6-5.4-3.6-11.6 0-19z"/>
    @endfor
  </g>
  <circle cx="24" cy="24" r="3.4" fill="#fbf5e6"/>
</svg>
