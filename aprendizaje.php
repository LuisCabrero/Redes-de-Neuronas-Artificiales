<?php


$tasa_aprendizaje = $argv[1];

//Array de pesos inicializado aleatoriamente.

$w = array();
for ($i=0; $i < 7; $i++) { 
	$w[$i] = rand(0,1);
}

//Umbral inicializado aleatoriamente
$umbral = rand(-0.5,0.5);

$error = null;
$error_global = null;

//Abrimos el entrenamiento.
$file = fopen("entrenamiento.csv", "r");

if($file == false){
	echo 'No se ha podido abrir el archivo';
	exit;
}

while (($linea = fgetcsv($file, 1000, ";")) !== false) {

	$salida_deseada = $linea[8];
	$salida_obtenida = 0;
	$contador = 0;

	$entradas = array();
	for ($i=0; $i < 7; $i++) { 
		$entradas[$i] = $linea[$i];
	}

	foreach ($entradas as $entrada) {
		$salida_obtenida += $w[$contador]*$entrada;		
	}
	$salida_obtenida += $umbral;

	//Calculamos el error.
	$error = $salida_deseada - $salida_obtenida;
	//Para cada uno de los pesos ajustamos.
	$contador = 0;
	foreach ($w as $peso) {
		$incremento_peso = $tasa_aprendizaje * $error * $entradas[$contador];
		$w[$contador] += $incremento_peso;
	}

	//Calcular incremento del umbral.
	$incremento_umbral = $tasa_aprendizaje * $error;
	$umbral += $incremento_umbral;

	$error_global +=  pow($error , 2);
}

var_dump($w);
var_dump($umbral);
var_dump($error);exit;


//VOLVER A RECORRER SIN ALTERAR PESOS Y CALCULAR ERROR.

$resultado = fopen("resultado_entrenamiento.txt", "w");
fwrite($resultado, implode(";", $w)); 
fwrite($resultado, $umbral); 
fwrite($resultado, $error_global); 

fclose($file);


?>
