<?php

namespace JamesMosquera\FiscalColombia\Enums;

enum RegimenTributario: string
{
    case RESPONSABLE_IVA     = 'responsable_iva';
    case NO_RESPONSABLE_IVA  = 'no_responsable_iva';
    case GRAN_CONTRIBUYENTE  = 'gran_contribuyente';
    case REGIMEN_SIMPLE      = 'regimen_simple';

    public function label(): string
    {
        return match($this) {
            self::RESPONSABLE_IVA    => 'Responsable de IVA',
            self::NO_RESPONSABLE_IVA => 'No Responsable de IVA',
            self::GRAN_CONTRIBUYENTE => 'Gran Contribuyente',
            self::REGIMEN_SIMPLE     => 'Régimen Simple de Tributación (SIMPLE)',
        };
    }

    public function aplicaReteiva(): bool
    {
        return $this === self::RESPONSABLE_IVA || $this === self::GRAN_CONTRIBUYENTE;
    }

    public static function opciones(): array
    {
        return array_map(
            fn(self $r) => ['value' => $r->value, 'label' => $r->label()],
            self::cases()
        );
    }
}
