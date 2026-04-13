<?php

namespace App\Filament\Resources\BranchResource\Pages;

use App\Filament\Concerns\SyncsTranslations;
use App\Filament\Resources\BranchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBranch extends CreateRecord
{
    use SyncsTranslations;

    protected static string $resource = BranchResource::class;
}
