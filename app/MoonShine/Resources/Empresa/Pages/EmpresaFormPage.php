<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Empresa\Pages;

use JamesMosquera\FiscalColombia\Enums\RegimenTributario;
use JamesMosquera\FiscalColombia\MoonShine\NitField;
use MoonShine\Contracts\Core\TypeCasts\DataWrapperContract;
use MoonShine\Laravel\Pages\Crud\FormPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends FormPage<\App\MoonShine\Resources\Empresa\EmpresaResource>
 */
final class EmpresaFormPage extends FormPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),

            NitField::make('NIT', 'nit')
                ->required()
                ->placeholder('Ej: 800197268'),

            Text::make('Dígito verificador', 'dv')
                ->placeholder('Se calcula automáticamente'),

            Text::make('Razón social', 'razon_social')
                ->required(),

            Select::make('Régimen tributario', 'regimen_tributario')
                ->options(
                    collect(RegimenTributario::cases())
                        ->mapWithKeys(fn($r) => [$r->value => $r->label()])
                        ->all()
                )
                ->required(),
        ];
    }

    protected function rules(DataWrapperContract $item): array
    {
        return [
            'nit'               => 'required|string|max:15',
            'razon_social'      => 'required|string|max:255',
            'regimen_tributario'=> 'required|string',
        ];
    }
}
