<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteContentPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'محتوى الموقع';
    protected static ?string $title           = 'محتوى الموقع النصي';
    protected static ?int    $navigationSort  = 12;

    protected static string $view = 'filament.pages.site-content';

    public array $data = [];

    public function mount(): void
    {
        $this->data = SiteSetting::allKeyed('content');
    }

    public function form(Form $form): Form
    {
        $hint = 'اتركه فارغاً لاستخدام النص الافتراضي';

        /**
         * Returns [TextInput/Textarea AR, TextInput/Textarea EN]
         * Arrow fn auto-captures $hint from enclosing scope.
         */
        $pair = fn (
            string $key,
            string $arLabel,
            string $enLabel  = '',
            bool   $textarea = false
        ) => [
            $textarea
                ? Forms\Components\Textarea::make($key . '_ar')
                    ->label($arLabel)->rows(3)->helperText($hint)->columnSpan(1)
                : Forms\Components\TextInput::make($key . '_ar')
                    ->label($arLabel)->helperText($hint)->columnSpan(1),
            $textarea
                ? Forms\Components\Textarea::make($key . '_en')
                    ->label($enLabel ?: ($arLabel . ' (EN)'))->rows(3)->helperText($hint)->columnSpan(1)
                : Forms\Components\TextInput::make($key . '_en')
                    ->label($enLabel ?: ($arLabel . ' (EN)'))->helperText($hint)->columnSpan(1),
        ];

        /** Helper: single page title+subtitle section */
        $pageSection = fn (string $page, string $icon, string $title) =>
            Forms\Components\Section::make("{$icon} {$title}")
                ->columns(2)
                ->collapsible()
                ->schema([
                    ...$pair("text_{$page}_title",    'عنوان الصفحة (عربي)',   'Page Title (EN)'),
                    ...$pair("text_{$page}_subtitle",  'النص التعريفي (عربي)',  'Subtitle (EN)', true),
                ]);

        return $form
            ->schema([
                Forms\Components\Tabs::make('محتوى الموقع')->tabs([

                    // ─── Tab 1: Home section headings ─────────────────────────────
                    Forms\Components\Tabs\Tab::make('الصفحة الرئيسية')
                        ->icon('heroicon-o-home')
                        ->schema([
                            Forms\Components\Section::make('عناوين الأقسام الرئيسية')
                                ->description('تظهر كعناوين الأقسام على الصفحة الرئيسية')
                                ->columns(2)
                                ->schema([
                                    ...$pair('text_home_categories_title',
                                        'قسم التحاليل — العنوان (عربي)', 'Test Categories Title (EN)'),
                                    ...$pair('text_home_packages_title',
                                        'قسم الباقات — العنوان (عربي)', 'Featured Packages Title (EN)'),
                                    ...$pair('text_home_why_title',
                                        'قسم "لماذا نختارنا" — العنوان (عربي)', '"Why Choose Us" Title (EN)'),
                                ]),

                            Forms\Components\Section::make('قسم الحجز الدعوي (CTA)')
                                ->description('يظهر أسفل الصفحة الرئيسية')
                                ->columns(2)
                                ->schema([
                                    ...$pair('text_home_cta_title',
                                        'عنوان CTA (عربي)', 'CTA Title (EN)'),
                                    ...$pair('text_home_cta_subtitle',
                                        'نص CTA (عربي)', 'CTA Subtitle (EN)', true),
                                ]),
                        ]),

                    // ─── Tab 2: Inner pages title / subtitle ──────────────────────
                    Forms\Components\Tabs\Tab::make('الصفحات الداخلية')
                        ->icon('heroicon-o-squares-2x2')
                        ->schema([
                            $pageSection('services', '📋', 'الخدمات'),
                            $pageSection('tests',    '🔬', 'التحاليل'),
                            $pageSection('packages', '📦', 'الباقات'),
                            $pageSection('branches', '📍', 'الفروع'),
                            $pageSection('contact',  '📞', 'التواصل'),
                            $pageSection('booking',  '📅', 'الحجز'),
                            $pageSection('prepare',  '📝', 'التحضير للتحاليل'),
                            $pageSection('partners', '🤝', 'الشركاء'),
                        ]),

                    // ─── Tab 3: About page detailed content ───────────────────────
                    Forms\Components\Tabs\Tab::make('صفحة عن المعمل')
                        ->icon('heroicon-o-building-office')
                        ->schema([
                            Forms\Components\Section::make('العنوان والمقدمة')
                                ->columns(2)
                                ->schema([
                                    ...$pair('text_about_title',    'عنوان الصفحة (عربي)',  'Page Title (EN)'),
                                    ...$pair('text_about_subtitle', 'المقدمة (عربي)',        'Introduction (EN)', true),
                                ]),

                            Forms\Components\Section::make('المهمة والرؤية')
                                ->columns(2)
                                ->schema([
                                    ...$pair('text_about_mission_title', 'عنوان المهمة (عربي)',  'Mission Title (EN)'),
                                    ...$pair('text_about_mission',       'نص المهمة (عربي)',     'Mission Text (EN)',  true),
                                    ...$pair('text_about_vision_title',  'عنوان الرؤية (عربي)',  'Vision Title (EN)'),
                                    ...$pair('text_about_vision',        'نص الرؤية (عربي)',     'Vision Text (EN)',   true),
                                ]),

                            Forms\Components\Section::make('لماذا نختارنا')
                                ->columns(2)
                                ->schema([
                                    ...$pair('text_about_why_title',    'عنوان القسم (عربي)',     'Section Title (EN)'),
                                    ...$pair('text_about_why_subtitle', 'النص التعريفي (عربي)',   'Subtitle (EN)', true),
                                ]),

                            Forms\Components\Section::make('البرامج المتخصصة')
                                ->columns(2)
                                ->schema([
                                    ...$pair('text_about_branded_title',    'عنوان القسم (عربي)',   'Section Title (EN)'),
                                    ...$pair('text_about_branded_subtitle', 'النص التعريفي (عربي)', 'Subtitle (EN)', true),
                                ]),
                        ]),

                    // ─── Tab 4: Footer ────────────────────────────────────────────
                    Forms\Components\Tabs\Tab::make('الفوتر')
                        ->icon('heroicon-o-bars-3-bottom-left')
                        ->schema([
                            Forms\Components\Section::make('شعار الفوتر')
                                ->description('النص الظاهر تحت الشعار في الفوتر')
                                ->columns(2)
                                ->schema([
                                    ...$pair('text_footer_tagline', 'الشعار (عربي)', 'Tagline (EN)', true),
                                ]),
                        ]),

                ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        foreach ($state as $key => $value) {
            // Save empty string to effectively "unset" (blade will fall back to __())
            SiteSetting::set($key, $value ?? '', 'content');
        }

        SiteSetting::clearCache();

        Notification::make()
            ->title('✅ تم حفظ المحتوى بنجاح')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('💾 حفظ المحتوى')
                ->action('save')
                ->color('success')
                ->size('lg'),
        ];
    }
}
