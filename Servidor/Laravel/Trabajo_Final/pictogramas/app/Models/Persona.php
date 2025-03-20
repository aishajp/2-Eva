<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    
    protected $table = 'personas';
    protected $primaryKey = 'idpersona';
    public $timestamps = false;
    
    protected $fillable = [
        'nombre',
        'apellidos',
        'edad'
    ];
    
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'idpersona', 'idpersona');
    }
}