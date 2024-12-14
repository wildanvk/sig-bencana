<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Cheesegrits\FilamentGoogleMaps\Columns\MapColumn;
use Cheesegrits\FilamentGoogleMaps\Actions\GoToAction;
use Cheesegrits\FilamentGoogleMaps\Filters\MapIsFilter;
use Cheesegrits\FilamentGoogleMaps\Actions\RadiusAction;
use Cheesegrits\FilamentGoogleMaps\Filters\RadiusFilter;
use Cheesegrits\FilamentGoogleMaps\Widgets\MapTableWidget;

class DisasterListsMapTableWidget extends MapTableWidget
{
	protected static ?string $heading = 'Peta Data Bencana';

	protected static ?int $sort = 1;

	protected static ?string $pollingInterval = null;

	protected static ?string $markerAction = 'markerAction';

	protected static ?bool $clustering = true;

	protected static ?string $mapId = 'incidents';

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

	protected function getTableFilters(): array
	{
		return [
			RadiusFilter::make('location')
				->section('Radius Filter')
				->selectUnit(),
			MapIsFilter::make('map'),
		];
	}

	protected function getTableActions(): array
	{
		return [
			Tables\Actions\ViewAction::make(),
			Tables\Actions\EditAction::make(),
			GoToAction::make()
				->zoom(14)
				->label('Lihat Lokasi'),
			// RadiusAction::make()
			// 	->label('Radius Search'),
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
					->columns(3)
			])
			->record(function (array $arguments) {
				return array_key_exists('model_id', $arguments) ? \App\Models\DisasterLists::find($arguments['model_id']) : null;
			})
			->modalSubmitAction(false);
	}
}
