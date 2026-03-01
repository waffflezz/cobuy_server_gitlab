<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductModelResource\Pages;
use App\Models\ProductModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductModelResource extends Resource
{
    protected static ?string $model = ProductModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Покупки';
    protected static ?string $label = 'Товар';
    protected static ?string $pluralLabel = 'Товары';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Товар')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Название')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                        ->label('Описание')
                        ->rows(3)
                        ->columnSpanFull(),

                    Forms\Components\Select::make('shopping_list_id')
                        ->label('Список')
                        ->relationship('shoppingList', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Select::make('status')
                        ->label('Статус')
                        ->required()
                        ->options([
                            ProductModel::NONE_STATUS => 'Не задан',
                            ProductModel::PLANNED_STATUS => 'Запланировано',
                            ProductModel::BUY_STATUS => 'Куплено',
                        ])
                        ->default(ProductModel::PLANNED_STATUS),

                    Forms\Components\TextInput::make('count')
                        ->label('Кол-во')
                        ->numeric()
                        ->minValue(1)
                        ->default(1),

                    Forms\Components\TextInput::make('price')
                        ->label('Цена')
                        ->numeric()
                        ->minValue(0)
                        ->prefix('₽'),

                    Forms\Components\Select::make('buyer_id')
                        ->label('Покупатель')
                        ->relationship('buyer', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Forms\Components\FileUpload::make('image')
                        ->label('Изображение')
                        ->image()
                        ->disk('public')
                        ->directory('products')
                        ->imageEditor()
                        ->maxSize(4096),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(' ')
                    ->disk('public')
                    ->square()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('shoppingList.name')
                    ->label('Список')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->formatStateUsing(fn (?int $state) => match ($state) {
                        ProductModel::BUY_STATUS => 'Куплено',
                        ProductModel::PLANNED_STATUS => 'Запланировано',
                        default => 'Не задан',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('count')
                    ->label('Кол-во')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('rub', true)
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('buyer.name')
                    ->label('Покупатель')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        ProductModel::NONE_STATUS => 'Не задан',
                        ProductModel::PLANNED_STATUS => 'Запланировано',
                        ProductModel::BUY_STATUS => 'Куплено',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductModels::route('/'),
            'create' => Pages\CreateProductModel::route('/create'),
            'view' => Pages\ViewProductModel::route('/{record}'),
            'edit' => Pages\EditProductModel::route('/{record}/edit'),
        ];
    }
}
