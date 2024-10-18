<?php

namespace App\Filament\Exports;

use App\Models\Acceso;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AccesoExporter extends Exporter
{
    protected static ?string $model = Acceso::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name'),
            ExportColumn::make('fecha'),
            ExportColumn::make('hora'),
            ExportColumn::make('fecha_real'),
            ExportColumn::make('hora_real'),
          /*  ExportColumn::make('descripcion'),
            ExportColumn::make('observaciones'),
            ExportColumn::make('condicion'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('total_horas')
                ->state(function (Order $record): float {
                    return $record->hora + 8;
                })*/
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your acceso export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
