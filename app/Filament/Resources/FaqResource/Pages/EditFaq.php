<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    use SyncsTranslations;

    protected static string $resource = FaqResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
