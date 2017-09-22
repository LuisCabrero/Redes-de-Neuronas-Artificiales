<?php

	//MODO DE EJECUCIÓN: php entrenamiento.php <tasa_aprendizaje> <numero_datos_entrada> <fichero_entrada>

	include 'adaline.php';
	$tasa_aprendizaje = $argv[1];
	$num_datos_entrada = $argv[2];
	$file_to_open = $argv[3];
	$file_to_show = $argv[4];


	$adaline = new Adaline($tasa_aprendizaje, $num_datos_entrada);

	$adaline->aprendizaje('data/entrenamiento.csv');
	$adaline->error('data/entrenamiento.csv');
	$adaline->resultados('results/'.$file_to_show);


?>