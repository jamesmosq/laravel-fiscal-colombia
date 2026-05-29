<?php

namespace JamesMosquera\FiscalColombia\Enums;

enum TipoDocumento: string
{
    case CC   = 'CC';
    case NIT  = 'NIT';
    case CE   = 'CE';
    case RC   = 'RC';
    case TI   = 'TI';
    case PEP  = 'PEP';
    case PPT  = 'PPT';
    case PAS  = 'PAS';
    case NUIP = 'NUIP';

    /** Código DIAN oficial */
    public function codigoDian(): int
    {
        return match($this) {
            self::CC   => 13,
            self::NIT  => 31,
            self::CE   => 22,
            self::RC   => 11,
            self::TI   => 12,
            self::PEP  => 41,
            self::PPT  => 42,
            self::PAS  => 47,
            self::NUIP => 91,
        };
    }

    public function label(): string
    {
        return match($this) {
            self::CC   => 'Cédula de Ciudadanía',
            self::NIT  => 'NIT',
            self::CE   => 'Cédula de Extranjería',
            self::RC   => 'Registro Civil',
            self::TI   => 'Tarjeta de Identidad',
            self::PEP  => 'Permiso Especial de Permanencia',
            self::PPT  => 'Permiso por Protección Temporal',
            self::PAS  => 'Pasaporte',
            self::NUIP => 'NUIP',
        };
    }

    public function requiereDv(): bool
    {
        return $this === self::NIT;
    }

    /** Retorna todos los tipos como array para selects */
    public static function opciones(): array
    {
        return array_map(
            fn(self $tipo) => ['value' => $tipo->value, 'label' => $tipo->label()],
            self::cases()
        );
    }
}
