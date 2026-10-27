<?php

namespace App\Console\Commands;

use App\Services\RecipeImporter;
use Illuminate\Console\Command;

class RecipesImport extends Command
{
    protected $signature = 'recipes:import';

    protected $description = 'Menyalin resep bawaan ke database agar bisa diubah lewat admin';

    public function handle(RecipeImporter $importer): int
    {
        $this->info($importer->import().' resep bawaan diimpor.');

        return self::SUCCESS;
    }
}
