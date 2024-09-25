<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MovimientoResource\Pages;
use App\Filament\Resources\MovimientoResource\RelationManagers;
use App\Models\Movimiento;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make([

                    Forms\Components\Section::make('Información del Producto')->schema(
                        [
                            Forms\Components\Hidden::make('user_id')
                                ->default(auth()->id())
                            ,
                            Forms\Components\ToggleButtons::make('tipo')
                                ->inline()
                                ->default('Ventas')
                                ->columnSpanFull()
                                ->options([
                                    'Compras' => 'Compras',
                                    'Ventas' => 'Ventas',
                                ])
                                ->colors([
                                    'Compras' => 'primary',
                                    'Ventas' => 'success',
                                ])
                                ->icons([
                                    'Ventas' => 'heroicon-m-sparkles',
                                    'Compras' => 'heroicon-m-arrow-path',

                                ]),
                            Forms\Components\TextInput::make('origen')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('destino')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('product_id')
                                ->relationship('product', 'name')
                                ->required()
                                ->live(onBlur: true)
                                ->preload()
                                ->columnSpanFull()
                                ->afterStateUpdated(
                                    fn($state, Forms\Set $set) => [
                                        $set('descripcion', Product::find($state)?->description ?? 'li'),

                                        $set('medida', Product::find($state)?->medida ?? 'li'),
                                        $set('rendimiento', Product::find($state)?->rendimiento ?? '1'),
                                        $set('precio', Product::find($state)?->price ?? 1),
                                        $set('stock', Product::find($state)?->stock() ?? 1),
                                    ]
                                ),
                            Forms\Components\TextInput::make('stock')
                                ->readOnly(true),
                            Forms\Components\TextInput::make('medida')
                                ->readOnly(true),
                            Forms\Components\TextInput::make('descripcion')
                                ->columnSpanFull(),
                        ])->columns(2),

                ])->columnSpan(2),

                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Datos del producto')->schema([

                        Forms\Components\TextInput::make('capas')
                            ->default(1)
                            ->numeric(),
                        Forms\Components\TextInput::make('rendimiento')
                            ->prefix('M2/L')
                            ->numeric(),
                        Forms\Components\TextInput::make('superficie')
                            ->required()
                            ->live(onBlur: true)
                            ->prefix('M2')
                            ->afterStateUpdated(
                                fn($state, Forms\Get $get, Set $set) => [
                                    $set('cantidad', ($state * $get('capas')) / $get('rendimiento')),
                                    $set('total', round($get('precio') * $get('cantidad'), 2))
                                ]
                            )
                            ->numeric(),

                        Forms\Components\TextInput::make('precio')
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn($state, Forms\Get $get, Set $set) => [
                                    $set('total', round($state * $get('cantidad'), 2))
                                ]
                            )
                            ->numeric()
                            ->required()
                            ->prefix('EUR'),

                        Forms\Components\TextInput::make('cantidad')
                            ->numeric()
                            ->required()
                            ->prefix('Litros'),


                        //Cantidad de pintura necesaria: (10 m² * 2 capas) / 10 m²/L = 2 litros
                        // superficie x capas / rendimiento

                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->required()
                            ->disabled()
                            ->dehydrated()
                        ,

                    ]),
                ]),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('origen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('capas')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rendimiento')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('superficie')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('medida')
                    ->searchable(),
                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destino')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovimientos::route('/'),
            'create' => Pages\CreateMovimiento::route('/create'),
            'edit' => Pages\EditMovimiento::route('/{record}/edit'),
        ];
    }
}
