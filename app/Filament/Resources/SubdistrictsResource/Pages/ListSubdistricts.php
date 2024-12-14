<?php

namespace App\Filament\Resources\SubdistrictsResource\Pages;

use App\Filament\Resources\SubdistrictsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubdistricts extends ListRecords
{
    protected static string $resource = SubdistrictsResource::class;
    protected static ?string $title = 'Kecamatan';
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
