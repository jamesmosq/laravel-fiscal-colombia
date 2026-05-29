💡
moonshine-colombia-fiscal
Herramientas fiscales y tributarias colombianas para MoonShine
Plan de trabajo completo para Claude Code
Versión	1.0.0
Dificultad	⭐⭐⭐ Media
Tiempo estimado	2–3 semanas
Base legal	E.T. + Resoluciones DIAN


1. Descripción del Plugin
Pack de campos, validadores y constantes para el contexto fiscal y tributario colombiano: NIT con dígito de verificación, tipo de documento, RUT, retenciones configurables y validación de factura electrónica según normativa DIAN.

1.1 Componentes del plugin
Componente	Descripción	Base legal
NitField	Campo NIT con cálculo y validación del dígito	Art. 555-1 E.T.
TipoDocumentoField	Select tipo de documento (CC, NIT, CE, PEP, PPT...)	Res. DIAN 000042/2020
RegimenesEnum	Enum responsable / no responsable de IVA	Art. 437 E.T.
RetencionesConfig	Tarifas de retefuente, reteiva, reteica	Art. 392-401 E.T.
FacturaElectronica	Validador número de resolución DIAN	Res. 000042/2020
NitValidator	Clase PHP standalone para validar NITs	—

2. Estructura del Package
moonshine-colombia-fiscal/
├── src/
│   ├── FiscalServiceProvider.php
│   ├── Fields/
│   │   ├── NitField.php
│   │   └── TipoDocumentoField.php
│   ├── Enums/
│   │   ├── TipoDocumento.php
│   │   ├── RegimenTributario.php
│   │   └── TipoPersona.php
│   ├── Support/
│   │   ├── NitValidator.php
│   │   ├── Retenciones.php
│   │   └── FacturaDian.php
│   └── Rules/
│       ├── NitRule.php
│       └── ResolucionDianRule.php
├── resources/views/fields/
│   ├── nit.blade.php
│   └── tipo-documento.blade.php
├── config/
│   └── colombia-fiscal.php
├── composer.json
├── CLAUDE.md
└── README.md

3. Especificaciones Técnicas por Componente
3.1 Algoritmo Dígito de Verificación del NIT
El dígito de verificación del NIT colombiano se calcula con el algoritmo de módulo 11 con los factores primos definidos por la DIAN:

Factores de multiplicación (de derecha a izquierda)
Pos 9	Pos 8	Pos 7	Pos 6	Pos 5	Pos 4	Pos 3	Pos 2	Pos 1
71	67	59	53	47	43	41	37	29

Pasos del algoritmo
1.	Multiplicar cada dígito del NIT (sin DV) por su factor según posición
2.	Sumar todos los productos
3.	Calcular residuo: suma % 11
4.	Si residuo == 0 → DV = 0
5.	Si residuo == 1 → DV = 1
6.	Si residuo >= 2 → DV = 11 - residuo

Implementación PHP en NitValidator
public static function calcularDv(string $nit): int
{
    $factores = [71, 67, 59, 53, 47, 43, 41, 37, 29];
    $nit = str_pad($nit, 9, "0", STR_PAD_LEFT);
    $suma = 0;
    foreach (str_split($nit) as $i => $digito) {
        $suma += (int)$digito * $factores[$i];
    }
    $residuo = $suma % 11;
    return match(true) {
        $residuo === 0 => 0,
        $residuo === 1 => 1,
        default        => 11 - $residuo,
    };
}

3.2 Tipos de Documento — Resolución DIAN 000042/2020
Código DIAN	Sigla	Nombre oficial
13	CC	Cédula de Ciudadanía
22	CE	Cédula de Extranjería
31	NIT	Número de Identificación Tributaria
11	RC	Registro Civil
12	TI	Tarjeta de Identidad
41	PEP	Permiso Especial de Permanencia
42	PPT	Permiso por Protección Temporal
91	NUIP	Número Único de Identificación Personal
50	NIT Extranjería	NIT para personas jurídicas del exterior
47	PAS	Pasaporte

3.3 Tarifas de Retenciones (config/colombia-fiscal.php)
Las tarifas se almacenan en el archivo de configuración publicable para que cada app las pueda sobreescribir según su actividad económica:

'retefuente' => [
    'honorarios'       => 11.0,  // % sobre valor bruto
    'servicios'        => 4.0,
    'compras'          => 2.5,
    'arrendamientos'   => 4.0,
    'rendimientos'     => 7.0,
],
'reteiva' => [
    'general'          => 15.0,  // % sobre el IVA
],
'reteica' => [
    'industria'        => 0.966, // por mil (varía por municipio)
    'comercio'         => 0.414,
    'servicios'        => 0.966,
],

