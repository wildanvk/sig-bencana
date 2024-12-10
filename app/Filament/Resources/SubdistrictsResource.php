<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Subdistricts;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubdistrictsResource\Pages;
use App\Filament\Resources\SubdistrictsResource\RelationManagers;

class SubdistrictsResource extends Resource
{
    protected static ?string $model = Subdistricts::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-asia-australia';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Kecamatan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode Kecamatan')
                            ->disabled()
                            ->default(fn() => 'KCM-' . str_pad((\App\Models\Subdistricts::max('kode') ? (int) substr(\App\Models\Subdistricts::max('kode'), 4) + 1 : 1), 4, '0', STR_PAD_LEFT))
                            ->helperText('Kode akan dihasilkan otomatis.'),
                        TextInput::make('nama_kecamatan')
                            ->label('Nama Kecamatan')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columnSpan('full'), // Menampilkan semua field dalam satu kolom
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kode')
                    ->label('Kode')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_kecamatan')
                    ->label('Nama Kecamatan')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('kode') // Default sorting berdasarkan kode
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubdistricts::route('/'),
            'create' => Pages\CreateSubdistricts::route('/create'),
            'edit' => Pages\EditSubdistricts::route('/{record}/edit'),
        ];
    }
}
