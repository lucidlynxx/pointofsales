<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Sale;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SaleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SaleResource\RelationManagers;
use App\Models\Product;
use Closure;

class SaleResource extends Resource
{
    protected static ?string $model = Sale::class;

    protected static ?string $navigationIcon = 'heroicon-o-switch-horizontal';

    protected static ?string $navigationGroup = 'Transaction';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Sale details')
                            ->schema([
                                Forms\Components\TextInput::make('invoice')
                                    ->default('INV-' . strtoupper(Str::random(10)))
                                    ->disabled()
                                    ->required(),
                                Forms\Components\TextInput::make('name')
                                    ->placeholder(auth()->user()->name)
                                    ->disabled()
                                    ->dehydrated(false)
                            ])->columns(2),
                        Forms\Components\Section::make('Sale items')
                            ->schema([
                                Forms\Components\Repeater::make('saleItems')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Product')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, callable $set, Closure $get) {
                                                $product = Product::find($state);
                                                if ($product) {
                                                    $set('price', $product->sell_price);
                                                    $prices = $get('../../saleItems');
                                                    $values = collect($prices)->pluck('price');

                                                    $set('../../total', $values->sum());
                                                }
                                            })
                                            ->searchable()
                                            ->getSearchResultsUsing(
                                                fn (string $search) => Product::where('name', 'like', "%{$search}%")
                                                    ->orWhere('barcode', 'like', "%{$search}%")
                                                    ->limit(25)
                                                    ->pluck('name', 'id')
                                            )
                                            ->getOptionLabelUsing(fn ($value): ?string => Product::find($value)?->name)
                                            ->required()
                                            ->columnSpan(5),
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Qty')
                                            ->reactive()
                                            ->afterStateUpdated(function ($state, Closure $set, Closure $get) {
                                                $product = Product::find($get('product_id'));
                                                if ($product) {
                                                    $set('price', $state * $product->sell_price);
                                                    $prices = $get('../../saleItems');
                                                    $values = collect($prices)->pluck('price');

                                                    $set('../../total', $values->sum());
                                                }
                                            })
                                            ->numeric()
                                            ->default(1)
                                            ->required()
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('price')
                                            ->disabled()
                                            ->required()
                                            ->columnSpan(3)
                                    ])
                                    ->reactive()
                                    ->defaultItems(1)
                                    ->columns(10)
                                    ->columnSpan('full')
                                    ->label('')
                                    ->createItemButtonLabel('Add sale')
                            ])->collapsible(),
                        Forms\Components\Section::make('Payment')
                            ->schema([
                                Forms\Components\TextInput::make('total')
                                    ->reactive()
                                    ->disabled()
                                    ->required(),
                                Forms\Components\TextInput::make('discount')
                                    ->mask(
                                        fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->thousandsSeparator(',')
                                    )
                                    ->debounce('800ms')
                                    ->afterStateUpdated(function ($state, Closure $set, Closure $get) {
                                        $prices = $get('saleItems');
                                        $values = collect($prices)->pluck('price');

                                        $set('total', $values->sum() - $state);
                                    })
                                    ->numeric(),
                                Forms\Components\TextInput::make('payment')
                                    ->mask(
                                        fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->thousandsSeparator(',')
                                    )
                                    ->debounce('800ms')
                                    ->afterStateUpdated(function ($state, Closure $set, Closure $get) {
                                        $set('change', str_replace('-', '', strval($get('total') - $state)));
                                    })
                                    ->gte('total')
                                    ->numeric()
                                    ->required(),
                                Forms\Components\TextInput::make('change')
                                    ->numeric()
                                    ->disabled(),
                            ])->columns(2)
                    ])->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date('d M Y - H:i:s')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('invoice'),
                Tables\Columns\TextColumn::make('total')
                    ->formatStateUsing(fn (string $state): string => "Rp " . number_format($state, 0, '.', ',')),
                Tables\Columns\TextColumn::make('discount')
                    ->formatStateUsing(function ($state) {
                        if ($state === null) {
                            return "Rp 0";
                        }
                        return "Rp " . number_format($state, 0, '.', ',');
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment')
                    ->formatStateUsing(fn (string $state): string => "Rp " . number_format($state, 0, '.', ','))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('change')
                    ->formatStateUsing(fn (string $state): string => "Rp " . number_format($state, 0, '.', ','))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date('d M Y - H:i')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('updated_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSale::route('/create'),
            'edit' => Pages\EditSale::route('/{record}/edit'),
        ];
    }
}
