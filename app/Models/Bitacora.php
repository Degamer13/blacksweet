<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bitacora extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'race_id', 'ejemplar_name', 'cliente', 'monto1', 'monto2', 'monto3', 'monto4',
        'total', 'porcentaje', 'pote', 'acumulado', 'total_pagar', 'total_subasta',
        'subasta1', 'subasta2', 'subasta3', 'subasta4'
    ];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }
}
