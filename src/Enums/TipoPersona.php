<?php

namespace JamesMosquera\FiscalColombia\Enums;

enum TipoPersona: string
{
    case NATURAL  = 'natural';
    case JURIDICA = 'juridica';

    public function label(): string
    {
        return match($this) {
            self::NATURAL  => 'Persona Natural',
            self::JURIDICA => 'Persona Jurídica',
        };
    }

    /** Tipos de documento válidos para este tipo de persona */
    public function documentosPermitidos(): array
    {
        return match($this) {
            self::NATURAL  => [
                TipoDocumento::CC,
                TipoDocumento::CE,
                TipoDocumento::RC,
                TipoDocumento::TI,
                TipoDocumento::PEP,
                TipoDocumento::PPT,
                TipoDocumento::PAS,
                TipoDocumento::NUIP,
            ],
            self::JURIDICA => [
                TipoDocumento::NIT,
            ],
        };
    }
}
