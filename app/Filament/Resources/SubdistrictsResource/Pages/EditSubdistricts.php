<?php

namespace App\Filament\Resources\SubdistrictsResource\Pages;

use App\Filament\Resources\SubdistrictsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubdistricts extends EditRecord
{
    protected static string $resource = SubdistrictsResource::class;
    protected static ?string $title = 'Edit Kecamatan';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data kecamatan berhasil diubah!';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
