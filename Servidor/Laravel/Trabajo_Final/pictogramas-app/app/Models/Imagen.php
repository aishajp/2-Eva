<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;
    
    protected $table = 'imagenes';
    protected $primaryKey = 'idimagen';
    protected $fillable = ['categoria', 'imagen', 'descripcion'];
    
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'idimagen', 'idimagen');
    }
}