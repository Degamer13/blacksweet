<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ganador extends Model
{
    use HasFactory;

    protected $table = 'ganadores';
    protected $fillable = [
        'remate_id',
        'es_ganador',
        'posicion',
        'monto_ganado',
        'porcentaje',
        'total_pagar'
    ];

    public function remate()
    {
        return $this->belongsTo(Remate::class);
    }
}
