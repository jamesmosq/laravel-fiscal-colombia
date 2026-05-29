# moonshine-colombia-fiscal

[![Versión en Packagist](https://img.shields.io/packagist/v/jamesmosquera/moonshine-colombia-fiscal.svg?style=flat-square)](https://packagist.org/packages/jamesmosquera/moonshine-colombia-fiscal)
[![Descargas](https://img.shields.io/packagist/dt/jamesmosquera/moonshine-colombia-fiscal.svg?style=flat-square)](https://packagist.org/packages/jamesmosquera/moonshine-colombia-fiscal)
[![PHP](https://img.shields.io/packagist/php-v/jamesmosquera/moonshine-colombia-fiscal.svg?style=flat-square)](https://packagist.org/packages/jamesmosquera/moonshine-colombia-fiscal)
[![Licencia](https://img.shields.io/packagist/l/jamesmosquera/moonshine-colombia-fiscal.svg?style=flat-square)](LICENSE)

Herramientas fiscales y tributarias colombianas para Laravel y MoonShine.

- **Valida NIT** con dígito de verificación según el algoritmo oficial de la DIAN.
- **Tipos de documento** conforme a la Resolución 000042 de 2020 (CC, NIT, CE, TI, PEP, PPT, NUIP…).
- **Retenciones** configurables: retefuente, reteiva y reteica.
- **Campos MoonShine** listos para usar en el panel administrativo (opcional).

**Doble uso:** funciona como paquete Laravel puro o como plugin con campos de UI para MoonShine v4.

---

## Requisitos

| Dependencia      | Versión mínima |
|------------------|----------------|
| PHP              | 8.2+           |
| Laravel          | 10 / 11 / 12   |
| MoonShine *(opcional)* | ^4.14   |

---

## Instalación

```bash
composer require jamesmosquera/moonshine-colombia-fiscal
```

El `FiscalColombiaServiceProvider` se registra automáticamente vía auto-discovery de Laravel.

### Publicar la configuración

```bash
php artisan vendor:publish --tag=fiscal-colombia-config
```

Esto crea `config/fiscal-colombia.php` en tu aplicación con las tarifas de retención. Puedes ajustarlas según tu actividad económica o cada reforma tributaria.

---

## Uso — Laravel puro

### NitValidator

Clase PHP pura, sin dependencias de framework. Se puede usar en cualquier proyecto PHP 8.2+.

```php
use JamesMosquera\FiscalColombia\Support\NitValidator;

// Calcular el dígito de verificación
NitValidator::calcularDv('800197268');   // → 4

// Validar NIT completo (acepta con puntos, guión, espacios o dígitos continuos)
NitValidator::esValido('800197268-4');   // → true
NitValidator::esValido('800.197.268-4'); // → true
NitValidator::esValido('8001972684');    // → true  (último dígito = DV)
NitValidator::esValido('800197268-9');   // → false (DV incorrecto)

// Formatear con puntos y guión: 800.197.268-4
NitValidator::formatear('800197268', 4);
```

NITs de referencia verificados con la DIAN:

| Entidad            | NIT           | DV |
|--------------------|---------------|----|
| DIAN               | 800.197.268   | 4  |
| EPM                | 890.904.996   | 1  |
| Univ. Nacional     | 899.999.063   | 3  |

### NitRule — Validación en formularios

```php
use JamesMosquera\FiscalColombia\Rules\NitRule;

$request->validate([
    'nit' => ['required', new NitRule],
]);
```

Acepta todos los formatos: `800197268-4`, `800.197.268-4`, `8001972684`.

### TipoDocumento — Enum DIAN

Tipos de documento según la **Resolución DIAN 000042 de 2020**.

```php
use JamesMosquera\FiscalColombia\Enums\TipoDocumento;

TipoDocumento::CC->label();       // → "Cédula de Ciudadanía"
TipoDocumento::NIT->codigoDian(); // → 31
TipoDocumento::NIT->requiereDv(); // → true
TipoDocumento::CC->requiereDv();  // → false

// Array para selects HTML
TipoDocumento::opciones();
// [['value' => 'CC', 'label' => 'Cédula de Ciudadanía'], ...]
```

| Enum                   | Sigla | Código DIAN |
|------------------------|-------|-------------|
| `TipoDocumento::CC`    | CC    | 13          |
| `TipoDocumento::NIT`   | NIT   | 31          |
| `TipoDocumento::CE`    | CE    | 22          |
| `TipoDocumento::RC`    | RC    | 11          |
| `TipoDocumento::TI`    | TI    | 12          |
| `TipoDocumento::PEP`   | PEP   | 41          |
| `TipoDocumento::PPT`   | PPT   | 42          |
| `TipoDocumento::PAS`   | PAS   | 47          |
| `TipoDocumento::NUIP`  | NUIP  | 91          |

### RegimenTributario — Enum

```php
use JamesMosquera\FiscalColombia\Enums\RegimenTributario;

RegimenTributario::RESPONSABLE_IVA->label();         // → "Responsable de IVA"
RegimenTributario::RESPONSABLE_IVA->aplicaReteiva(); // → true
RegimenTributario::NO_RESPONSABLE_IVA->aplicaReteiva(); // → false

RegimenTributario::opciones(); // array para selects
```

| Enum                                       | Label                              |
|--------------------------------------------|------------------------------------|
| `RegimenTributario::RESPONSABLE_IVA`       | Responsable de IVA                 |
| `RegimenTributario::NO_RESPONSABLE_IVA`    | No Responsable de IVA              |
| `RegimenTributario::GRAN_CONTRIBUYENTE`    | Gran Contribuyente                 |
| `RegimenTributario::REGIMEN_SIMPLE`        | Régimen Simple de Tributación      |

### TipoPersona — Enum

```php
use JamesMosquera\FiscalColombia\Enums\TipoPersona;

TipoPersona::JURIDICA->label();              // → "Persona Jurídica"
TipoPersona::NATURAL->documentosPermitidos(); // → [CC, CE, RC, TI, PEP, PPT, PAS, NUIP]
TipoPersona::JURIDICA->documentosPermitidos(); // → [NIT]
```

### Retenciones

Las tarifas se leen desde `config/fiscal-colombia.php`, lo que permite ajustarlas sin modificar el paquete.

```php
use JamesMosquera\FiscalColombia\Support\Retenciones;

$resultado = Retenciones::calcular(
    valorBase: 1_000_000,
    concepto: 'honorarios',
    calcularReteiva: true,
    actividadIca: 'servicios'
);

// [
//   'retefuente'        => 110_000,   // 11%
//   'reteiva'           => 150_000,   // 15% del IVA
//   'reteica'           => 966,       // 0.966‰
//   'total_retenciones' => 260_966,
//   'valor_neto'        => 739_034,
// ]

// Solo retefuente
Retenciones::retefuente(1_000_000, 'honorarios'); // → 110_000
```

Conceptos disponibles por defecto:

| Concepto       | Tarifa  |
|----------------|---------|
| `honorarios`   | 11 %    |
| `servicios`    | 4 %     |
| `compras`      | 2.5 %   |
| `arrendamiento`| 4 %     |
| `rendimientos` | 7 %     |

---

## Uso — Con MoonShine v4

### NitField

Campo de texto con cálculo automático del dígito de verificación en tiempo real (Alpine.js).

```php
use JamesMosquera\FiscalColombia\MoonShine\NitField;

NitField::make('NIT', 'nit')->required(),
```

El campo muestra el DV calculado mientras el usuario escribe.

### TipoDocumentoField

Select pre-cargado con todos los tipos de documento de la Resolución 000042/2020.

```php
use JamesMosquera\FiscalColombia\MoonShine\TipoDocumentoField;

TipoDocumentoField::make('Tipo de documento', 'tipo_documento')->required(),
```

Las opciones se cargan automáticamente desde el enum `TipoDocumento`. Puedes pasarle un subconjunto con `->options([...])`.

---

## Actualización de tarifas

Las tarifas cambian anualmente por decreto del Ministerio de Hacienda. Después de publicar el config, edita `config/fiscal-colombia.php`:

```php
'retefuente' => [
    'honorarios' => 11.0, // actualizar según decreto vigente
],
```

---

## Tests

```bash
cd packages/moonshine-colombia-fiscal
./vendor/bin/phpunit
```

**20 tests, 27 aserciones** — todos los algoritmos DIAN verificados contra NITs reales.

---

## Licencia

MIT — ver [LICENSE](LICENSE).

---

*Desarrollado por [James Mosquera Rentería](https://jamesmosquera.com) · Medellín, Colombia*
*Basado en normatividad colombiana: E.T., Resolución DIAN 000042/2020*
