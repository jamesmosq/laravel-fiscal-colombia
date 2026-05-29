<?php

namespace JamesMosquera\FiscalColombia\Tests\Unit;

use JamesMosquera\FiscalColombia\Tests\TestCase;
use JamesMosquera\FiscalColombia\Support\Retenciones;

class RetencionesTest extends TestCase
{
    public function test_calcula_retefuente_honorarios(): void
    {
        $resultado = Retenciones::calcular(5_000_000, 'honorarios');

        // 11% de 5.000.000 = 550.000
        $this->assertSame(550_000, $resultado['retefuente']);
        $this->assertSame(0, $resultado['reteiva']);
        $this->assertSame(0, $resultado['reteica']);
        $this->assertSame(550_000, $resultado['total_retenciones']);
        $this->assertSame(4_450_000, $resultado['valor_neto']);
    }

    public function test_calcula_retefuente_servicios(): void
    {
        $resultado = Retenciones::calcular(1_000_000, 'servicios');

        // 4% de 1.000.000 = 40.000
        $this->assertSame(40_000, $resultado['retefuente']);
        $this->assertSame(960_000, $resultado['valor_neto']);
    }

    public function test_calcula_con_reteiva(): void
    {
        $resultado = Retenciones::calcular(1_000_000, 'honorarios', calcularReteiva: true);

        // Retefuente: 11% = 110.000
        // Reteiva: 15% = 150.000
        $this->assertSame(110_000, $resultado['retefuente']);
        $this->assertSame(150_000, $resultado['reteiva']);
        $this->assertSame(260_000, $resultado['total_retenciones']);
    }

    public function test_calcula_con_reteica_industria(): void
    {
        $resultado = Retenciones::calcular(1_000_000, 'servicios', actividadIca: 'industria');

        // Reteica industria: 0.966‰ de 1.000.000 = 966
        $this->assertSame(966, $resultado['reteica']);
    }

    public function test_lanza_excepcion_con_concepto_invalido(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Retenciones::calcular(1_000_000, 'concepto_invalido');
    }

    public function test_helper_retefuente(): void
    {
        $this->assertSame(25_000, Retenciones::retefuente(1_000_000, 'compras'));
    }
}
