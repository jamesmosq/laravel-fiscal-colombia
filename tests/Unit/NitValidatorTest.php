<?php

namespace JamesMosquera\FiscalColombia\Tests\Unit;

use JamesMosquera\FiscalColombia\Support\NitValidator;
use PHPUnit\Framework\TestCase;

class NitValidatorTest extends TestCase
{
    // --- calcularDv ---

    public function test_calcula_dv_dian(): void
    {
        // NIT de la DIAN: 800.197.268-4 (verificado oficialmente)
        $this->assertSame(4, NitValidator::calcularDv('800197268'));
    }

    public function test_calcula_dv_epm(): void
    {
        // NIT de EPM: 890.904.996-1 (verificado oficialmente)
        $this->assertSame(1, NitValidator::calcularDv('890904996'));
    }

    public function test_calcula_dv_universidad_nacional(): void
    {
        // NIT de la Universidad Nacional: 899.999.063-3
        $this->assertSame(3, NitValidator::calcularDv('899999063'));
    }

    public function test_calcula_dv_ignorando_puntos(): void
    {
        $this->assertSame(4, NitValidator::calcularDv('800.197.268'));
    }

    public function test_calcula_dv_ignorando_espacios(): void
    {
        $this->assertSame(4, NitValidator::calcularDv('800 197 268'));
    }

    public function test_lanza_excepcion_con_nit_vacio(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        NitValidator::calcularDv('');
    }

    // --- esValido ---

    public function test_valida_nit_correcto_con_guion(): void
    {
        $this->assertTrue(NitValidator::esValido('800197268-4'));
    }

    public function test_valida_nit_correcto_sin_guion(): void
    {
        $this->assertTrue(NitValidator::esValido('8001972684'));
    }

    public function test_valida_nit_con_puntos_y_guion(): void
    {
        $this->assertTrue(NitValidator::esValido('800.197.268-4'));
    }

    public function test_valida_epm(): void
    {
        $this->assertTrue(NitValidator::esValido('890904996-1'));
    }

    public function test_rechaza_nit_con_dv_incorrecto(): void
    {
        // DV real de la DIAN es 4, ponemos 9
        $this->assertFalse(NitValidator::esValido('800197268-9'));
    }

    public function test_rechaza_nit_con_formato_invalido(): void
    {
        $this->assertFalse(NitValidator::esValido('abc-xyz'));
    }

    public function test_rechaza_nit_vacio(): void
    {
        $this->assertFalse(NitValidator::esValido(''));
    }

    // --- formatear ---

    public function test_formatea_nit_con_puntos_y_guion(): void
    {
        $this->assertSame('800.197.268-4', NitValidator::formatear('800197268', 4));
    }
}
