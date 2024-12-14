<?php

namespace App\Filament\Resources;

use livewire;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Subdistricts;
use App\Models\DisasterLists;
use App\Models\DisasterTypes;
use Filament\Resources\Resource;

use Illuminate\Support\Facades\DB;
use App\Forms\Components\MapPicker;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Actions\Action;
use Filament\Support\Enums\VerticalAlignment;
use Cheesegrits\FilamentGoogleMaps\Fields\Map;
use App\Filament\Resources\DisasterListsResource\Pages;
use App\Filament\Resources\DisasterListsResource\RelationManagers;
use App\Filament\Resources\DisasterListsResource\Pages\EditDisasterLists;
use App\Filament\Resources\DisasterListsResource\Pages\ListDisasterLists;
use App\Filament\Resources\DisasterListsResource\Pages\CreateDisasterLists;

class DisasterListsResource extends Resource
{
    protected static ?string $model = DisasterLists::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Manajemen';
    protected static ?string $navigationLabel = 'Data Bencana';
    protected static ?string $slug = 'data-bencana';
    protected static ?string $breadcrumb = 'Data Bencana';


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
                            ->helperText('Kode dihasilkan otomatis.'),
                        DatePicker::make('tanggal_kejadian')
                            ->label('Tanggal Kejadian')
                            ->default(now())
                            ->native(false)
                            ->displayFormat('d mm Y')
                            ->required(),
                        Select::make('kode_jenis_bencana')
                            ->label('Jenis Bencana')
                            ->relationship('disasterTypes', 'jenis_bencana')
                            ->options(DisasterTypes::all()->pluck('jenis_bencana', 'kode'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('kode_kecamatan')
                            ->label('Kecamatan')
                            ->relationship('subdistricts', 'nama_kecamatan')
                            ->options(Subdistricts::all()->pluck('nama_kecamatan', 'kode'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('desa')
                            ->label('Nama Desa')
                            ->required(),
                        TextInput::make('penyebab')
                            ->label('Penyebab'),


                        Section::make('Detail Dampak')
                            ->schema([
                                Textarea::make('dampak')
                                    ->label('Dampak atau Kerusakan')
                                    ->required()
                                    ->rows(3),
                                Grid::make(2)
                                    ->schema([
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
                                    ]),
                                FileUpload::make('foto')
                                    ->label('Unggah Foto')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']) // Validasi jenis file
                                    ->directory('foto_bencana') // Direktori penyimpanan foto
                                    ->nullable()
                                    ->required(false) // Tidak wajib diisi
                                    ->imagePreviewHeight('250')
                                    ->panelAspectRatio('4:1')
                                    ->panelLayout('integrated')
                                    ->removeUploadedFileButtonPosition('right')
                                    ->uploadButtonPosition('left')
                                    ->uploadProgressIndicatorPosition('left')
                                    ->helperText('Hanya menerima file JPG atau PNG.'),
                            ])
                            ->collapsible(),


                        Textarea::make('upaya')
                            ->label('Upaya yang Dilakukan')
                            ->required()
                            ->rows(5),
                        Section::make('Lokasi')
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('latitude')
                                            ->label('Latitude')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $get, callable $set, $livewire) {
                                                $set('location', [
                                                    'lat' => floatVal($state),
                                                    'lng' => floatVal($get('location')['lng']),
                                                ]);
                                                $livewire->dispatch('refreshMap');
                                            })
                                            ->lazy()
                                            ->helperText('Koordinat lintang.'),
                                        TextInput::make('longitude')
                                            ->label('Longitude')
                                            ->numeric()
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $get, callable $set, $livewire) {
                                                $set('location', [
                                                    'lat' => floatval($get('location')['lat']),
                                                    'lng' => floatVal($state),
                                                ]);
                                                $livewire->dispatch('refreshMap');
                                            })
                                            ->lazy()
                                            ->helperText('Koordinat bujur.'),
                                        Map::make('location')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                                $set('latitude', $state['lat']);
                                                $set('longitude', $state['lng']);
                                            })
                                            ->clickable(true)
                                            ->defaultZoom(15)
                                            ->defaultLocation([-7.02360292124367, 109.59210851245584])
                                            ->columnSpanFull(),
                                        Actions::make([
                                            Action::make('Perbarui Latitude and Longitude')
                                                ->icon('heroicon-m-map-pin')
                                                ->action(function (Set $set, callable $get, $state, $livewire): void {
                                                    $set('latitude', $get('location')['lat']);
                                                    $set('longitude', $get('location')['lng']);
                                                })
                                        ])->verticalAlignment(VerticalAlignment::Start),
                                    ]),

                            ])
                            ->collapsible(),

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
                    ->date('d F Y')
                    ->sortable(),
                TextColumn::make('disasterTypes.jenis_bencana')
                    ->label('Jenis Bencana')
                    ->sortable(),
                TextColumn::make('subdistricts.nama_kecamatan')
                    ->label('Kecamatan'),
                TextColumn::make('desa')
                    ->label('Desa'),
                TextColumn::make('dampak')
                    ->label('Dampak')
                    ->extraAttributes(['style' => 'width: 12rem; max-width: 12rem;  white-space: normal; word-wrap: break-word;']),
                TextColumn::make('kk')
                    ->toggleable()
                    ->label('KK'),
                TextColumn::make('jiwa')
                    ->toggleable()
                    ->label('Jiwa'),
                TextColumn::make('meninggal')
                    ->toggleable()
                    ->label('MD'),
                TextColumn::make('upaya')
                    ->label('Upaya')
                    ->extraAttributes(['style' => 'width: 12rem; max-width: 12rem;  white-space: normal; word-wrap: break-word;']),
                TextColumn::make('nilai_kerusakan')
                    ->toggleable()
                    ->label('Kerugian')
                    ->money('IDR'), // Format mata uang
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->height(100) // Tinggi gambar dalam tabel
                    ->width(100) // Lebar gambar dalam tabel
                    ->alignment(Alignment::Center)
                    ->defaultImageUrl(url('storage/foto_bencana/default.jpg'))
            ])
            ->defaultSort('tanggal_kejadian', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Hapus Data Bencana')
                    ->modalDescription('Apakah Anda yakin ingin menghapus data bencana ini?'),
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
