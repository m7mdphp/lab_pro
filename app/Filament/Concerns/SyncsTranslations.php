<?php

namespace App\Filament\Concerns;

/**
 * Add to Filament Create and Edit page classes.
 * The resource class must define:
 *   public static array $translatableFields = ['field1', 'field2'];
 */
trait SyncsTranslations
{
    private array $pendingTranslations = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$data, $this->pendingTranslations] = $this->partitionTranslations($data);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$data, $this->pendingTranslations] = $this->partitionTranslations($data);
        return $data;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        $fields = static::getResource()::$translatableFields ?? [];
        foreach (['en', 'ar'] as $locale) {
            $trans = $record->translations()->where('locale', $locale)->first();
            foreach ($fields as $field) {
                $data["{$field}_{$locale}"] = $trans?->$field;
            }
        }
        return $data;
    }

    protected function afterCreate(): void
    {
        $this->flushTranslations();
    }

    protected function afterSave(): void
    {
        $this->flushTranslations();
    }

    private function partitionTranslations(array $data): array
    {
        $translations = [];
        $fields = static::getResource()::$translatableFields ?? [];
        foreach (['en', 'ar'] as $locale) {
            foreach ($fields as $field) {
                $key = "{$field}_{$locale}";
                if (array_key_exists($key, $data)) {
                    $translations[$locale][$field] = $data[$key];
                    unset($data[$key]);
                }
            }
        }
        return [$data, $translations];
    }

    private function flushTranslations(): void
    {
        foreach ($this->pendingTranslations as $locale => $fields) {
            $this->record->translations()->updateOrCreate(['locale' => $locale], $fields);
        }
    }
}
