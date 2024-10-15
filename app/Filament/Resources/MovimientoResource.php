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

class MovimientoResource extends Resource
{
    protected static ?string $model = Movimiento::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function calculateTotal($state, $get, $set): void
    {

        $precio = $get('precio') ?? 0 ;
        $cantidad = $get('cantidad') ?? 1;


        $total = $precio * $cantidad;
        //dd($get('precio') , $get('cantidad'), $total, $state);
            $set('total', number_format($total, 2,'.',''));

    }

    public static function calculateTotalState($state, $get, $set): void
    {
        $precio = $get('precio') ?? 1 ;
        $cantidad = $get('cantidad') ?? 1;

        $total = $precio * $cantidad;
     //  dd($get('precio') , $get('cantidad'), $cantidad, $precio, $total, $state);

        $set('total', number_format($total, 2,'.',''));

    }
    public static function calculateCantidad( $get, $set): void
    {

        $capas = $get('capas') ?? 1 ;
        $unidades = $get('unidades') ?? 1 ;

        $superficie = $get('superficie') ?? 1 ;
        $rendimiento = $get('rendimiento') ?? 1;
        $rendimiento2 = $get('rendimiento2') ?? 1;
        $tipo_rendimiento = $get('rendimiento_tipo');
        $pintura = 0 ;

        if($tipo_rendimiento == 'Kg/m2') {
            $pintura = (1 / $rendimiento) * $capas ;

        }else{
            $pintura = (1 / $rendimiento2) * $capas ;

        }
        $set('pintura', number_format($pintura, 2,'.',''));

        $area = $unidades * $superficie;
        $set('area', number_format($area, 2,'.',''));

//dd($capas, $area, $pintura,1 / $rendimiento, $tipo_rendimiento);
        if ($get('tipo') === 'Salidas') {
            $cantidad =  $pintura * $area;
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
                                ->live( debounce: 500)
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
                                ->live( debounce: 500)
                                ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Entradas')
                                ->columnSpanFull(),

                            Forms\Components\TextInput::make('destino')
                                ->live( debounce: 500)
                                ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('product_id')
                                ->relationship('product', 'name')
                                ->label('Producto')
                                ->searchable()
                                ->required()
                                ->live( debounce: 500)
                                ->preload()
                                ->columnSpanFull()
                                ->createOptionForm(
                                    Product::getForm()
                                )
                                ->afterStateUpdated(
                                    function ($state, Forms\Set $set, Forms\Get $get) {

                                        $set('descripcion', Product::find($state)?->description ?? 'li');

                                        $set('medida', Product::find($state)?->medida ?? 'li');
                                        $set('rendimiento', Product::find($state)?->rendimiento ?? '1');
                                        $set('rendimiento2', Product::find($state)?->rendimiento2 ?? '1');
                                        $set('precio', Product::find($state)?->price ?? 0);
                                        $set('stock', Product::find($state)?->stock() ?? 1);
                                        $set('superficie', 1);

                                        self::calculateCantidad( $get, $set);

                                    }



                                ),
                            Forms\Components\TextInput::make('stock')
                                ->label('Stock Actual')
                                ->readOnly(true),

                            Forms\Components\TextInput::make('medida')
                                ->readOnly(true),
                            Forms\Components\TextInput::make('descripcion')
                                ->columnSpanFull(),

                            Forms\Components\ToggleButtons::make('rendimiento_tipo')
                                ->label('Rendimiento')
                                ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                                ->inline()
                                ->default('Kg/m2')
                                ->live( debounce: 500)

                                ->options([
                                    'Kg/m2' => 'Kg/m2',
                                    'Kg/mL' => 'Kg/mL',
                                ])
                                ->colors([
                                    'Kg/m2' => 'primary',
                                    'Kg/mL' => 'success',
                                ])
                                ->icons([
                                    'Kg/m2' => 'heroicon-m-sparkles',
                                    'Kg/mL' => 'heroicon-m-arrow-path',

                                ])
                                ->afterStateUpdated(
                                    function ($state, Forms\Get $get, Set $set) {
                                        if($state !== null ) {
                                            self::calculateTotalState($state, $get, $set);
                                            self::calculateCantidad( $get, $set);
                                        }

                                    }
                                )
                            ,
                            Forms\Components\TextInput::make('rendimiento')
                                ->label('Rendimiento Kg/m2 , Con 1kg cubre :')
                                ->suffix('m2')
                                ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas' and $get('rendimiento_tipo') === 'Kg/m2')
                                ->live(debounce: 500)
                                ->afterStateUpdated(
                                    function ($state, Forms\Get $get, Set $set) {
                                        if($state !== null or $get('rendimiento') !== '') {
                                            self::calculateTotalState($state, $get, $set);
                                            self::calculateCantidad( $get, $set);
                                        }
                                    }
                                )
                                ->numeric(),
                            Forms\Components\TextInput::make('rendimiento2')
                                ->label('Rendimiento Kg/mL , Con 1kg cubre :')
                                ->suffix('mL')
                                ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas' && $get('rendimiento_tipo') === 'Kg/mL')
                                ->live(debounce: 500)
                                ->afterStateUpdated(
                                    function ($state, Forms\Get $get, Set $set) {
                                        if($state !== null or $get('rendimiento2') !== '') {
                                            self::calculateTotalState($state, $get, $set);
                                            self::calculateCantidad( $get, $set);
                                        }
                                    }
                                )
                                ->numeric(),

                        ])->columns(2),


                ])->columnSpan(2),



                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Datos del producto')->schema([

                        Forms\Components\TextInput::make('capas')
                            ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                            ->live(debounce: 500)
                            ->default(1)
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {
                                    self::calculateCantidad( $get, $set);
                                }
                            )
                            ->numeric(),


                        Forms\Components\TextInput::make('unidades')
                            ->required()
                            ->default(1)
                            ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                            ->live(debounce: 500)
                            ->prefix('Unidades')
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {

                                    if($state !== null or $get('superficie') !== '') {
                                        self::calculateTotalState($state, $get, $set);
                                        self::calculateCantidad( $get, $set);
                                    }
                                }
                            )
                            ->numeric(),
                        Forms\Components\TextInput::make('superficie')
                            ->required()
                            ->label('Superficie por Unidad')
                            ->default(1)
                            ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                            ->live(debounce: 500)
                            ->prefix('M2')
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {

                                    if($state !== null or $get('superficie') !== '') {
                                        self::calculateTotalState($state, $get, $set);
                                        self::calculateCantidad( $get, $set);
                                    }
                                }
                            )
                            ->numeric(),

                        Forms\Components\Section::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('area')
                                    ->label('Area Total ')
                                    ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                                    ->readOnly()
                                    ->numeric()
                                    ->prefix('M2'),
                                Forms\Components\TextInput::make('pintura')
                                    ->label('kg x m2')
                                    ->visible(fn (Forms\Get $get): bool => $get('tipo') === 'Salidas')
                                    ->numeric()
                                    ->prefix('Kg'),

                        ]),

                        /*Forms\Components\TextInput::make('precio')
                            ->live( debounce: 500)
                            ->afterStateUpdated(
                                function ($state, Forms\Get $get, Set $set) {

                                    if($state !== null or $get('precio') !== '') {
                                        self::calculateTotalState($state, $get, $set);
                                    }
                                }
                            )
                            ->numeric()
                            ->prefix('EUR'),*/

                        Forms\Components\TextInput::make('cantidad')
                            ->numeric()
                            ->required()


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
                                    if($state !== null or $get('cantidad') !== '') {
                                        self::calculateTotalState($state, $get, $set);
                                    }
                                }
                            ),

                        Forms\Components\TextInput::make('total')
                            ->numeric()
                            ->visible(false)
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
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Fecha desde'),
                        Forms\Components\DatePicker::make('created_until')->label('Fecha Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })

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
