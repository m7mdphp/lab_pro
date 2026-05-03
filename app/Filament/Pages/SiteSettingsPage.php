<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettingsPage extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'إعدادات الموقع';
    protected static ?string $title           = 'إعدادات الموقع';
    protected static ?int    $navigationSort  = 10;

    protected static string $view = 'filament.pages.site-settings';

    // Bound form data
    public array $data = [];

    public function mount(): void
    {
        $this->data = SiteSetting::allKeyed();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')->tabs([

                    Forms\Components\Tabs\Tab::make('معلومات التواصل')->icon('heroicon-o-phone')->schema([
                        Forms\Components\TextInput::make('hotline')
                            ->label('الخط الساخن (Hotline)')
                            ->placeholder('19XXX')
                            ->helperText('يظهر في أعلى الموقع وفي الـ Footer')
                            ->required(),
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('رقم واتساب')
                            ->placeholder('01000000000'),
                        Forms\Components\TextInput::make('email')
                            ->label('البريد الإلكتروني')
                            ->email()
                            ->placeholder('info@elsheikhalab.com'),
                        Forms\Components\TextInput::make('working_hours_ar')
                            ->label('أوقات العمل (عربي)')
                            ->placeholder('السبت – الخميس: 7 ص – 9 م'),
                        Forms\Components\TextInput::make('working_hours_en')
                            ->label('Working Hours (English)')
                            ->placeholder('Sat – Thu: 7 AM – 9 PM'),
                        Forms\Components\Textarea::make('address_ar')
                            ->label('العنوان (عربي)')
                            ->rows(2)
                            ->placeholder('معامل الشيخة للتحاليل الطبية — مصر'),
                        Forms\Components\Textarea::make('address_en')
                            ->label('Address (English)')
                            ->rows(2)
                            ->placeholder('El-Sheikha Lab, Egypt'),
                        Forms\Components\TextInput::make('branches_count')
                            ->label('عدد الفروع')
                            ->numeric()
                            ->default(4),
                    ])->columns(2),

                    Forms\Components\Tabs\Tab::make('السوشيال ميديا')->icon('heroicon-o-share')->schema([
                        Forms\Components\TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url()
                            ->placeholder('https://facebook.com/elsheikhalab')
                            ->prefixIcon('heroicon-o-globe-alt'),
                        Forms\Components\TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url()
                            ->placeholder('https://instagram.com/elsheikhalab')
                            ->prefixIcon('heroicon-o-globe-alt'),
                        Forms\Components\TextInput::make('whatsapp_url')
                            ->label('WhatsApp Link')
                            ->url()
                            ->placeholder('https://wa.me/201000000000')
                            ->prefixIcon('heroicon-o-chat-bubble-left-right'),
                        Forms\Components\TextInput::make('youtube_url')
                            ->label('YouTube')
                            ->url()
                            ->placeholder('https://youtube.com/@elsheikhalab')
                            ->prefixIcon('heroicon-o-globe-alt'),
                    ])->columns(2),

                    Forms\Components\Tabs\Tab::make('هوية الموقع')->icon('heroicon-o-building-office')->schema([
                        Forms\Components\TextInput::make('site_name_ar')
                            ->label('اسم الموقع (عربي)')
                            ->required()
                            ->placeholder('معامل الشيخة للتحاليل الطبية'),
                        Forms\Components\TextInput::make('site_name_en')
                            ->label('Site Name (English)')
                            ->required()
                            ->placeholder('El-Sheikha Lab'),
                        Forms\Components\TextInput::make('tagline_ar')
                            ->label('الشعار (عربي)')
                            ->placeholder('نتائج دقيقة، قرارات أسرع'),
                        Forms\Components\TextInput::make('tagline_en')
                            ->label('Tagline (English)')
                            ->placeholder('Accurate Results, Faster Decisions'),
                        Forms\Components\TextInput::make('founded_year')
                            ->label('سنة التأسيس')
                            ->placeholder('2010'),
                    ])->columns(2),

                    Forms\Components\Tabs\Tab::make('الإحصاءات والأرقام')->icon('heroicon-o-chart-bar')->schema([
                        Forms\Components\Section::make('أرقام الصفحة الرئيسية والصفحة التعريفية')
                            ->description('هذه الأرقام تظهر في أقسام الإحصاء على الصفحة الرئيسية وصفحة عن المعمل')
                            ->schema([
                                Forms\Components\TextInput::make('stat_tests_count')
                                    ->label('عدد التحاليل والباقات')
                                    ->placeholder('300+')
                                    ->helperText('يظهر في بطاقة الإحصاء بالصفحة الرئيسية'),
                                Forms\Components\TextInput::make('stat_analyses_done')
                                    ->label('إجمالي التحاليل المنجزة')
                                    ->placeholder('1M+')
                                    ->helperText('مثلاً: 1M+ أو +مليون'),
                                Forms\Components\TextInput::make('stat_avg_time')
                                    ->label('متوسط وقت الإنجاز')
                                    ->placeholder('24 h')
                                    ->helperText('يظهر في بطاقة الإحصاء'),
                                Forms\Components\TextInput::make('stat_total_analyses_milestone')
                                    ->label('حجم التحاليل الكلي (بانر)')
                                    ->placeholder('+1,000,000')
                                    ->helperText('يظهر في البانر الكبير بصفحة عن المعمل'),
                            ])->columns(2),
                    ]),

                ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $groups = [
            'hotline'         => 'contact',
            'whatsapp'        => 'contact',
            'email'           => 'contact',
            'working_hours_ar'=> 'contact',
            'working_hours_en'=> 'contact',
            'address_ar'      => 'contact',
            'address_en'      => 'contact',
            'branches_count'  => 'general',
            'facebook_url'    => 'social',
            'instagram_url'   => 'social',
            'whatsapp_url'    => 'social',
            'youtube_url'     => 'social',
            'site_name_ar'    => 'seo',
            'site_name_en'    => 'seo',
            'tagline_ar'      => 'seo',
            'tagline_en'      => 'seo',
            'founded_year'    => 'seo',
            'stat_tests_count'            => 'stats',
            'stat_analyses_done'          => 'stats',
            'stat_avg_time'               => 'stats',
            'stat_total_analyses_milestone' => 'stats',
        ];

        foreach ($state as $key => $value) {
            SiteSetting::set($key, $value, $groups[$key] ?? 'general');
        }

        SiteSetting::clearCache();

        Notification::make()
            ->title('✅ تم حفظ الإعدادات بنجاح')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('💾 حفظ الإعدادات')
                ->action('save')
                ->color('success')
                ->size('lg'),
        ];
    }
}
