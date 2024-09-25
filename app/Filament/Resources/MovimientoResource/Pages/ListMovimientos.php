<?php

namespace App\Filament\Resources\MovimientoResource\Pages;

use App\Filament\Resources\MovimientoResource;
use App\Models\Movimiento;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListMovimientos extends ListRecords
{
    protected static string $resource = MovimientoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos')->badge(Movimiento::count()),
            'Compras' => Tab::make()->query(fn($query) => $query->where('tipo','Compras'))->badge(Movimiento::where('tipo','Compras')->count()),
            'Ventas' => Tab::make()->query(fn($query) => $query->where('tipo','Ventas'))->badge(Movimiento::where('tipo','Ventas')->count()),



        ];
    }
}