3.4 Clase Retenciones
La clase Retenciones debe exponer métodos de cálculo reutilizables en cualquier sistema contable:
•	calcularRetefuente(float $base, string $concepto): float
•	calcularReteiva(float $iva, string $concepto = "general"): float
•	calcularReteica(float $base, string $actividad = "comercio"): float
•	calcularTotalRetenciones(float $base, float $iva, array $conceptos): array

4. Plan de Trabajo por Fases
📦	FASE 1 — Scaffold del Package
Estructura base, config publicable y ServiceProvider

Tareas
7.	Crear estructura de carpetas completa según sección 2
8.	Configurar composer.json — este plugin NO requiere moonshine como dependencia obligatoria
9.	Definir en composer.json dos secciones de require: moonshine como suggest (opcional)
10.	Crear FiscalServiceProvider que publique config y registre los campos si MoonShine está presente
11.	Crear config/colombia-fiscal.php con todas las tarifas de sección 3.3

Nota especial — doble uso del plugin
Este plugin debe funcionar como package Laravel puro (para cualquier app) y como plugin MoonShine (con campos UI). El ServiceProvider debe detectar si MoonShine está instalado antes de registrar los campos.

🧮	FASE 2 — NitValidator y NitRule
El corazón del plugin

Tareas
12.	Crear NitValidator.php con método estático calcularDv() según algoritmo de sección 3.1
13.	Agregar método validar(string $nitConDv): bool que verifica NIT completo "900123456-7"
14.	Agregar método formatear(string $nit): string que retorna "900.123.456-7"
15.	Agregar método limpiar(string $nit): string que elimina puntos, guiones y espacios
16.	Crear NitRule.php implementando ValidationRule de Laravel para usar en formularios
17.	La regla debe aceptar NIT con o sin dígito de verificación y con o sin puntuación

📋	FASE 3 — Enums PHP 8.1+
TipoDocumento, RegimenTributario y TipoPersona

Tareas
18.	Crear TipoDocumento.php como Backed Enum (string) con todos los códigos DIAN de sección 3.2
19.	Agregar método label(): string que retorna el nombre legible para mostrar en UI
20.	Agregar método codigoDian(): string que retorna el código numérico DIAN
21.	Crear RegimenTributario.php: responsableIva, noResponsableIva, granContribuyente, regimenSimple
22.	Crear TipoPersona.php: natural, juridica con método requiereNit(): bool

🎛️	FASE 4 — NitField para MoonShine
Campo con cálculo automático del DV

Tareas
23.	Crear NitField.php extendiendo Text de MoonShine
24.	Agregar lógica Alpine.js para calcular el dígito de verificación en tiempo real al escribir
25.	Mostrar el DV calculado como badge junto al campo, actualizado dinámicamente
26.	Agregar método mostrarDv(bool $valor) para mostrar u ocultar el dígito automático
27.	Agregar método formatearAlGuardar(bool $valor) para guardar "900.123.456-7" o "9001234567"
28.	Agregar regla de validación NitRule al campo por defecto
29.	Crear blade nit.blade.php con el campo de texto y el badge de DV

Alpine.js — Cálculo del DV en frontend
x-data="{
  nit: '',
  dv: null,
  calcularDv() {
    const factores = [71,67,59,53,47,43,41,37,29];
    const n = this.nit.replace(/\D/g,'').padStart(9,'0').slice(-9);
    const suma = [...n].reduce((s,d,i) => s + parseInt(d)*factores[i], 0);
    const r = suma % 11;
    this.dv = r < 2 ? r : 11 - r;
  }
}"
x-on:input="calcularDv"

📋	FASE 5 — TipoDocumentoField para MoonShine
Select con todos los tipos DIAN

Tareas
30.	Crear TipoDocumentoField.php extendiendo Select de MoonShine
31.	Poblar opciones desde TipoDocumento enum automáticamente
32.	Agregar método soloPersonaNatural() que filtra a CC, CE, TI, RC, PEP, PPT, PAS
33.	Agregar método soloPersonaJuridica() que muestra NIT, NIT Extranjería
34.	Agregar método conCodigoDian(bool $valor) para mostrar "13 - CC" vs solo "CC"

💰	FASE 6 — Clase Retenciones
Calculadora de retenciones tributarias

Tareas
35.	Crear Retenciones.php con los métodos de sección 3.4
36.	Leer las tarifas desde config("colombia-fiscal.retefuente") para que sean sobreescribibles
37.	Agregar método calcularTotalRetenciones() que retorne array con desglose completo
38.	El array de retorno debe incluir: base, tarifa, monto y concepto de cada retención
39.	Agregar soporte para UVT (Unidad de Valor Tributario) configurable en colombia-fiscal.php

