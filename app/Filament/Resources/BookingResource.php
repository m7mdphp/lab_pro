<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'الحجوزات';
    protected static ?string $modelLabel = 'حجز';
    protected static ?string $pluralModelLabel = 'الحجوزات';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('بيانات العميل')->schema([
                Forms\Components\TextInput::make('name')->label('الاسم')->required(),
                Forms\Components\TextInput::make('phone')->label('الهاتف')->required(),
                Forms\Components\TextInput::make('email')->label('البريد الإلكتروني')->email(),
            ])->columns(3),

            Forms\Components\Section::make('تفاصيل الحجز')->schema([
                Forms\Components\Select::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending'   => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                        'done'      => 'منتهي',
                    ])
                    ->required(),
                Forms\Components\Select::make('package_id')
                    ->label('الباقة / التحليل')
                    ->relationship('package', 'slug')
                    ->searchable()
                    ->nullable(),
                Forms\Components\TextInput::make('test_name')->label('اسم التحليل (نص حر)')->nullable(),
                Forms\Components\Select::make('branch_id')
                    ->label('الفرع')
                    ->relationship('branch', 'slug')
                    ->searchable()
                    ->nullable(),
                Forms\Components\DatePicker::make('preferred_date')->label('التاريخ المفضل')->nullable(),
                Forms\Components\Textarea::make('notes')->label('ملاحظات')->nullable()->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable()->width(60),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('الهاتف')->searchable(),
                Tables\Columns\TextColumn::make('preferred_date')->label('التاريخ')->date('Y-m-d')->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'pending'   => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        'done'      => 'gray',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'pending'   => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                        'done'      => 'منتهي',
                        default     => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('وقت الحجز')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('الحالة')
                    ->options([
                        'pending'   => 'قيد الانتظار',
                        'confirmed' => 'مؤكد',
                        'cancelled' => 'ملغي',
                        'done'      => 'منتهي',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل'),
                Tables\Actions\DeleteAction::make()->label('حذف'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookings::route('/'),
            'edit'   => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
