<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdminHash extends Command
{
    protected $signature = 'admin:hash';

    protected $description = 'Membuat hash kata sandi admin untuk disalin ke file .env';

    public function handle(): int
    {
        $password = (string) $this->secret('Kata sandi admin (min. 8 karakter)');

        if (mb_strlen($password) < 8) {
            $this->error('Kata sandi terlalu pendek.');

            return self::FAILURE;
        }

        if ($password !== (string) $this->secret('Ulangi kata sandi')) {
            $this->error('Kedua kata sandi tidak sama.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Salin dua baris ini ke file .env (tanda kutip tunggal jangan dihapus):');
        $this->newLine();
        $this->line('ADMIN_EMAIL=email-anda@contoh.com');
        $this->line("ADMIN_PASSWORD_HASH='".password_hash($password, PASSWORD_BCRYPT)."'");
        $this->newLine();

        return self::SUCCESS;
    }
}
