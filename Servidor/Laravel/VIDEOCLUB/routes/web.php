<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
   // return view('welcome');
   echo "Hola desde Laravel";
});

/*Asi se define la 2 ruta,http://localhost:8080/2ºEva/Servidor/Laravel/VIDEOCLUB/public/pagina1 */ 
route:: get('pagina1', function () {
    echo "Esta es la página 1";
});

/*Asi se define la 3 ruta,http://localhost:8080/2ºEva/Servidor/Laravel/VIDEOCLUB/public/pagina2/1(numero que queramos = id) */
route:: get('pagina2/{id}', function ($id) {
    echo "Usuario ".$id;
}); 
/*Asi se define la 4 ruta,http://localhost:8080/2ºEva/Servidor/Laravel/VIDEOCLUB/public/pagina3 */
Route::get('pagina3/{name?}', function($name = null)
{
return $name;
});
// También podemos poner algún valor por defecto...
Route::get('pagina3_1/{name?}', function($name = 'Aisha')
{
return $name;
});
Route::get('pagina4/{name}', function($name)
{
//
})
->where('name', '[A-Za-z]+');
Route::get('user/{id}', function($id)
{
//
})
->where('id', '[0-9]+');
// Si hay varios parámetros podemos validarlos usando un array:
Route::get('user/{id}/{name}', function($id, $name)
{
//
})
->where(array('id' => '[0-9]+', 'name' => '[A-Za-z]+'))
