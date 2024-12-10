<?php

namespace App\Filament\Resources\DisasterTypesResource\Pages;

use App\Filament\Resources\DisasterTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDisasterTypes extends CreateRecord
{
    protected static string $resource = DisasterTypesResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Jenis bencana berhasil ditambahkan!';
    }
}
