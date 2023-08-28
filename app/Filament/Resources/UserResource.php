<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Account';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->required()
                            ->unique(table: User::class, ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(table: User::class, ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')
                            ->preload(),
                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (Page $livewire) => ($livewire instanceof CreateUser))
                            ->maxLength(255)
                            ->confirmed()
                            ->hiddenOn('edit'),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (Page $livewire) => ($livewire instanceof CreateUser))
                            ->maxLength(255)
                            ->hiddenOn('edit'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name and Email')
                    ->sortable(['name', 'email'])
                    ->searchable(['name', 'email'])
                    ->description(fn (User $record): string => $record->email),
                Tables\Columns\TextColumn::make('username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y - H:i')
                    ->sortable()
            ])->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
