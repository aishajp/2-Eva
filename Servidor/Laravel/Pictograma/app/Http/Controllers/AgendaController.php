// app/Http/Controllers/AgendaController.php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;
use App\Models\Persona;
use App\Models\Agenda;

class AgendaController extends Controller
{
    // Ejercicio 1: Listado de pictogramas
    public function listadoPictogramas()
    {
        $imagenes = Imagen::all();
        return view('pictogramas.listado', compact('imagenes'));
    }
    
    // Ejercicio 2: Formulario para insertar nueva entrada en la agenda
    public function formularioAgenda()
    {
        $personas = Persona::all();
        $imagenes = Imagen::all();
        return view('agenda.formulario', compact('personas', 'imagenes'));
    }
    
    // Ejercicio 2: Procesamiento del formulario de agenda
    public function guardarAgenda(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'persona' => 'required|exists:personas,idpersona',
            'imagen' => 'required|exists:imagenes,idimagen',
        ]);
        
        Agenda::create([
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'idpersona' => $request->persona,
            'idimagen' => $request->imagen,
        ]);
        
        return redirect()->route('agenda.formulario')->with('success', 'Entrada de agenda guardada correctamente');
    }
    
    // Ejercicio 3: Formulario para consultar agenda
    public function consultarAgendaForm()
    {
        $personas = Persona::all();
        return view('agenda.consultar', compact('personas'));
    }
    
    // Ejercicio 3: Mostrar agenda del día
    public function mostrarAgenda(Request $request)
    {
        $request->validate([
            'persona' => 'required|exists:personas,idpersona',
            'fecha' => 'required|date',
        ]);
        
        $persona = Persona::find($request->persona);
        $fecha = $request->fecha;
        
        $agenda = Agenda::select('imagenes.imagen', 'agenda.fecha', 'agenda.hora')
            ->join('imagenes', 'imagenes.idimagen', '=', 'agenda.idimagen')
            ->where('agenda.idpersona', $request->persona)
            ->where('agenda.fecha', $request->fecha)
            ->get();
            
        return view('agenda.mostrar', compact('agenda', 'persona', 'fecha'));
    }
}