<?php
/* Calcular si un número entero generado de forma aleatoria entre 1 y 1000 es
perfecto. Un número perfecto es aquel que la suma de sus divisores es él mismo,
por ejemplo el 6, sus divisores son 1,2,3 la suma de los mismos es 6 */
  $num=rand(1,1000);
  
  $sum=1;
  for ($i=2; $i<$num; $i++) {
    if ($num % $i==0) { 
        $sum += $i;
    }
  }

  if ($sum == $num && $num != 1) {
    echo "$num es perfecto";
  } else {
    echo "$num NO es perfecto";
  }

?>
