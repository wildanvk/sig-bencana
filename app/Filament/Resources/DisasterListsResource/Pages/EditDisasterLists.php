<?php

namespace App\Filament\Resources\DisasterListsResource\Pages;

use App\Filament\Resources\DisasterListsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDisasterLists extends EditRecord
{
    protected static string $resource = DisasterListsResource::class;
    protected static ?string $title = 'Edit Data Bencana';

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Data bencana berhasil diubah!';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
