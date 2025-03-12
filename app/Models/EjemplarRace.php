<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EjemplarRace extends Model
{
    use HasFactory;

    protected $table = 'ejemplar_race';

    protected $fillable = ['race_id', 'ejemplar_name', 'status'];

    public function race()
    {
        return $this->belongsTo(Race::class);
    }

}
