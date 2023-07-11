<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Livewire\TemporaryUploadedFile;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProductResource\RelationManagers;
use Filament\Forms\Components\Select;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 1 ? 'primary' : 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make('Product')
                            ->schema([
                                Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->reactive()
                                            ->afterStateUpdated(function (Closure $set, $state) {
                                                $set('slug', Str::slug($state));
                                            })
                                            ->required()
                                            ->string()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(table: Product::class, ignoreRecord: true)
                                            ->maxLength(2048),
                                    ]),
                                Forms\Components\MarkdownEditor::make('description')
                                    ->required()
                                    ->string()
                                    ->maxLength(65535),
                            ]),
                        Section::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('buy_price')
                                    ->numeric()
                                    ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: 'Rp ', thousandsSeparator: ',', decimalPlaces: 2, isSigned: false))
                                    ->required(),
                                Forms\Components\TextInput::make('sell_price')
                                    ->numeric()
                                    ->mask(fn (TextInput\Mask $mask) => $mask->money(prefix: 'Rp ', thousandsSeparator: ',', decimalPlaces: 2, isSigned: false))
                                    ->required(),
                                Forms\Components\TextInput::make('stock')
                                    ->numeric()
                                    ->helperText('Jumlah produk yang tersedia')
                                    ->required(),
                            ])->columns(2),
                    ])->columnSpan(8),
                Grid::make()
                    ->schema([
                        Section::make('Image')
                            ->schema([
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->minSize(20)
                                    ->maxSize(1024)
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return (string) str($file->getClientOriginalName())->prepend(now()->timestamp . '-');
                                    })->directory('product-image'),
                            ]),
                        Section::make('Categories')
                            ->schema([
                                Select::make('categories')
                                    ->multiple()
                                    ->relationship('categories', 'name')
                                    ->preload()
                                    ->required()
                            ]),
                    ])->columnSpan(4),
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->size(80)
                    ->square()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->wrap()
                    ->sortable()
                    ->searchable(['name', 'description']),
                Tables\Columns\TagsColumn::make('categories.name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('buy_price')
                    ->formatStateUsing(fn (string $state): string => "Rp " . number_format($state, 0, '.', ',')),
                Tables\Columns\TextColumn::make('sell_price')
                    ->formatStateUsing(fn (string $state): string => "Rp " . number_format($state, 0, '.', ',')),
                Tables\Columns\TextColumn::make('stock'),
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y - H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date('d M Y - H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('updated_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
