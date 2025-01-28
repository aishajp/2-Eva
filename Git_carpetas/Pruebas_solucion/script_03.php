<?php
/* Determinar la cantidad de dinero que recibirá un trabajador por concepto de las
horas extras trabajadas en una empresa, sabiendo que cuando las horas de
trabajo exceden de 40, el resto se consideran horas extras y que estas se pagan al
doble de una hora normal cuando no exceden de 8; si las horas extras exceden de
8 se pagan las primeras 8 al doble de lo que se pagan las horas normales y el resto
al triple*/
  define("JORNADA",40);   //Jornada laboral
  define("PHORA",50);    //Paga por hora normal
  $horas=55; //Horas trabajadas
  $dif=$horas-JORNADA;
  if ($dif>8) {
    $paga=8*PHORA*2+($dif-8)*PHORA*3;
  } else {
    $paga=$dif*PHORA*2;
  }
  echo "Horas trabajadas: $horas<br>";
  echo "Horas extra: $dif<br>";
  echo "Dinero extra: $paga";
?>
