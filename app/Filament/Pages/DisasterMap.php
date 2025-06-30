<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class DisasterMap extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static string $view = 'filament.pages.disaster-map';

    protected static ?string $navigationLabel = 'Peta Bencana';
    protected static ?string $title = 'Peta Bencana';
    protected static ?int $navigationSort = 2;
}
