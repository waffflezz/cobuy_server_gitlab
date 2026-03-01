<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShoppingListModelResource\Pages;
use App\Filament\Resources\ShoppingListModelResource\RelationManagers\ProductsRelationManager;
use App\Models\ShoppingListModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShoppingListModelResource extends Resource
{
    protected static ?string $model = ShoppingListModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Покупки';
    protected static ?string $label = 'Список';
    protected static ?string $pluralLabel = 'Списки';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Список покупок')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Название')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('group_id')
                        ->label('Группа')
                        ->relationship('group', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),

                    Forms\Components\Toggle::make('hidden')
                        ->label('Скрытый')
                        ->default(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('group.name')
                    ->label('Группа')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('hidden')
                    ->label('Скрыт')
                    ->boolean(),

                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Товаров')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('hidden')
                    ->label('Скрыт'),
                Tables\Filters\SelectFilter::make('group_id')
                    ->label('Группа')
                    ->relationship('group', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShoppingListModels::route('/'),
            'create' => Pages\CreateShoppingListModel::route('/create'),
            'view' => Pages\ViewShoppingListModel::route('/{record}'),
            'edit' => Pages\EditShoppingListModel::route('/{record}/edit'),
        ];
    }
}
