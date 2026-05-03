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
        // ── Preview thumbnail of current saved image ──────────────────────────
        $previewField = fn (string $key) =>
            Forms\Components\Placeholder::make($key . '_preview')
                ->label('الصورة الحالية')
                ->content(function () use ($key): \Illuminate\Support\HtmlString {
                    $value = SiteSetting::get($key);

                    if (!$value) {
                        return new \Illuminate\Support\HtmlString(
                            '<div class="flex flex-col items-center justify-center h-28 rounded-xl border-2 border-dashed border-gray-300 bg-gray-50 text-gray-400 gap-1.5">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs font-medium text-center px-2">لا توجد صورة — سيتم استخدام الافتراضية</span>
                            </div>'
                        );
                    }

                    $url = str_starts_with($value, 'http')
                        ? $value
                        : asset('storage/' . $value);

                    $name = basename(parse_url($url, PHP_URL_PATH));

                    return new \Illuminate\Support\HtmlString(
                        '<div class="relative rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                            <img src="' . e($url) . '" class="w-full h-28 object-cover" alt="الصورة الحالية" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-2">
                                <span class="text-white text-[10px] font-semibold bg-black/40 rounded px-1.5 py-0.5 truncate max-w-full inline-block">' . e($name) . '</span>
                            </div>
                        </div>'
                    );
                })
                ->columnSpan(1);

        // ── Upload field ──────────────────────────────────────────────────────
        $uploadField = fn (string $key, string $label, string $helperText = '') =>
            Forms\Components\FileUpload::make($key)
                ->label($label)
                ->disk('public')
                ->directory('site-images')
                ->image()
                ->imageEditor()
                ->maxSize(5120)
                ->nullable()
                ->helperText($helperText ?: 'ارفع صورة من جهازك (حتى 5 MB).')
                ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, mixed $record) use ($key) {
                    $value = SiteSetting::get($key);
                    if ($value && str_starts_with($value, 'http')) {
                        $component->state(null);
                    }
                })
                ->columnSpan(1);

        // ── URL field ─────────────────────────────────────────────────────────
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

        // ── Helper: 3-column section (preview | upload | url) ─────────────────
        $imgSection = fn (string $key, string $sectionTitle, string $uploadLabel) =>
            Forms\Components\Section::make($sectionTitle)
                ->columns(3)
                ->schema([
                    $previewField($key),
                    $uploadField($key, $uploadLabel),
                    $urlField($key, $uploadLabel),
                ]);

        return $form
            ->schema([
                Forms\Components\Tabs::make('إدارة الصور')->tabs([

                    Forms\Components\Tabs\Tab::make('الصفحة الرئيسية')->icon('heroicon-o-home')->schema([
                        $imgSection('image_home_why',        'قسم "لماذا معامل الشيخة"',        'صورة قسم لماذا'),
                        $imgSection('image_home_collection', 'بانر سحب العينات المنزلي',         'صورة بانر السحب'),
                    ]),

                    Forms\Components\Tabs\Tab::make('صفحة عن المعمل')->icon('heroicon-o-building-office')->schema([
                        $imgSection('image_about_hero',   'هيرو صفحة عن المعمل',         'صورة هيرو عن المعمل'),
                        $imgSection('image_about_banner', 'بانر التحاليل المنجزة',        'صورة بانر التحاليل'),
                    ]),

                    Forms\Components\Tabs\Tab::make('باقي الصفحات')->icon('heroicon-o-squares-2x2')->schema([
                        $imgSection('image_services_hero', 'هيرو الخدمات',                'هيرو صفحة الخدمات'),
                        $imgSection('image_tests_hero',    'هيرو التحاليل',               'هيرو صفحة التحاليل'),
                        $imgSection('image_packages_hero', 'هيرو الباقات',                'هيرو صفحة الباقات'),
                        $imgSection('image_branches_hero', 'هيرو الفروع',                 'هيرو صفحة الفروع'),
                        $imgSection('image_contact_hero',  'هيرو التواصل',                'هيرو صفحة التواصل'),
                        $imgSection('image_booking_hero',  'هيرو الحجز',                  'هيرو صفحة الحجز'),
                        $imgSection('image_prepare_hero',  'هيرو تحضير التحاليل',         'هيرو صفحة التحضير'),
                        $imgSection('image_partners_hero', 'هيرو الشركاء',                'هيرو صفحة الشركاء'),
                    ]),

                ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

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