📄	FASE 7 — FacturaDian y ResolucionDianRule
Validación de facturación electrónica

Tareas
40.	Crear FacturaDian.php con método validarResolucion(string $numero): bool
41.	La resolución DIAN tiene formato: 18 dígitos, comenzando con el año de expedición
42.	Crear ResolucionDianRule.php como regla de validación Laravel
43.	Agregar método generarPrefijo(string $resolucion, int $numero): string que genere "SETP990000001"
44.	Agregar validación de rango: número dentro del rango autorizado por la resolución

🧪	FASE 8 — Testing exhaustivo
Especialmente crítico para el algoritmo del NIT

Tareas
45.	Test NIT conocido: 900123456 → DV = 7 (verificar con DIAN)
46.	Test NIT con ceros a la izquierda: 12345 → padding correcto
47.	Test formato: "900.123.456-7" → limpieza → "9001234567"
48.	Test NitRule acepta "900123456-7", "900.123.456-7", "9001234567"
49.	Test NitRule rechaza NIT con DV incorrecto
50.	Test TipoDocumento enum retorna todos los códigos DIAN correctos
51.	Test Retenciones: base 1.000.000 honorarios → retefuente = 110.000
52.	Test NitField renderiza en Resource MoonShine sin errores

📝	FASE 9 — Documentación y Publicación
README completo con ejemplos de todos los componentes

Tareas
53.	README con sección de instalación standalone (sin MoonShine) y con MoonShine
54.	Documentar NitValidator como utilidad PHP reutilizable fuera de MoonShine
55.	Documentar Retenciones con tabla de ejemplos de cálculo
56.	Agregar sección "Actualización de tarifas" explicando cómo publicar y modificar el config
57.	Publicar en GitHub: tu-usuario/moonshine-colombia-fiscal
58.	Registrar en Packagist
59.	Enviar a marketplace de getmoonshine.app/plugins

5. Instrucciones Específicas para Claude Code
5.1 Checkpoints por fase
Fase	Acción	Checkpoint
1	Scaffold	php artisan vendor:publish --tag=colombia-fiscal-config OK
2	NitValidator	NitValidator::calcularDv("900123456") === 7
3	Enums	TipoDocumento::CC->codigoDian() === "13"
4	NitField	Campo renderiza con DV calculado en tiempo real
5	TipoDocField	soloPersonaNatural() no incluye NIT
6	Retenciones	calcularRetefuente(1000000, "honorarios") === 110000
7	FacturaDian	Regla valida/rechaza resoluciones correctamente
8	Tests	php artisan test → todo verde
9	Publicación	composer require instala sin conflictos

5.2 Restricciones de implementación
•	NitValidator debe ser una clase PHP pura sin dependencia de Laravel ni MoonShine
•	Los Enums deben ser PHP 8.1 Backed Enums (string), no arrays ni constantes
•	Las tarifas de retenciones NUNCA deben estar hardcodeadas — siempre desde config()
•	El campo NIT debe guardar solo los dígitos numéricos por defecto (sin puntuación)
•	Usar match() en lugar de switch/if-else donde sea posible (código más limpio)
•	Todo debe ser compatible con PHP 8.2+ y Laravel 10/11/12

5.3 Advertencia sobre actualizaciones fiscales
Las tarifas de retenciones y la UVT cambian anualmente por decreto. El config debe tener la UVT vigente con un comentario indicando el año y el decreto. Documentar en el README que el mantenedor debe actualizar config/colombia-fiscal.php cada año.

6. Entregables Finales
•	Repositorio GitHub: moonshine-colombia-fiscal
•	Package doble uso: standalone Laravel + addon MoonShine
•	NitValidator como utilidad PHP reutilizable (el más valioso del plugin)
•	Enums PHP 8.1 para documentos, regímenes y tipo de persona
•	Clase Retenciones con calculadora configurable
•	Campos NitField y TipoDocumentoField para MoonShine
•	Suite de tests con énfasis en el algoritmo del DV
•	Listado en Packagist y marketplace de getmoonshine.app/plugins

7. Hoja de Ruta de la Serie moonshine-colombia
Plugin	Prioridad	Tiempo	Valor diferencial
municipios	1 — Empezar aquí	2–3 semanas	Datos DANE ya disponibles
fiscal	2 — NitValidator único	2–3 semanas	Doble uso: Laravel + MoonShine
puc	3 — Más complejo	3–5 semanas	Único en todo el ecosistema PHP

Plugin 3 de 3 — Serie moonshine-colombia | Plan generado con Claude
