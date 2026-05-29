<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use JamesMosquera\FiscalColombia\Enums\TipoDocumento;

class Contribuyente extends Model
{
    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'nombres',
        'apellidos',
    ];

    protected $casts = [
        'tipo_documento' => TipoDocumento::class,
    ];
}
