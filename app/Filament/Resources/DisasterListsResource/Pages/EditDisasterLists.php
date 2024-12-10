<?php

namespace App\Filament\Resources\DisasterListsResource\Pages;

use App\Filament\Resources\DisasterListsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisasterLists extends EditRecord
{
    protected static string $resource = DisasterListsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
