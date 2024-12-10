<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\DisasterLists;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DisasterListsResource\Pages;
use App\Filament\Resources\DisasterListsResource\RelationManagers;
use App\Filament\Resources\DisasterListsResource\Pages\EditDisasterLists;
use App\Filament\Resources\DisasterListsResource\Pages\ListDisasterLists;
use App\Filament\Resources\DisasterListsResource\Pages\CreateDisasterLists;
use App\Models\DisasterTypes;
use App\Models\Subdistricts;

class DisasterListsResource extends Resource
{
    protected static ?string $model = DisasterLists::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?string $navigationLabel = 'Data Bencana';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode')
                            ->label('Kode Bencana')
                            ->disabled()
                            ->default(fn() => 'BNC-' . str_pad((\App\Models\DisasterLists::max('kode') ? (int) substr(\App\Models\DisasterLists::max('kode'), 4) + 1 : 1), 4, '0', STR_PAD_LEFT))
                            ->helperText('Kode akan dihasilkan otomatis.'),
                        DatePicker::make('tanggal_kejadian')
                            ->label('Tanggal Kejadian')
                            ->default(now())
                            ->required(),
                        Select::make('kode_jenis_bencana')
                            ->label('Jenis Bencana')
                            ->relationship('disasterTypes', 'jenis_bencana')
                            ->options(DisasterTypes::all()->pluck('jenis_bencana', 'kode'))
                            ->searchable()
                            ->required(),
                        Select::make('kode_kecamatan')
                            ->label('Kecamatan')
                            ->relationship('subdistricts', 'nama_kecamatan')
                            ->options(Subdistricts::all()->pluck('nama_kecamatan', 'kode'))
                            ->searchable()
                            ->required(),
                        TextInput::make('desa')
                            ->label('Nama Desa')
                            ->required(),
                        TextInput::make('penyebab')
                            ->label('Penyebab'),
                        Textarea::make('dampak')
                            ->label('Dampak atau Kerusakan'),
                        TextInput::make('kk')
                            ->label('Jumlah KK Terdampak')
                            ->numeric(),
                        TextInput::make('jiwa')
                            ->label('Jumlah Jiwa Terdampak')
                            ->numeric(),
                        TextInput::make('sakit')
                            ->label('Jumlah Orang Sakit')
                            ->numeric(),
                        TextInput::make('hilang')
                            ->label('Jumlah Orang Hilang')
                            ->numeric(),
                        TextInput::make('meninggal')
                            ->label('Jumlah Orang Meninggal')
                            ->numeric(),
                        TextInput::make('nilai_kerusakan')
                            ->label('Nilai Kerusakan (Rp)')
                            ->numeric(),
                        Textarea::make('upaya')
                            ->label('Upaya yang Dilakukan'),
                        FileUpload::make('foto')
                            ->label('Unggah Foto')
                            ->image()
                            ->directory('foto_bencana') // Direktori penyimpanan foto
                            ->nullable()
                            ->required(false) // Tidak wajib diisi
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
                TextColumn::make('tanggal_kejadian')
                    ->label('Tanggal Kejadian')
                    ->date()
                    ->sortable(),
                TextColumn::make('disasterTypes.nama')
                    ->label('Jenis Bencana')
                    ->sortable(),
                TextColumn::make('subdistricts.nama')
                    ->label('Kecamatan'),
                TextColumn::make('desa')
                    ->label('Desa'),
                TextColumn::make('kk')
                    ->label('KK'),
                TextColumn::make('jiwa')
                    ->label('Jiwa'),
                TextColumn::make('meninggal')
                    ->label('Meninggal Dunia'),
                TextColumn::make('nilai_kerusakan')
                    ->label('Kerugian (Rp)')
                    ->money('IDR'), // Format mata uang
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->height(100) // Tinggi gambar dalam tabel
                    ->width(100) // Lebar gambar dalam tabel
                    ->defaultImageUrl(url('storage/foto_bencana/default.jpg'))
            ])
            ->defaultSort('tanggal_kejadian', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListDisasterLists::route('/'),
            'create' => Pages\CreateDisasterLists::route('/create'),
            'edit' => Pages\EditDisasterLists::route('/{record}/edit'),
        ];
    }
}
