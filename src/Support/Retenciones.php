<?php

namespace JamesMosquera\FiscalColombia\Support;

class Retenciones
{
    /**
     * Calcula todas las retenciones aplicables a un concepto y valor base.
     *
     * @param  int  $valorBase  Valor en pesos colombianos (sin centavos)
     * @param  string  $concepto  honorarios|servicios|compras|arrendamiento|rendimientos
     * @param  bool  $calcularReteiva  Si aplica reteiva (responsable de IVA)
     * @param  string|null  $actividadIca  industria|comercio|servicios (null = no aplica)
     * @return array{retefuente: int, reteiva: int, reteica: int, total_retenciones: int, valor_neto: int}
     */
    public static function calcular(
        int    $valorBase,
        string $concepto = 'servicios',
        bool   $calcularReteiva = false,
        ?string $actividadIca = null
    ): array {
        $config = config('fiscal-colombia');

        $tarifaRetefuente = $config['retefuente'][$concepto]
            ?? throw new \InvalidArgumentException("Concepto '{$concepto}' no reconocido.");

        $retefuente = (int) round($valorBase * $tarifaRetefuente / 100);

        $reteiva = 0;
        if ($calcularReteiva) {
            $tarifa = $config['reteiva']['general'];
            $reteiva = (int) round($valorBase * $tarifa / 100);
        }

        $reteica = 0;
        if ($actividadIca !== null) {
            $tarifaIca = $config['reteica'][$actividadIca]
                ?? throw new \InvalidArgumentException("Actividad ICA '{$actividadIca}' no reconocida.");
            $reteica = (int) round($valorBase * $tarifaIca / 1000);
        }

        $totalRetenciones = $retefuente + $reteiva + $reteica;

        return [
            'retefuente'         => $retefuente,
            'reteiva'            => $reteiva,
            'reteica'            => $reteica,
            'total_retenciones'  => $totalRetenciones,
            'valor_neto'         => $valorBase - $totalRetenciones,
        ];
    }

    /**
     * Calcula solo retefuente para un concepto dado.
     */
    public static function retefuente(int $valorBase, string $concepto = 'servicios'): int
    {
        return self::calcular($valorBase, $concepto)['retefuente'];
    }
}
