<?php

declare(strict_types=1);

namespace JamesMosquera\FiscalColombia\MoonShine;

use JamesMosquera\FiscalColombia\Enums\TipoDocumento;
use MoonShine\UI\Fields\Select;

class TipoDocumentoField extends Select
{
    protected function prepareBeforeRender(): void
    {
        parent::prepareBeforeRender();

        if (empty($this->options)) {
            $this->options(
                collect(TipoDocumento::cases())
                    ->mapWithKeys(fn(TipoDocumento $t) => [$t->value => $t->label()])
                    ->all()
            );
        }
    }
}
