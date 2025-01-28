<?php
/* Dados 2 números asignados dentro del código a variables realizar el siguiente
cálculo: si son iguales que los multiplique, si el primero es mayor que el segundo
que los reste y si no que los sume. Mostrar el resultado en pantalla.*/
  $num1=70;
  $num2=70;
  if ($num1==$num2) {
    $result=$num1*$num2;
  } else if ($num1>$num2) {
    $result=$num1-$num2;
  } else {
    $result=$num1+$num2;
  }
  echo "Número 1: $num1<br>";
  echo "Número 2: $num2<br>";
  echo "Resultado: $result<br>";
?>
