<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Empresa\Pages;

use JamesMosquera\FiscalColombia\MoonShine\NitField;
use MoonShine\Laravel\Pages\Crud\IndexPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;
use JamesMosquera\FiscalColombia\Enums\RegimenTributario;

/**
 * @extends IndexPage<\App\MoonShine\Resources\Empresa\EmpresaResource>
 */
final class EmpresaIndexPage extends IndexPage
{
    protected function fields(): iterable
    {
        return [
            ID::make()->sortable(),
            Text::make('Razón social', 'razon_social')->sortable(),
            NitField::make('NIT', 'nit'),
            Text::make('DV', 'dv'),
            Select::make('Régimen tributario', 'regimen_tributario')
                ->options(
                    collect(RegimenTributario::cases())
                        ->mapWithKeys(fn($r) => [$r->value => $r->label()])
                        ->all()
                ),
        ];
    }
}
