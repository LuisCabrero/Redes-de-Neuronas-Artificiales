<?php

	//MODO DE EJECUCIÓN: php entrenamiento.php <tasa_aprendizaje> <num_ciclos>

	include 'adaline.php';
	$tasa_aprendizaje = $argv[1];
	$num_ciclos = $argv[2];


	$adaline = new Adaline($tasa_aprendizaje);

	$adaline->entrenamiento($num_ciclos);

?>