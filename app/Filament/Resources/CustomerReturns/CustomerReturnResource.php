<?php

namespace App\Filament\Resources\CustomerReturns;

use App\Filament\Resources\CustomerReturns\Pages\CreateCustomerReturn;
use App\Filament\Resources\CustomerReturns\Pages\EditCustomerReturn;
use App\Filament\Resources\CustomerReturns\Pages\ListCustomerReturns;
use App\Filament\Resources\CustomerReturns\Pages\ViewCustomerReturn;
use App\Filament\Resources\CustomerReturns\Schemas\CustomerReturnForm;
use App\Filament\Resources\CustomerReturns\Schemas\CustomerReturnInfolist;
use App\Filament\Resources\CustomerReturns\Tables\CustomerReturnsTable;
use App\Models\CustomerReturn;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use UnitEnum;

class CustomerReturnResource extends Resource
{
    protected static ?string $model = CustomerReturn::class;

    protected static ?string $navigationLabel = 'Barang Pengembalian';
    protected static UnitEnum|string|null $navigationGroup = 'Transaksi dan Pengembalian';
    protected static ?int $navigationSort = 2;
    protected static ?string $pluralModelLabel = 'Barang Pengembalian';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && $user->hasRole(['admin', 'manajer', 'kasir']);
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    protected static ?string $recordTitleAttribute = 'return_quantity';

    public static function form(Schema $schema): Schema
    {
        return CustomerReturnForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CustomerReturnInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomerReturnsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCustomerReturns::route('/'),
            'create' => CreateCustomerReturn::route('/create'),
            'view' => ViewCustomerReturn::route('/{record}'),
            'edit' => EditCustomerReturn::route('/{record}/edit'),
        ];
    }
}