<?php
/* Identificar entre dos números aleatorios cual es el mayor y si este es par o impar.
Buscar información previamente para generar números aleatorios y usarla para
resolver el ejercicio*/
  $x=rand();
  $y=rand();
  $max=max($x,$y);
  $tipo=$max % 2 ? "impar" : "par";
  echo "Número aleatorio 1: $x<br>";
  echo "Número aleatorio 2: $y<br>";
  echo "El mayor de los dos es $max y es un número $tipo";
?>
