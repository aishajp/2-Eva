<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    
    protected $table = 'agenda';
    protected $fillable = ['fecha', 'hora', 'idpersona', 'idimagen'];
    
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'idpersona', 'idpersona');
    }
    
    public function imagen()
    {
        return $this->belongsTo(Imagen::class, 'idimagen', 'idimagen');
    }
}