<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamMemberResource\Pages;
use App\Models\TeamMember;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeamMemberResource extends Resource
{
    protected static ?string $model = TeamMember::class;
    protected static ?string $navigationIcon  = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'المحتوى';
    protected static ?string $modelLabel      = 'عضو فريق';
    protected static ?string $pluralModelLabel = 'فريق العمل';
    protected static ?int    $navigationSort  = 8;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('الصورة الشخصية')->schema([
                Forms\Components\FileUpload::make('image')
                    ->label('الصورة')
                    ->image()
                    ->disk('public')
                    ->directory('team')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('400')
                    ->imageResizeTargetHeight('400')
                    ->columnSpanFull(),
            ])->columns(1)->columnSpan(1),

            Forms\Components\Section::make('البيانات الأساسية')->schema([
                Forms\Components\TextInput::make('name_ar')->label('الاسم (عربي)')->required()->maxLength(150),
                Forms\Components\TextInput::make('name_en')->label('Name (EN)')->maxLength(150),
                Forms\Components\TextInput::make('title_ar')->label('المسمى الوظيفي (عربي)')->maxLength(150)
                    ->placeholder('استشاري أمراض الدم'),
                Forms\Components\TextInput::make('title_en')->label('Job Title (EN)')->maxLength(150)
                    ->placeholder('Hematology Consultant'),
                Forms\Components\TextInput::make('specialty_ar')->label('التخصص (عربي)')->maxLength(150),
                Forms\Components\TextInput::make('specialty_en')->label('Specialty (EN)')->maxLength(150),
                Forms\Components\Textarea::make('bio_ar')->label('السيرة الذاتية (عربي)')->rows(3),
                Forms\Components\Textarea::make('bio_en')->label('Bio (EN)')->rows(3),
                Forms\Components\TextInput::make('linkedin_url')->label('LinkedIn URL')->url()->maxLength(255),
                Forms\Components\TextInput::make('sort_order')->label('الترتيب')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->label('نشط')->default(true),
            ])->columns(2)->columnSpan(2),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->label('الصورة')
                    ->circular()
                    ->width(48)->height(48),
                Tables\Columns\TextColumn::make('name_ar')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('title_ar')->label('المسمى الوظيفي'),
                Tables\Columns\TextColumn::make('specialty_ar')->label('التخصص')->badge()->color('info'),
                Tables\Columns\IconColumn::make('is_active')->label('نشط')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->label('ترتيب')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
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
            'index'  => Pages\ListTeamMembers::route('/'),
            'create' => Pages\CreateTeamMember::route('/create'),
            'edit'   => Pages\EditTeamMember::route('/{record}/edit'),
        ];
    }
}
