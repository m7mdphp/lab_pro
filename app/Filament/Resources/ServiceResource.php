<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $modelLabel = 'خدمة';
    protected static ?string $pluralModelLabel = 'الخدمات';
    protected static ?int $navigationSort = 6;

    public static array $translatableFields = ['name', 'short_description', 'description'];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')->tabs([

                Forms\Components\Tabs\Tab::make('English')->schema([
                    Forms\Components\TextInput::make('name_en')->label('Name (EN)')->maxLength(255),
                    Forms\Components\Textarea::make('short_description_en')->label('Short Description (EN)')->rows(2),
                    Forms\Components\RichEditor::make('description_en')
                        ->label('Full Description (EN)')
                        ->fileAttachmentsDisk('public'),
                    Forms\Components\Repeater::make('features_en')
                        ->label('Features / Benefits (EN)')
                        ->schema([
                            Forms\Components\TextInput::make('item')->label('Feature')->required(),
                        ])
                        ->addActionLabel('Add Feature')
                        ->collapsible()
                        ->afterStateHydrated(function ($state, callable $set, $record) {
                            if ($record) {
                                $trans = $record->translations()->where('locale', 'en')->first();
                                $features = $trans?->features ?? [];
                                $set('features_en', collect($features)->map(fn($f) => ['item' => $f])->toArray());
                            }
                        })
                        ->dehydrated(false),
                ]),

                Forms\Components\Tabs\Tab::make('عربي')->schema([
                    Forms\Components\TextInput::make('name_ar')->label('الاسم (AR)')->maxLength(255),
                    Forms\Components\Textarea::make('short_description_ar')->label('وصف مختصر (AR)')->rows(2),
                    Forms\Components\RichEditor::make('description_ar')
                        ->label('وصف كامل (AR)')
                        ->fileAttachmentsDisk('public'),
                    Forms\Components\Repeater::make('features_ar')
                        ->label('المميزات / الفوائد (AR)')
                        ->schema([
                            Forms\Components\TextInput::make('item')->label('ميزة')->required(),
                        ])
                        ->addActionLabel('إضافة ميزة')
                        ->collapsible()
                        ->afterStateHydrated(function ($state, callable $set, $record) {
                            if ($record) {
                                $trans = $record->translations()->where('locale', 'ar')->first();
                                $features = $trans?->features ?? [];
                                $set('features_ar', collect($features)->map(fn($f) => ['item' => $f])->toArray());
                            }
                        })
                        ->dehydrated(false),
                ]),

                Forms\Components\Tabs\Tab::make('إعدادات')->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100),
                    Forms\Components\TextInput::make('icon')
                        ->label('Icon (heroicon name)')
                        ->placeholder('heroicon-o-user')
                        ->maxLength(100),
                    Forms\Components\TextInput::make('color')
                        ->label('Color class')
                        ->placeholder('green')
                        ->maxLength(50),
                    Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->label('نشط')->default(true),
                ])->columns(2),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable()->width(60),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->searchable(),
                Tables\Columns\TextColumn::make('icon')->label('أيقونة'),
                Tables\Columns\IconColumn::make('is_active')->label('نشط')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('الترتيب')->sortable(),
            ])
            ->defaultSort('sort_order')
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
            'index'  => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit'   => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
