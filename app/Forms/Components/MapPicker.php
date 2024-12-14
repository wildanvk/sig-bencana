<?php

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class MapPicker extends Field
{
    protected string $view = 'forms.components.map-picker';

    protected ?float $defaultLatitude = null;

    protected ?float $defaultLongitude = null;

    public function defaultLatitude(float $latitude): static
    {
        $this->defaultLatitude = $latitude;
        return $this;
    }

    public function defaultLongitude(float $longitude): static
    {
        $this->defaultLongitude = $longitude;
        return $this;
    }

    public function getDefaultLatitude(): ?float
    {
        return $this->defaultLatitude;
    }

    public function getDefaultLongitude(): ?float
    {
        return $this->defaultLongitude;
    }
}
