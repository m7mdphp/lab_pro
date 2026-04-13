<?php

namespace App\Filament\Resources\TestCategoryResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\TestCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestCategory extends CreateRecord
{
    use SyncsTranslations;

    protected static string $resource = TestCategoryResource::class;
}
