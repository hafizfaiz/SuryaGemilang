<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Tables\Table; // ✅ Pastikan Anda mengimpor class Table Filament

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Mengubah placeholder search default untuk semua tabel Filament
        Table::configureUsing(function (Table $table): void {
            $table->searchPlaceholder('Cari..');
        });

    }
}