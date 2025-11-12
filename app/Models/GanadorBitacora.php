<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GanadorBitacora extends Model
{
    use HasFactory;

    protected $table = 'ganadores_bitacora';

    protected $fillable = [
        'remate_id',
        'ejemplar_name',
        'cliente',
        'es_ganador',
        'posicion',
        'monto_ganado',
        'porcentaje',
        'total_pagar',
    ];

    public function remate()
    {
        return $this->belongsTo(Remate::class);
    }
}
