<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ejemplar extends Model
{
        use HasFactory;

    // Definir los campos que son asignables en masa
    protected $fillable = ['name'];

 public function ejemplarRaces()
{
    return $this->hasMany(EjemplarRace::class, 'ejemplar_id');
}
}
