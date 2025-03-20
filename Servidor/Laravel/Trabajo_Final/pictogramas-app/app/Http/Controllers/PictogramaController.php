<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;
use App\Models\Persona;
use App\Models\Agenda;
use Carbon\Carbon;

class PictogramaController extends Controller
{
    // Ejercicio 1: Listar todos los pictogramas
    public function listarPictogramas()
    {
        $imagenes = Imagen::all();
        return view('pictogramas.listado', ['imagenes' => $imagenes]);
    }
    
    // Ejercicio 2: Formulario para insertar entrada en agenda
    public function formularioAgenda()
    {
        $personas = Persona::all();
        $imagenes = Imagen::all();
        $fechaHoy = Carbon::now()->format('Y-m-d');
        $horaActual = Carbon::now()->format('H:i');
        
        return view('pictogramas.formulario', [
            'personas' => $personas,
            'imagenes' => $imagenes,
            'fechaHoy' => $fechaHoy,
            'horaActual' => $horaActual
        ]);
    }
    
    // Ejercicio 2: Guardar entrada en agenda
    public function guardarAgenda(Request $request)
    {
        $agenda = new Agenda();
        $agenda->fecha = $request->fecha;
        $agenda->hora = $request->hora;
        $agenda->idpersona = $request->persona;
        $agenda->idimagen = $request->actividad;
        $agenda->save();
        
        return redirect()->back()->with('mensaje', 'Dato agendado correctamente');
    }
    
    // Ejercicio 3: Formulario para mostrar agenda
    public function formularioMostrarAgenda()
    {
        $personas = Persona::all();
        $fechaHoy = Carbon::now()->format('Y-m-d');
        
        return view('pictogramas.mostrar-agenda', [
            'personas' => $personas,
            'fechaHoy' => $fechaHoy
        ]);
    }
    
    // Ejercicio 3: Mostrar agenda
    public function mostrarAgenda(Request $request)
    {
        $personas = Persona::all();
        $fechaHoy = $request->fecha;
        
        $agenda = Imagen::select('imagenes.imagen', 'agenda.fecha', 'agenda.hora')
            ->join('agenda', 'imagenes.idimagen', '=', 'agenda.idimagen')
            ->where('agenda.idpersona', $request->persona)
            ->where('agenda.fecha', $request->fecha)
            ->get();
        
        return view('pictogramas.mostrar-agenda', [
            'personas' => $personas,
            'fechaHoy' => $fechaHoy,
            'agenda' => $agenda,
            'personaSeleccionada' => $request->persona
        ]);
    }
}
?>