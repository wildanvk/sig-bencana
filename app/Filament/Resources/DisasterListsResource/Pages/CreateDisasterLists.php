<?php

namespace App\Filament\Resources\DisasterListsResource\Pages;

use App\Filament\Resources\DisasterListsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDisasterLists extends CreateRecord
{
    protected static string $resource = DisasterListsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data bencana  berhasil ditambahkan!';
    }
}
