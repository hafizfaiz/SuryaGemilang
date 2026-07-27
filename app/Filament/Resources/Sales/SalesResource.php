<?php

namespace App\Filament\Resources\Sales;

use App\Filament\Resources\Sales\Pages\CreateSales;
use App\Filament\Resources\Sales\Pages\EditSales;
use App\Filament\Resources\Sales\Pages\ListSales;
use App\Filament\Resources\Sales\Pages\ViewSales;
use App\Filament\Resources\Sales\Schemas\SalesForm;
use App\Filament\Resources\Sales\Schemas\SalesInfolist;
use App\Filament\Resources\Sales\Tables\SalesTable;
use App\Models\Sales;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;

    protected static ?string $navigationLabel = 'Penjualan';
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi dan Pengembalian';
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralModelLabel = 'Penjualan';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->hasRole(['admin', 'manajer', 'kasir']);
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $recordTitleAttribute = 'invoice_number';

    public static function form(Schema $schema): Schema
    {
        return SalesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SalesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSales::route('/'),
            'create' => CreateSales::route('/create'),
            'view' => ViewSales::route('/{record}'),
            'edit' => EditSales::route('/{record}/edit'),
        ];
    }
}