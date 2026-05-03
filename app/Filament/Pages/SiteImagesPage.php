<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteImagesPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'صور الموقع';
    protected static ?string $title           = 'صور الموقع';
    protected static ?int    $navigationSort  = 11;

    protected static string $view = 'filament.pages.site-images';

    public array $data = [];

    public function mount(): void
    {
        $all = SiteSetting::allKeyed('images');
        $this->data = $all;
    }

    public function form(Form $form): Form
    {
        $uploadField = fn (string $key, string $label, string $helperText = '') =>
            Forms\Components\FileUpload::make($key)
                ->label($label)
                ->disk('public')
                ->directory('site-images')
                ->image()
                ->imageEditor()
                ->maxSize(5120)
                ->nullable()
                ->helperText($helperText ?: 'ارفع صورة من جهازك. أو أدخل رابطاً في الحقل المجاور.')
                ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, mixed $record) use ($key) {
                    // Don't try to load HTTP URLs as stored files
                    $value = SiteSetting::get($key);
                    if ($value && str_starts_with($value, 'http')) {
                        $component->state(null);
                    }
                })
                ->columnSpan(1),

        $urlField = fn (string $key, string $label) =>
            Forms\Components\TextInput::make($key . '_url')
                ->label('أو رابط URL للصورة')
                ->placeholder('https://images.unsplash.com/...')
                ->nullable()
                ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, mixed $record) use ($key) {
                    $value = SiteSetting::get($key);
                    if ($value && str_starts_with($value, 'http')) {
                        $component->state($value);
                    }
                })
                ->columnSpan(1);

        return $form
            ->schema([
                Forms\Components\Tabs::make('إدارة الصور')->tabs([

                    Forms\Components\Tabs\Tab::make('الصفحة الرئيسية')->icon('heroicon-o-home')->schema([
                        Forms\Components\Section::make('قسم "لماذا معامل الشيخة"')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_home_why', 'صورة قسم لماذا معامل الشيخة'),
                                $urlField('image_home_why', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('بانر سحب العينات المنزلي')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_home_collection', 'صورة بانر السحب المنزلي'),
                                $urlField('image_home_collection', 'رابط URL'),
                            ]),
                    ]),

                    Forms\Components\Tabs\Tab::make('صفحة عن المعمل')->icon('heroicon-o-building-office')->schema([
                        Forms\Components\Section::make('هيرو صفحة عن المعمل')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_about_hero', 'صورة هيرو عن المعمل'),
                                $urlField('image_about_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('بانر التحاليل المنجزة')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_about_banner', 'صورة بانر التحاليل المنجزة'),
                                $urlField('image_about_banner', 'رابط URL'),
                            ]),
                    ]),

                    Forms\Components\Tabs\Tab::make('باقي الصفحات')->icon('heroicon-o-squares-2x2')->schema([
                        Forms\Components\Section::make('هيرو الخدمات')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_services_hero', 'هيرو صفحة الخدمات'),
                                $urlField('image_services_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('هيرو التحاليل')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_tests_hero', 'هيرو صفحة التحاليل'),
                                $urlField('image_tests_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('هيرو الباقات')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_packages_hero', 'هيرو صفحة الباقات'),
                                $urlField('image_packages_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('هيرو الفروع')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_branches_hero', 'هيرو صفحة الفروع'),
                                $urlField('image_branches_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('هيرو التواصل')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_contact_hero', 'هيرو صفحة التواصل'),
                                $urlField('image_contact_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('هيرو الحجز')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_booking_hero', 'هيرو صفحة الحجز'),
                                $urlField('image_booking_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('هيرو تحضير التحاليل')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_prepare_hero', 'هيرو صفحة تحضير التحاليل'),
                                $urlField('image_prepare_hero', 'رابط URL'),
                            ]),
                        Forms\Components\Section::make('هيرو الشركاء')
                            ->columns(2)
                            ->schema([
                                $uploadField('image_partners_hero', 'هيرو صفحة الشركاء'),
                                $urlField('image_partners_hero', 'رابط URL'),
                            ]),
                    ]),

                ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        // Image keys managed by this page
        $imageKeys = [
            'image_home_why',
            'image_home_collection',
            'image_about_hero',
            'image_about_banner',
            'image_services_hero',
            'image_tests_hero',
            'image_packages_hero',
            'image_branches_hero',
            'image_contact_hero',
            'image_booking_hero',
            'image_prepare_hero',
            'image_partners_hero',
        ];

        foreach ($imageKeys as $key) {
            // Check URL override field first
            $urlOverride = $state[$key . '_url'] ?? null;
            $fileValue   = $state[$key] ?? null;

            if ($urlOverride && str_starts_with($urlOverride, 'http')) {
                SiteSetting::set($key, $urlOverride, 'images');
            } elseif ($fileValue !== null && $fileValue !== '') {
                $path = is_array($fileValue) ? ($fileValue[0] ?? null) : $fileValue;
                if ($path) {
                    SiteSetting::set($key, $path, 'images');
                }
            }
            // If both are empty/null, keep existing value (no overwrite)
        }

        SiteSetting::clearCache();

        Notification::make()
            ->title('تم حفظ الصور بنجاح')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('حفظ الصور')
                ->action('save')
                ->color('success')
                ->size('lg'),
        ];
    }
}
