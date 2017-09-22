<?php

	//MODO DE EJECUCIÓN: php entrenamiento.php <tasa_aprendizaje> <numero_datos_entrada> <num_ciclos>

	include 'adaline.php';
	$tasa_aprendizaje = $argv[1];
	$num_datos_entrada = $argv[2];
	$num_ciclos = $argv[3];


	$adaline = new Adaline($tasa_aprendizaje, $num_datos_entrada);

	$adaline->entrenamiento($num_ciclos);

?>