<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'الحجوزات';
    protected static ?string $modelLabel = 'رسالة';
    protected static ?string $pluralModelLabel = 'رسائل التواصل';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $count = ContactMessage::unread()->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->label('الاسم')->disabled(),
            Forms\Components\TextInput::make('phone')->label('الهاتف')->disabled(),
            Forms\Components\TextInput::make('email')->label('البريد')->disabled(),
            Forms\Components\Textarea::make('message')->label('الرسالة')->disabled()->rows(5)->columnSpanFull(),
            Forms\Components\Toggle::make('is_read')->label('تمت القراءة'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable()->width(60),
                Tables\Columns\IconColumn::make('is_read')->label('مقروءة')->boolean(),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable(),
                Tables\Columns\TextColumn::make('phone')->label('الهاتف'),
                Tables\Columns\TextColumn::make('email')->label('البريد')->searchable()->toggleable(),
                Tables\Columns\TextColumn::make('message')->label('الرسالة')->limit(60)->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label('التاريخ')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_read')->label('مقروءة'),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_read')
                    ->label('تعيين كمقروءة')
                    ->icon('heroicon-o-check')
                    ->visible(fn(ContactMessage $record) => !$record->is_read)
                    ->action(fn(ContactMessage $record) => $record->markAsRead()),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListContactMessages::route('/'),
            'view'  => Pages\ViewContactMessage::route('/{record}'),
        ];
    }
}
