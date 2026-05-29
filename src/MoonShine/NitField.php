<?php

declare(strict_types=1);

namespace JamesMosquera\FiscalColombia\MoonShine;

use MoonShine\UI\Fields\Text;

class NitField extends Text
{
    protected string $view = 'fiscal-colombia::fields.nit';

    protected function prepareBeforeRender(): void
    {
        parent::prepareBeforeRender();
    }
}
