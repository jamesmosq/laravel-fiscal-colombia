<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Empresa;

use App\Models\Empresa;
use App\MoonShine\Resources\Empresa\Pages\EmpresaDetailPage;
use App\MoonShine\Resources\Empresa\Pages\EmpresaFormPage;
use App\MoonShine\Resources\Empresa\Pages\EmpresaIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Attributes\Icon;

/**
 * @extends ModelResource<Empresa, EmpresaIndexPage, EmpresaFormPage, EmpresaDetailPage>
 */
#[Icon('building-office')]
class EmpresaResource extends ModelResource
{
    protected string $model = Empresa::class;

    protected string $column = 'razon_social';

    public function getTitle(): string
    {
        return 'Empresas';
    }

    protected function pages(): array
    {
        return [
            EmpresaIndexPage::class,
            EmpresaFormPage::class,
            EmpresaDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'razon_social', 'nit'];
    }
}
