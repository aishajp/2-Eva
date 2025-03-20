<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Imagen;
use App\Models\Persona;
use App\Models\Agenda;

class PictogramaController extends Controller
{
    // Ejercicio 1: Listado de pictogramas
    public function index()
    {
        $imagenes = Imagen::all();
        return view('pictogramas.index', compact('imagenes'));
    }

    // Ejercicio 2: Formulario para insertar nueva entrada en la agenda
    public function create()
    {
        $personas = Persona::all();
        $imagenes = Imagen::all();
        $fechaHoy = date('Y-m-d');
        $hora = date('H:i');
        
        return view('pictogramas.create', compact('personas', 'imagenes', 'fechaHoy', 'hora'));
    }

    // Ejercicio 2: Guardar nueva entrada en la agenda
    public function store(Request $request)
    {
        $agenda = new Agenda();
        $agenda->fecha = $request->fecha;
        $agenda->hora = $request->hora;
        $agenda->idpersona = $request->persona;
        $agenda->idimagen = $request->actividad;
        $agenda->save();
        
        return redirect()->route('pictogramas.create')->with('success', 'Dato agendado correctamente');
    }

    // Ejercicio 3: Formulario para mostrar la agenda de un día
    public function showAgenda()
    {
        $personas = Persona::all();
        $fechaHoy = date('Y-m-d');
        
        return view('pictogramas.agenda', compact('personas', 'fechaHoy'));
    }

    // Ejercicio 3: Mostrar la agenda de un día seleccionado
    public function filterAgenda(Request $request)
    {
        $personas = Persona::all();
        $fechaHoy = $request->fecha ?? date('Y-m-d');
        
        $agenda = Agenda::select('imagenes.imagen', 'agenda.fecha', 'agenda.hora')
            ->join('imagenes', 'imagenes.idimagen', '=', 'agenda.idimagen')
            ->where('agenda.idpersona', $request->persona)
            ->where('agenda.fecha', $request->fecha)
            ->get();
        
        return view('pictogramas.agenda', compact('personas', 'fechaHoy', 'agenda'));
    }
}