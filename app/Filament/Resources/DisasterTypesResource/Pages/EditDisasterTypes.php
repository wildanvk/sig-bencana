<?php

namespace App\Filament\Resources\DisasterTypesResource\Pages;

use App\Filament\Resources\DisasterTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisasterTypes extends EditRecord
{
    protected static string $resource = DisasterTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
