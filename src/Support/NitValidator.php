<?php

namespace JamesMosquera\FiscalColombia\Support;

class NitValidator
{
    /**
     * Factores para el cálculo del dígito de verificación según la DIAN.
     * Se aplican de izquierda a derecha sobre los dígitos del NIT.
     */
    private const FACTORES = [71, 67, 59, 53, 47, 43, 41, 37, 29, 23, 19, 17, 13, 7, 3];

    /**
     * Calcula el dígito de verificación de un NIT colombiano.
     */
    public static function calcularDv(string $nit): int
    {
        $nit = preg_replace('/\D/', '', $nit);

        if (empty($nit)) {
            throw new \InvalidArgumentException('El NIT no puede estar vacío.');
        }

        $n = strlen($nit);
        $factores = array_slice(self::FACTORES, count(self::FACTORES) - $n);

        $suma = 0;
        for ($i = 0; $i < $n; $i++) {
            $suma += (int) $nit[$i] * $factores[$i];
        }

        $residuo = $suma % 11;

        if ($residuo === 0) return 0;
        if ($residuo === 1) return 1;
        return 11 - $residuo;
    }

    /**
     * Valida que el NIT incluya el dígito de verificación correcto.
     * Acepta formatos: "900123456-7", "9001234567", "900.123.456-7"
     */
    public static function esValido(string $nit): bool
    {
        $limpio = preg_replace('/[\s.]/', '', $nit);

        if (!preg_match('/^(\d+)-?(\d)$/', $limpio, $matches)) {
            return false;
        }

        $numero = $matches[1];
        $dvIngresado = (int) $matches[2];

        try {
            return self::calcularDv($numero) === $dvIngresado;
        } catch (\InvalidArgumentException) {
            return false;
        }
    }

    /**
     * Formatea un NIT con puntos y guión: 900.123.456-7
     */
    public static function formatear(string $nit, int $dv): string
    {
        $nit = preg_replace('/\D/', '', $nit);
        $formateado = number_format((int) $nit, 0, '', '.');
        return "{$formateado}-{$dv}";
    }
}
