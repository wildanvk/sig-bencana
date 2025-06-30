<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Actions\Action;
use App\Models\DisasterTypes;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Cheesegrits\FilamentGoogleMaps\Actions\GoToAction;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;

class DisasterListsMapTableWidget extends MapTableWidget
{
	protected static ?string $heading = 'Peta Data Bencana';

	protected static ?int $sort = 1;

	protected static ?string $pollingInterval = null;

	protected static ?string $markerAction = 'markerAction';

	protected static ?bool $clustering = true;

	protected static ?string $mapId = 'incidents';

	protected array $mapOptions = [
		'zoom' => 11,
		'center' => [
			'lat' => -6.8896,
			'lng' => 109.6753,
		],
		'restriction' => [
			'latLngBounds' => [
				'north' => -6.7,
				'south' => -7.15,
				'east' => 109.85,
				'west' => 109.45,
			],
			'strictBounds' => true,
		],
		'gestureHandling' => 'greedy', // opsional, pastikan drag aktif di dalam batas
		'draggable' => true, // biarkan draggable tapi tetap terkunci pada batas
	];

	protected function getFormSchema(): array
	{
		return [
			Card::make()
				->schema([
					TextInput::make('kode')
						->label('Kode Bencana'),
					DatePicker::make('tanggal_kejadian')
						->label('Tanggal Kejadian')
						->required()
						->native(false)
						->displayFormat('d mm Y'),
					Select::make('kode_jenis_bencana')
						->label('Jenis Bencana')
						->relationship('disasterTypes', 'jenis_bencana')
						->options(DisasterTypes::all()->pluck('jenis_bencana', 'kode')),
					TextInput::make('desa')
						->label('Desa'),
				])
		];
	}

	protected function getTableQuery(): Builder
	{
		return \App\Models\DisasterLists::query()->latest();
	}

	protected function getTableColumns(): array
	{
		return [
			Tables\Columns\TextColumn::make('kode')
				->searchable(),
			Tables\Columns\TextColumn::make('tanggal_kejadian')
				->searchable()
				->date('d F Y'),
			Tables\Columns\TextColumn::make('disasterTypes.jenis_bencana')
				->label('Jenis Bencana')
				->searchable(),
			Tables\Columns\TextColumn::make('desa'),
			Tables\Columns\TextColumn::make('subdistricts.nama_kecamatan')
				->label('Kecamatan')
				->searchable(),
		];
	}

	protected function getTableActions(): array
	{
		return [
			Tables\Actions\ViewAction::make()
				->label('Lihat Detail')
				->form($this->getFormSchema()),
			GoToAction::make()
				->zoom(14)
				->label('Lihat Lokasi'),
		];
	}

	protected function getData(): array
	{
		$locations = $this->getRecords();

		$data = [];

		foreach ($locations as $location) {
			$data[] = [
				'location' => [
					'lat' => $location->latitude ? round(floatval($location->latitude), static::$precision) : 0,
					'lng' => $location->longitude ? round(floatval($location->longitude), static::$precision) : 0,
				],
				'id'      => $location->kode,
			];
		}

		return $data;
	}

	public function markerAction(): Action
	{
		return Action::make('markerAction')
			->label('Details')
			->infolist([
				Section::make([
					TextEntry::make('kode'),
					TextEntry::make('tanggal_kejadian')
						->date('d F Y'),
					TextEntry::make('disasterTypes.jenis_bencana'),
					TextEntry::make('desa'),
				])
					->columns(1)
			])
			->record(function (array $arguments) {
				return array_key_exists('model_id', $arguments) ? \App\Models\DisasterLists::find($arguments['model_id']) : null;
			})
			->modalSubmitAction(false);
	}
}
