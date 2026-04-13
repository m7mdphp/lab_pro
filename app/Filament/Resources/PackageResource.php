<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $modelLabel = 'باقة / تحليل';
    protected static ?string $pluralModelLabel = 'الباقات والتحاليل';
    protected static ?int $navigationSort = 2;

    /** Fields synced to the translations table */
    public static array $translatableFields = ['name', 'short_description', 'description'];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')->tabs([

                Forms\Components\Tabs\Tab::make('English')->schema([
                    Forms\Components\TextInput::make('name_en')
                        ->label('Name (EN)')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('short_description_en')
                        ->label('Short Description (EN)')
                        ->rows(2),
                    Forms\Components\RichEditor::make('description_en')
                        ->label('Full Description (EN)')
                        ->fileAttachmentsDisk('public'),
                ]),

                Forms\Components\Tabs\Tab::make('عربي')->schema([
                    Forms\Components\TextInput::make('name_ar')
                        ->label('الاسم (AR)')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('short_description_ar')
                        ->label('وصف مختصر (AR)')
                        ->rows(2),
                    Forms\Components\RichEditor::make('description_ar')
                        ->label('وصف كامل (AR)')
                        ->fileAttachmentsDisk('public'),
                ]),

                Forms\Components\Tabs\Tab::make('إعدادات')->schema([
                    Forms\Components\TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\TextInput::make('price')
                        ->label('السعر (جنيه)')
                        ->numeric()
                        ->step(0.01)
                        ->helperText('يُخزَّن داخلياً بالقروش (×100)')
                        ->afterStateHydrated(fn($state, callable $set) => $set('price', $state ? round($state / 100, 2) : null))
                        ->dehydrateStateUsing(fn($state) => $state !== null ? (int) round($state * 100) : null),
                    Forms\Components\TextInput::make('original_price')
                        ->label('السعر الأصلي (جنيه)')
                        ->numeric()
                        ->step(0.01)
                        ->afterStateHydrated(fn($state, callable $set) => $set('original_price', $state ? round($state / 100, 2) : null))
                        ->dehydrateStateUsing(fn($state) => $state !== null ? (int) round($state * 100) : null),
                    Forms\Components\TextInput::make('sample_type')->label('نوع العينة')->maxLength(100),
                    Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->label('نشط')->default(false),
                    Forms\Components\Toggle::make('is_featured')->label('مميز')->default(false),
                ])->columns(2),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable()->width(60),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('price')
                    ->label('السعر')
                    ->formatStateUsing(fn($state) => $state ? number_format($state / 100, 0) . ' جنيه' : '—')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('نشط')->boolean(),
                Tables\Columns\IconColumn::make('is_featured')->label('مميز')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('آخر تعديل')->since()->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('نشط'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('مميز'),
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
            'index'  => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit'   => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
