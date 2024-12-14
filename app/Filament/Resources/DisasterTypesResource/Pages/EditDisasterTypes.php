<?php

namespace App\Filament\Resources\DisasterTypesResource\Pages;

use App\Filament\Resources\DisasterTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisasterTypes extends EditRecord
{
    protected static string $resource = DisasterTypesResource::class;
    protected static ?string $title = 'Edit Jenis Bencana';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data jenis bencana berhasil diubah!';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
