<?php
/* Dados 3 números asignados dentro del código a mostrar el número mayor de los
tres. */
  $num1=100;
  $num2=20;
  $num3=100;
  //$max=max($num1,$num2,$num3);
  echo "Número 1: $num1<br>";
  echo "Número 2: $num2<br>";
  echo "Número 3: $num3<br>";
  if (($num1>=$num2)&($num1>=$num3))
	  echo "El mayor es: $num1<br>";
	  elseif (($num2>=$num1)&($num2>=$num3))
	   echo "El mayor es: $num2<br>";
	     else 
			echo "El mayor es: $num3<br>";

?>
