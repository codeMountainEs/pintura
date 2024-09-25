<?php

namespace App\Models;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'image','is_active'];

    public function products() {
        return $this->hasMany(product::class);
    }

    public static function getForm($brandId = null) : array
    {
        return [
            Section::make([
                Grid::make()
                    ->schema([

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (string $operation, $state , Set $set) =>
                                $set('slug', Str::slug($state)) ),

                        TextInput::make('slug')
                            ->maxLength(255)
                            ->disabled()
                            ->required()
                            ->dehydrated()
                            ->unique(Brand::class, 'slug', ignoreRecord: true),

                    ]),
                FileUpload::make('image')
                    ->image()
                    ->directory('brands'),

                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ])
        ];
    }
}
