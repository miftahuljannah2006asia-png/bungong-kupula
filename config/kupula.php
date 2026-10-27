<?php

return [

    /*
    | Identitas situs. Ubah lewat file .env agar tidak perlu menyunting kode.
    */
    'name' => 'Bungong Kupula',
    'tagline' => 'Gastronomi & Budaya Pidie',

    // Nomor WhatsApp admin (format internasional tanpa +, contoh 6281234567890)
    'whatsapp' => env('KUPULA_WHATSAPP', '6281200000000'),
    'email' => env('KUPULA_EMAIL', 'halo@bungongkupula.id'),
    'instagram' => env('KUPULA_INSTAGRAM'),

];
