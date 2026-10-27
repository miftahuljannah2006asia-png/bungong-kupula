<?php

return [

    /*
    | Akun admin tunggal. Isi di .env:
    |   ADMIN_EMAIL=anda@contoh.com
    |   ADMIN_PASSWORD_HASH='...'   (buat dengan: php artisan admin:hash)
    */
    'email' => env('ADMIN_EMAIL'),
    'password_hash' => env('ADMIN_PASSWORD_HASH'),

];
