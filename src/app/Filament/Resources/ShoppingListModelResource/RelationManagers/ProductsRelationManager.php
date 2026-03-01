<?php

namespace App\Filament\Resources\ShoppingListModelResource\RelationManagers;

use App\Models\ProductModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    protected static ?string $title = 'Товары';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Название')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->label('Описание')
                ->rows(3)
                ->columnSpanFull(),

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
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
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
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Добавить товар'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
