<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestCategoryResource\Pages;
use App\Models\TestCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestCategoryResource extends Resource
{
    protected static ?string $model = TestCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $modelLabel = 'قسم تحاليل';
    protected static ?string $pluralModelLabel = 'أقسام التحاليل';
    protected static ?int $navigationSort = 4;

    public static array $translatableFields = ['name', 'description'];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')->tabs([

                Forms\Components\Tabs\Tab::make('English')->schema([
                    Forms\Components\TextInput::make('name_en')->label('Name (EN)')->maxLength(255)->required(),
                    Forms\Components\Textarea::make('description_en')->label('Description (EN)')->rows(3),
                ]),

                Forms\Components\Tabs\Tab::make('عربي')->schema([
                    Forms\Components\TextInput::make('name_ar')->label('الاسم (AR)')->maxLength(255),
                    Forms\Components\Textarea::make('description_ar')->label('الوصف (AR)')->rows(3),
                ]),

                Forms\Components\Tabs\Tab::make('إعدادات')->schema([
                    Forms\Components\TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                    Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
                    Forms\Components\Toggle::make('is_active')->label('نشط')->default(true),
                ])->columns(3),

            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable()->width(60),
                Tables\Columns\TextColumn::make('slug')->label('Slug')->searchable(),
                Tables\Columns\TextColumn::make('sort_order')->label('الترتيب')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('نشط')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('آخر تعديل')->since()->sortable(),
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
            'index'  => Pages\ListTestCategories::route('/'),
            'create' => Pages\CreateTestCategory::route('/create'),
            'edit'   => Pages\EditTestCategory::route('/{record}/edit'),
        ];
    }
}
