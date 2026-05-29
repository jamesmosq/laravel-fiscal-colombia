<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JamesMosquera\FiscalColombia\Enums\RegimenTributario;

class Empresa extends Model
{
    protected $fillable = [
        'razon_social',
        'nit',
        'dv',
        'regimen_tributario',
    ];

    protected $casts = [
        'regimen_tributario' => RegimenTributario::class,
    ];
}
