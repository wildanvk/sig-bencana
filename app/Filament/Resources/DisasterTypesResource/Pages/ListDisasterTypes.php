<?php

namespace App\Filament\Resources\DisasterTypesResource\Pages;

use App\Filament\Resources\DisasterTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDisasterTypes extends ListRecords
{
    protected static string $resource = DisasterTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
