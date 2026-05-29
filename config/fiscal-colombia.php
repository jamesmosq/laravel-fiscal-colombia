<?php

return [

    /*
     * Tarifas de retención en la fuente (retefuente).
     * Valores en porcentaje. Configurables por el usuario.
     */
    'retefuente' => [
        'honorarios'    => 11.0,
        'servicios'     => 4.0,
        'compras'       => 2.5,
        'arrendamiento' => 4.0,
        'rendimientos'  => 7.0,
    ],

    /*
     * Tarifa de retención de IVA (reteiva).
     */
    'reteiva' => [
        'general' => 15.0,
    ],

    /*
     * Tarifas de retención de ICA (reteica) en por mil (‰).
     */
    'reteica' => [
        'industria' => 0.966,
        'comercio'  => 0.414,
        'servicios' => 0.966,
    ],

];
