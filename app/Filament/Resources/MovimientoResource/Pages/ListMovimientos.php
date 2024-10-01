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
            'Entradas' => Tab::make()->query(fn($query) => $query->where('tipo','Entradas'))->badge(Movimiento::where('tipo','Entradas')->count()),
            'Salidas' => Tab::make()->query(fn($query) => $query->where('tipo','Salidas'))->badge(Movimiento::where('tipo','Salidas')->count()),



        ];
    }
}
