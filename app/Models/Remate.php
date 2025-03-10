<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remate extends Model
{
    use HasFactory;

    protected $fillable = [
        'number', 'race_id', 'ejemplar_id', 'cliente', 'monto1', 'monto2', 'monto3', 'monto4',
        'total', 'porcentaje', 'pote', 'total_pagar', 'total_subasta',
        'subasta1', 'subasta2', 'subasta3', 'subasta4'
    ];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

    public function ejemplar()
    {
        return $this->belongsTo(Ejemplar::class);
    }
}

