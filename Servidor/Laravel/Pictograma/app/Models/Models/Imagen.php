// app/Models/Imagen.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;
    
    protected $table = 'imagenes';
    protected $primaryKey = 'idimagen';
    public $timestamps = false;
    
    protected $fillable = [
        'imagen',
        'ruta'
    ];
    
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'idimagen', 'idimagen');
    }
}

// app/Models/Persona.php
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
        'nombre'
    ];
    
    public function agendas()
    {
        return $this->hasMany(Agenda::class, 'idpersona', 'idpersona');
    }
}

// app/Models/Agenda.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    
    protected $table = 'agenda';
    protected $primaryKey = 'idagenda';
    public $timestamps = false;
    
    protected $fillable = [
        'fecha',
        'hora',
        'idpersona',
        'idimagen'
    ];
    
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'idpersona', 'idpersona');
    }
    
    public function imagen()
    {
        return $this->belongsTo(Imagen::class, 'idimagen', 'idimagen');
    }
}