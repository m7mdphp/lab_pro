<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\PartnerResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePartner extends CreateRecord
{
    use SyncsTranslations;

    protected static string $resource = PartnerResource::class;
}
