<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Empresa\Pages;

use JamesMosquera\FiscalColombia\Enums\RegimenTributario;
use JamesMosquera\FiscalColombia\MoonShine\NitField;
use MoonShine\Laravel\Pages\Crud\DetailPage;
use MoonShine\UI\Fields\ID;
use MoonShine\UI\Fields\Select;
use MoonShine\UI\Fields\Text;

/**
 * @extends DetailPage<\App\MoonShine\Resources\Empresa\EmpresaResource>
 */
final class EmpresaDetailPage extends DetailPage
{
    protected function fields(): iterable
    {
        return [
            ID::make(),
            Text::make('Razón social', 'razon_social'),
            NitField::make('NIT', 'nit'),
            Text::make('Dígito verificador', 'dv'),
            Select::make('Régimen tributario', 'regimen_tributario')
                ->options(
                    collect(RegimenTributario::cases())
                        ->mapWithKeys(fn($r) => [$r->value => $r->label()])
                        ->all()
                ),
        ];
    }
}
