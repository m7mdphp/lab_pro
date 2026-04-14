<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\ServiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    use SyncsTranslations;

    protected static string $resource = ServiceResource::class;
}
