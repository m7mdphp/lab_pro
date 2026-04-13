<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $modelLabel = 'سؤال شائع';
    protected static ?string $pluralModelLabel = 'الأسئلة الشائعة';
    protected static ?int $navigationSort = 5;

    public static array $translatableFields = ['question', 'answer'];

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Tabs')->tabs([

                Forms\Components\Tabs\Tab::make('English')->schema([
                    Forms\Components\Textarea::make('question_en')->label('Question (EN)')->rows(2)->required(),
                    Forms\Components\Textarea::make('answer_en')->label('Answer (EN)')->rows(4),
                ]),

                Forms\Components\Tabs\Tab::make('عربي')->schema([
                    Forms\Components\Textarea::make('question_ar')->label('السؤال (AR)')->rows(2),
                    Forms\Components\Textarea::make('answer_ar')->label('الإجابة (AR)')->rows(4),
                ]),

                Forms\Components\Tabs\Tab::make('إعدادات')->schema([
                    Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
                    Forms\Components\TextInput::make('category')->label('التصنيف')->default('general')->maxLength(100),
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
                Tables\Columns\TextColumn::make('category')->label('التصنيف')->badge(),
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
            'index'  => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit'   => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
