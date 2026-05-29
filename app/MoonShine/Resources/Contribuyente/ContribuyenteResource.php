<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Contribuyente;

use App\Models\Contribuyente;
use App\MoonShine\Resources\Contribuyente\Pages\ContribuyenteDetailPage;
use App\MoonShine\Resources\Contribuyente\Pages\ContribuyenteFormPage;
use App\MoonShine\Resources\Contribuyente\Pages\ContribuyenteIndexPage;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Support\Attributes\Icon;

/**
 * @extends ModelResource<Contribuyente, ContribuyenteIndexPage, ContribuyenteFormPage, ContribuyenteDetailPage>
 */
#[Icon('user')]
class ContribuyenteResource extends ModelResource
{
    protected string $model = Contribuyente::class;

    protected string $column = 'nombres';

    public function getTitle(): string
    {
        return 'Contribuyentes';
    }

    protected function pages(): array
    {
        return [
            ContribuyenteIndexPage::class,
            ContribuyenteFormPage::class,
            ContribuyenteDetailPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'nombres', 'apellidos', 'numero_documento'];
    }
}
