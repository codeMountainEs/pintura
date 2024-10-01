<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;



class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','brand_id','name','slug','images','rendimiento',
        'description','price','is_active','is_featured','in_stock','on_sale','medida'];



    protected $casts = ['images' => 'array'];


    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function movimientos(){
        return $this->hasMany(Movimiento::class);
    }

    public function stock()
    {
        $stock = $this->movimientos()
                ->where('tipo', 'Entradas')
                ->sum('cantidad')
            - $this->movimientos()
                ->where('tipo', 'Salidas')
                ->sum('cantidad');
        return round($stock,2);
    }


    public static function getForm($brandId = null) : array
    {
        return [
            Section::make([

                Group::make([
                    Section::make('Product Information')->schema(
                        [
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255)
                                ->live( debounce: 500)
                                ->afterStateUpdated(
                                    function (string $operation, $state , Set $set) {
                                    //dd(Str::slug($state));
                                        //$operation !== 'create' ?   null  : $set('slug', Str::slug($state));
                                       $set('slug', Str::slug($state));


                                    }),

                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->disabled()
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true),

                            Select::make('medida')
                                ->options([
                                    'Litros' => 'Litros',
                                    'Kg' => 'Kg'
                                ])->default('Kg'),

                            TextInput::make('rendimiento')
                                ->default(1)
                                ->suffix('Litros/M2'),


                                 Select::make('category_id')
                                     ->required()
                                     ->label('Categoria')
                                     ->searchable()
                                     ->preload()
                                     ->relationship('category', 'name')
                                     ->createOptionForm(
                                         Category::getForm()
                                     ),


                        Select::make('brand_id')
                            ->label('Marca')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('brand', 'name')
                            ->createOptionForm(
                                Brand::getForm()
                            ),
                            MarkdownEditor::make('description')
                                ->columnSpanFull()
                                ->fileAttachmentsDirectory('products'),




                        ])->columns(2),

                       Section::make('Images')->schema([
                            FileUpload::make('images')
                                ->image()
                                ->multiple()
                                ->directory('products')
                                ->maxFiles(5)
                                ->imageEditor()
                                ->imageResizeMode('cover')
                                ->imageCropAspectRatio('16:9')
                                ->imageResizeTargetWidth('1280')
                                ->imageResizeTargetHeight('720')
                        ])->columnSpan(1)

                ])->columnSpan(2),

                    Section::make('Price')->schema([
                        TextInput::make('price')
                            ->label('Precio')
                            ->numeric()
                            ->default(0)
                            ->prefix('EUR')
                    ]),

                    Section::make('Status')->schema([
                        Toggle::make('in_stock')
                            ->required()
                            ->inline()
                            ->default(true),
                        Toggle::make('is_active')
                            ->required()
                            ->inline()
                            ->default(true),
                        Toggle::make('is_featured')
                            ->required()
                            ->inline()
                            ->default(true),
                        Toggle::make('on_sale')
                            ->required()
                            ->inline()
                            ->default(true),
                    ]),



            ]),

        ];
    }
}
