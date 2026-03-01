<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupModelResource\Pages;
use App\Filament\Resources\GroupModelResource\RelationManagers\ShoppingListsRelationManager;
use App\Filament\Resources\GroupModelResource\RelationManagers\UsersRelationManager;
use App\Models\GroupModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GroupModelResource extends Resource
{
    protected static ?string $model = GroupModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Покупки';
    protected static ?string $label = 'Группа';
    protected static ?string $pluralLabel = 'Группы';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основное')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Название')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('owner_id')
                            ->label('Владелец')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->label('Изображение')
                            ->image()
                            ->directory('tmp')
                            ->disk('local')
                            ->preserveFilenames(),

                        Forms\Components\TextInput::make('invite_link')
                            ->label('Invite ссылка')
                            ->disabled()
                            ->dehydrated(false)
                    ])->columns(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label(' ')
                    ->disk('public')
                    ->circular()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Владелец')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Участники')
                    ->sortable(),

                Tables\Columns\TextColumn::make('shoppingLists_count')
                    ->counts('shoppingLists')
                    ->label('Списки')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('owner_id')
                    ->label('Владелец')
                    ->relationship('owner', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
            ShoppingListsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroupModels::route('/'),
            'create' => Pages\CreateGroupModel::route('/create'),
            'view' => Pages\ViewGroupModel::route('/{record}'),
            'edit' => Pages\EditGroupModel::route('/{record}/edit'),
        ];
    }
}
