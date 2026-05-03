<x-filament-panels::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div class="mt-6 flex justify-start">
            <x-filament::button type="submit" color="success" size="lg" icon="heroicon-o-check">
                حفظ الصور
            </x-filament::button>
        </div>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>
