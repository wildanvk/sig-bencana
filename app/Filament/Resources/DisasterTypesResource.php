<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DisasterTypes;
use Filament\Resources\Resource;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DisasterTypesResource\Pages;
use App\Filament\Resources\DisasterTypesResource\RelationManagers;
use App\Filament\Resources\DisasterTypesResource\Pages\EditDisasterTypes;
use App\Filament\Resources\DisasterTypesResource\Pages\ListDisasterTypes;
use App\Filament\Resources\DisasterTypesResource\Pages\CreateDisasterTypes;
use Filament\Forms\Components\Card;

class DisasterTypesResource extends Resource
{
    protected static ?string $model = DisasterTypes::class;
    protected static ?string $title = 'Custom Page Title';
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $navigationLabel = 'Jenis Bencana';
    protected static ?string $slug = 'jenis-bencana';
    protected static ?string $breadcrumb = 'Jenis Bencana';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode')
                            ->disabled() // Tidak bisa diubah (generate otomatis)
                            ->default(fn() => 'JNS-' . str_pad((\App\Models\Subdistricts::max('kode') ? (int) substr(\App\Models\Subdistricts::max('kode'), 4) + 1 : 1), 4, '0', STR_PAD_LEFT))
                            ->helperText('Kode dihasilkan otomatis.'),
                        TextInput::make('jenis_bencana')
                            ->label('Nama Jenis Bencana')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columnSpan('full'),
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
                TextColumn::make('jenis_bencana')
                    ->label('Nama Jenis Bencana')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('kode') // Default sorting berdasarkan kode
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Hapus Jenis Bencana')
                    ->modalDescription('Apakah Anda yakin ingin menghapus jenis bencana ini?'),
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
            'index' => Pages\ListDisasterTypes::route('/'),
            'create' => Pages\CreateDisasterTypes::route('/create'),
            'edit' => Pages\EditDisasterTypes::route('/{record}/edit'),
        ];
    }
}
