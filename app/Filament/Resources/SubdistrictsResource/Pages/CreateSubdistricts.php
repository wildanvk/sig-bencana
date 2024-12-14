<?php

namespace App\Filament\Resources\SubdistrictsResource\Pages;

use App\Filament\Resources\SubdistrictsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSubdistricts extends CreateRecord
{
    protected static string $resource = SubdistrictsResource::class;
    protected static ?string $title = 'Tambah Kecamatan';
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Kecamatan berhasil ditambahkan!';
    }
}
