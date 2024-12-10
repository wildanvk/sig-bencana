<?php

namespace App\Filament\Resources\SubdistrictsResource\Pages;

use App\Filament\Resources\SubdistrictsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubdistricts extends EditRecord
{
    protected static string $resource = SubdistrictsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
