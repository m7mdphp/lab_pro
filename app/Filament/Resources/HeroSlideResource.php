<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HeroSlideResource\Pages;
use App\Models\HeroSlide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;

    protected static ?string $navigationIcon  = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'الإعدادات';
    protected static ?string $navigationLabel = 'سلايدر الرئيسية';
    protected static ?string $modelLabel      = 'سلايد';
    protected static ?string $pluralModelLabel = 'السلايدر';
    protected static ?int    $navigationSort  = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\Section::make('الصورة')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('صورة السلايد')
                        ->disk('public')
                        ->directory('hero-slides')
                        ->image()
                        ->imageEditor()
                        ->maxSize(5120)
                        ->nullable()
                        ->helperText('ارفع صورة من جهازك (حتى 5 ميجابايت). للصور الخارجية، أدخل الرابط في الحقل أدناه واتركه فارغاً.')
                        ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, $record) {
                            // If stored value is an HTTP URL (not a file path), clear the FileUpload
                            if ($record && $record->image && str_starts_with($record->image, 'http')) {
                                $component->state(null);
                            }
                        }),

                    Forms\Components\TextInput::make('image_url_override')
                        ->label('أو: رابط URL للصورة')
                        ->placeholder('https://images.unsplash.com/...')
                        ->helperText('إذا أدخلت رابطاً هنا سيُستخدم بدلاً من الصورة المرفوعة.')
                        ->nullable()
                        ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                            if ($record && $record->image && str_starts_with($record->image, 'http')) {
                                $component->state($record->image);
                            }
                        }),
                ]),

            Forms\Components\Section::make('النصوص — عربي')
                ->schema([
                    Forms\Components\TextInput::make('title_ar')
                        ->label('العنوان (عربي)')
                        ->helperText('يمكن إضافة <br> لسطر جديد')
                        ->nullable(),
                    Forms\Components\Textarea::make('subtitle_ar')
                        ->label('الوصف (عربي)')
                        ->rows(2)
                        ->nullable(),
                ])
                ->columns(1),

            Forms\Components\Section::make('Texts — English')
                ->schema([
                    Forms\Components\TextInput::make('title_en')
                        ->label('Title (English)')
                        ->helperText('You can use <br> for line breaks')
                        ->nullable(),
                    Forms\Components\Textarea::make('subtitle_en')
                        ->label('Subtitle (English)')
                        ->rows(2)
                        ->nullable(),
                ])
                ->columns(1),

            Forms\Components\Section::make('زر الحجز')
                ->schema([
                    Forms\Components\TextInput::make('button_text_ar')
                        ->label('نص الزر (عربي)')
                        ->default('احجز الآن')
                        ->nullable(),
                    Forms\Components\TextInput::make('button_text_en')
                        ->label('Button Text (English)')
                        ->default('Book Now')
                        ->nullable(),
                    Forms\Components\TextInput::make('button_url')
                        ->label('رابط الزر')
                        ->default('/booking')
                        ->helperText('رابط كامل أو مسار نسبي مثل /booking')
                        ->nullable(),
                ])
                ->columns(2),

            Forms\Components\Section::make('الإعدادات')
                ->schema([
                    Forms\Components\TextInput::make('sort_order')
                        ->label('الترتيب')
                        ->numeric()
                        ->default(0)
                        ->helperText('الأصغر يظهر أولاً'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('نشط')
                        ->default(true)
                        ->inline(false),
                ])
                ->columns(2),

        ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return static::resolveImageField($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return static::resolveImageField($data);
    }

    /**
     * If the user typed a URL in the override field, use it as the image value.
     */
    protected static function resolveImageField(array $data): array
    {
        $urlOverride = $data['image_url_override'] ?? null;
        if ($urlOverride && str_starts_with($urlOverride, 'http')) {
            $data['image'] = $urlOverride;
        }
        unset($data['image_url_override']);
        return $data;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('الصورة')
                    ->width(80)
                    ->height(50)
                    ->defaultImageUrl('https://images.unsplash.com/photo-1579154204601-01588f351e67?w=200&q=60')
                    ->state(fn (HeroSlide $record): string => $record->image_url),

                Tables\Columns\TextColumn::make('title_ar')
                    ->label('العنوان')
                    ->limit(40)
                    ->html(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('الترتيب')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تعديل')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListHeroSlides::route('/'),
            'create' => Pages\CreateHeroSlide::route('/create'),
            'edit'   => Pages\EditHeroSlide::route('/{record}/edit'),
        ];
    }
}
