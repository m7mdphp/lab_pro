<?php

namespace App\Filament\Resources\PackageResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\PackageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePackage extends CreateRecord
{
    use SyncsTranslations;

    protected static string $resource = PackageResource::class;
}
