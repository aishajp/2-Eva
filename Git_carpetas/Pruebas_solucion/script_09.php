<?php
/* Hacer un programa que genere un numero aleatorio y compruebe si es primo.
 Un número primo es un número entero que sólo es
divisible por 1 y por sí mismo */
  $num=rand(1,100);

  $esprimo=true;
  if ($num==1) {
    $esprimo=false;
  } else {
    for ($i=2; $i<=$num/2; $i++) {
      if ($num % $i == 0) {
        $esprimo=false;
      }
    }
  }
  if ($esprimo) {
    echo "$num es primo";
  } else {
    echo "$num NO es primo";
  }
?>
