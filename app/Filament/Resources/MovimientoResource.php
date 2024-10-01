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

    public static function calculateTotal($state, $get, $set): void
    {
            $total = $get('precio')  * $get('cantidad') ;
        //dd($get('precio') , $get('cantidad'), $total);
            $set('total', number_format($total, 2,'.',''));

    }
    public static function calculateCantidad( $get, $set): void
    {

        if ($get('tipo') === 'Salidas') {
            $cantidad = $get('capas') * ($get('superficie') / $get('rendimiento'));
        } else {
            $cantidad = 1; // Ejemplo de valor por defecto
        }
        $set('cantidad', number_format($cantidad, 2,'.',''));

    }

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
                                ->default('Salidas')
                                ->live()
                                ->columnSpanFull()
                                ->options([
                                    'Entradas' => 'Entradas',
                                    'Salidas' => 'Salidas',
                                ])
                                ->colors([
                                    'Compras' => 'primary',
                                    'Entradas' => 'success',
                                ])
                                ->icons([
                                    'Salidas' => 'heroicon-m-sparkles',
                                    'Entradas' => 'heroicon-m-arrow-path',

                                ]),
                            Forms\Components\TextInput::make('origen')
                                ->live()
                                ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Entradas')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('destino')
                                ->live()
                                ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('product_id')
                                ->relationship('product', 'name')
                                ->required()
                                ->live()
                                ->preload()
                                ->columnSpanFull()
                                ->createOptionForm(
                                    Product::getForm()
                                )
                                ->afterStateUpdated(
                                    fn($state, Forms\Set $set) => [
                                        $set('descripcion', Product::find($state)?->description ?? 'li'),

                                        $set('medida', Product::find($state)?->medida ?? 'li'),
                                        $set('rendimiento', Product::find($state)?->rendimiento ?? '1'),
                                        $set('precio', Product::find($state)?->price ?? 0),
                                        $set('stock', Product::find($state)?->stock() ?? 1),
                                        $set('superficie', 1),

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
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {
                                    self::calculateCantidad( $get, $set);
                                }
                            )
                            ->numeric(),
                        Forms\Components\TextInput::make('rendimiento')
                            ->prefix('kg/M2')
                            ->live(debounce: 500)
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {
                                    self::calculateCantidad( $get, $set);
                                    self::calculateTotal($state, $get, $set);
                                }
                            )
                            ->numeric(),
                        Forms\Components\TextInput::make('superficie')
                            ->required()
                            ->default(1)
                            ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                            ->live()
                            ->prefix('M2')
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {
                                    self::calculateCantidad( $get, $set);
                                    self::calculateTotal($state, $get, $set);
                                }
                            )
                            ->numeric(),

                        Forms\Components\TextInput::make('precio')
                            ->live( debounce: 500)
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {

                                    self::calculateTotal($state, $get, $set);
                                }
                            )
                            ->numeric()
                            ->prefix('EUR'),

                        Forms\Components\TextInput::make('cantidad')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->live( debounce: 500)

                            ->prefix(function ( Forms\Get $get){
                                return match ($get('tipo')) {
                                    'Entradas' => 'Kilos',
                                    'Salidas' => 'Kilos',
                                    default => 'Kilos',
                                };
                            }
                            )
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {

                                    self::calculateTotal($state, $get, $set);
                                }
                            ),

                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->default(0)
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
