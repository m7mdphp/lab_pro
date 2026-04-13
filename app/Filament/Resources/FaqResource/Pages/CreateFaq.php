<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\FaqResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFaq extends CreateRecord
{
    use SyncsTranslations;

    protected static string $resource = FaqResource::class;
}
