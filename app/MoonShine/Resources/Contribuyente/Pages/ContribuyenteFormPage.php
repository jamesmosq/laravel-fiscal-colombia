<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Contribuyente\Pages;

use JamesMosquera\FiscalColombia\MoonShine\TipoDocumentoField;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Text;

/**
 * @extends FormPage<\App\MoonShine\Resources\Contribuyente\ContribuyenteResource>
 */
final class ContribuyenteFormPage extends FormPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),

            TipoDocumentoField::make('Tipo de documento', 'tipo_documento')
                ->required(),

            Text::make('Número de documento', 'numero_documento')
                ->required()
                ->placeholder('Ej: 1234567890'),

            Text::make('Nombres', 'nombres')
                ->required(),

            Text::make('Apellidos', 'apellidos')
                ->required(),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'tipo_documento'    => 'required|string',
            'numero_documento'  => 'required|string|max:20',
            'nombres'           => 'required|string|max:255',
            'apellidos'         => 'required|string|max:255',
        ];
    }
}
