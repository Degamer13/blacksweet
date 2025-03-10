<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    // Definir los campos que son asignables en masa
    protected $fillable = ['name'];

    // Relación con EjemplarRace
    public function ejemplarRaces()
    {
        return $this->hasMany(EjemplarRace::class, 'race_id');
    }

    // Eliminar en cascada cuando se elimine una carrera
    protected static function booted()
    {
        static::deleting(function ($race) {
            // Eliminar todos los ejemplares asociados cuando se elimina la carrera
            $race->ejemplarRaces()->delete();
        });
    }
       // Relación con Ejemplar a través de la tabla ejemplar_race
    public function ejemplars()
    {
        return $this->hasManyThrough(
            Ejemplar::class,   // Modelo relacionado
            EjemplarRace::class, // Modelo intermedio
            'race_id',          // Clave foránea en la tabla intermedia
            'id',               // Clave primaria en la tabla ejemplar
            'id',               // Clave primaria en la tabla carreras
            'ejemplar_id'       // Clave foránea en la tabla intermedia
        );
    }
}
