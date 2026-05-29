<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Contribuyente\Pages;

use JamesMosquera\FiscalColombia\MoonShine\TipoDocumentoField;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends DetailPage<\App\MoonShine\Resources\Contribuyente\ContribuyenteResource>
 */
final class ContribuyenteDetailPage extends DetailPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),
            TipoDocumentoField::make('Tipo de documento', 'tipo_documento'),
            Text::make('Número de documento', 'numero_documento'),
            Text::make('Nombres', 'nombres'),
            Text::make('Apellidos', 'apellidos'),
        ];
    }
}
