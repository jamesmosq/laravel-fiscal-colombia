<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Contribuyente\Pages;

use JamesMosquera\FiscalColombia\MoonShine\TipoDocumentoField;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends IndexPage<\App\MoonShine\Resources\Contribuyente\ContribuyenteResource>
 */
final class ContribuyenteIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            TipoDocumentoField::make('Tipo doc.', 'tipo_documento'),
            Text::make('Número documento', 'numero_documento')->sortable(),
            Text::make('Nombres', 'nombres')->sortable(),
            Text::make('Apellidos', 'apellidos')->sortable(),
        ];
    }
}
