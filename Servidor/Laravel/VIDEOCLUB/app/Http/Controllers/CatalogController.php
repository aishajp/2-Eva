<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CatalogController extends Controller
{
    private $arrayPeliculas = [
        [
            'title' => 'El padrino',
            'year' => '1972', 
            'director' => 'Francis Ford Coppola', 
            'poster' => 'https://via.placeholder.com/150x200', 
            'rented' => false, 
            'synopsis' => 'Don Vito Corleone es el respetado y temido jefe de una de las cinco familias de la mafia de Nueva York...'
        ],
        [
            'title' => 'El Señor de los anillos', 
            'year' => '2001', 
            'director' => 'Peter Jackson', 
            'poster' => 'https://via.placeholder.com/150x200', 
            'rented' => true, 
            'synopsis' => 'En la Tierra Media, el Señor Oscuro Sauron forjó los Grandes Anillos del Poder...'
        ],
        // Añade aquí el resto de películas del array_peliculas.php
    ];

    public function getIndex()
    {
        return view('catalog.index', ['arrayPeliculas' => $this->arrayPeliculas]);
    }

    public function getShow($id)
    {
        return view('catalog.show', ['pelicula' => $this->arrayPeliculas[$id], 'id' => $id]);
    }

    public function getCreate()
    {
        return view('catalog.create');
    }

    public function getEdit($id)
    {
        return view('catalog.edit', ['pelicula' => $this->arrayPeliculas[$id], 'id' => $id]);
    }
}