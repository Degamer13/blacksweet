<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Race extends Model
{
    use HasFactory;

    // Definir los campos que son asignables en masa
    protected $fillable = ['name'];

    // Si necesitas relaciones o métodos personalizados, agrégalos aquí
}
