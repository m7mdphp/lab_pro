<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $navigationIcon  = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $modelLabel      = 'مقال';
    protected static ?string $pluralModelLabel = 'المدونة الطبية';
    protected static ?int    $navigationSort  = 7;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')->tabs([

                // ── Arabic content ────────────────────────────────────
                Forms\Components\Tabs\Tab::make('المحتوى العربي')->icon('heroicon-o-language')->schema([
                    Forms\Components\TextInput::make('title_ar')
                        ->label('العنوان (عربي)')
                        ->required()
                        ->maxLength(255)
                        ->live(debounce: 600)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', Str::slug($state))),
                    Forms\Components\Textarea::make('excerpt_ar')
                        ->label('مقتطف / وصف مختصر (عربي)')
                        ->rows(3)->maxLength(500),
                    Forms\Components\RichEditor::make('content_ar')
                        ->label('المحتوى الكامل (عربي)')
                        ->fileAttachmentsDisk('public')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('category_ar')->label('التصنيف (عربي)')->maxLength(100)
                        ->placeholder('صحة القلب، السكري، الغدة الدرقية...'),
                    Forms\Components\TextInput::make('author_ar')->label('الكاتب (عربي)')->maxLength(100),
                ])->columns(2),

                // ── English content ───────────────────────────────────
                Forms\Components\Tabs\Tab::make('English Content')->icon('heroicon-o-language')->schema([
                    Forms\Components\TextInput::make('title_en')
                        ->label('Title (EN)')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('excerpt_en')
                        ->label('Excerpt / Short Description (EN)')
                        ->rows(3)->maxLength(500),
                    Forms\Components\RichEditor::make('content_en')
                        ->label('Full Content (EN)')
                        ->fileAttachmentsDisk('public')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('category_en')->label('Category (EN)')->maxLength(100)
                        ->placeholder('Heart Health, Diabetes, Thyroid...'),
                    Forms\Components\TextInput::make('author_en')->label('Author (EN)')->maxLength(100),
                ])->columns(2),

                // ── Media ─────────────────────────────────────────────
                Forms\Components\Tabs\Tab::make('الصورة والصوت')->icon('heroicon-o-photo')->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->label('صورة المقال الرئيسية')
                        ->image()
                        ->disk('public')
                        ->directory('posts')
                        ->imageResizeMode('cover')
                        ->imageCropAspectRatio('16:9')
                        ->imageResizeTargetWidth('1200')
                        ->imageResizeTargetHeight('675')
                        ->columnSpanFull(),
                    Forms\Components\Section::make('🎧 ملف صوتي (اختياري)')->description('ارفع ملف صوتي أو أدخل رابطاً خارجياً لتشغيله مع المقال')->columns(2)->schema([
                        Forms\Components\FileUpload::make('audio_file')
                            ->label('رفع ملف صوتي (MP3/WAV)')
                            ->disk('public')
                            ->directory('posts/audio')
                            ->acceptedFileTypes(['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg'])
                            ->maxSize(50 * 1024), // 50 MB
                        Forms\Components\TextInput::make('audio_url')
                            ->label('رابط صوتي خارجي')
                            ->url()
                            ->placeholder('https://soundcloud.com/...')
                            ->helperText('يُستخدم إذا لم يُرفع ملف. يأخذ الأولوية على الملف المرفوع.'),
                    ]),
                ]),

                // ── Settings ──────────────────────────────────────────
                Forms\Components\Tabs\Tab::make('إعدادات')->icon('heroicon-o-cog-6-tooth')->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('يُملأ تلقائياً من العنوان العربي'),
                    Forms\Components\TextInput::make('read_time')
                        ->label('وقت القراءة (دقائق)')
                        ->numeric()
                        ->default(3)
                        ->minValue(1)->maxValue(60),
                    Forms\Components\Toggle::make('is_published')
                        ->label('منشور')
                        ->default(false),
                    Forms\Components\DateTimePicker::make('published_at')
                        ->label('تاريخ النشر')
                        ->default(now()),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('الترتيب')
                        ->numeric()->default(0),
                ])->columns(2),

                // ── SEO ───────────────────────────────────────────────
                Forms\Components\Tabs\Tab::make('SEO')->icon('heroicon-o-magnifying-glass')->schema([
                    Forms\Components\TextInput::make('seo_title_ar')->label('عنوان SEO (عربي)')->maxLength(70),
                    Forms\Components\TextInput::make('seo_title_en')->label('SEO Title (EN)')->maxLength(70),
                    Forms\Components\Textarea::make('seo_description_ar')->label('وصف SEO (عربي)')->rows(2)->maxLength(160),
                    Forms\Components\Textarea::make('seo_description_en')->label('SEO Description (EN)')->rows(2)->maxLength(160),
                ])->columns(2),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->disk('public')
                    ->label('صورة')
                    ->width(60)->height(40)
                    ->defaultImageUrl(asset('images/logo.png')),
                Tables\Columns\TextColumn::make('title_ar')->label('العنوان')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('category_ar')->label('التصنيف')->badge()->color('success'),
                Tables\Columns\IconColumn::make('is_published')->label('منشور')->boolean(),
                Tables\Columns\TextColumn::make('published_at')->label('تاريخ النشر')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('read_time')->label('وقت القراءة')->suffix(' د')->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('الحالة')
                    ->trueLabel('منشور فقط')->falseLabel('مسودة فقط'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
