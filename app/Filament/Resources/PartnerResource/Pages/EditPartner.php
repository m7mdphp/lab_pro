<?php

namespace App\Filament\Resources\PartnerResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\PartnerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartner extends EditRecord
{
    use SyncsTranslations;

    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
