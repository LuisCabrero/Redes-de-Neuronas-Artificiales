<?php

/*
* CLASE:        Adaline
* DESCRIPCIÓN:  Implementa el uso de este tipo de red neuronal
* AUTOR:        Luis Cabrero García.
* FECHA:        22/09/2017
*/

class Adaline {

  var $tasa_aprendizaje;    	//Controla el cambio que sufren los pesos de una iteración a otra (entre 0 y 1)
  var $w;  						//Array de pesos
  var $umbral;     				//Umbral
  var $error;   				//Error producido en un patrón.
  var $error_global;   			//Error cuadrático global.
  var $error_global_validacion; //Error cuadrático global en el proceso de validación
  var $num_datos_entrada;   	//Número de datos de entrada.

  // Método constructor y que inicializa los pesos y umbral aleatoriamente.
  public function __construct($tasa_aprendizaje, $num_datos_entrada) {

  	if($tasa_aprendizaje < 0 || $tasa_aprendizaje > 1){
  		return 'La tasa de aprendizaje debe ser un número real comprendido entre 0 y 1';
  	}

    $this->tasa_aprendizaje = $tasa_aprendizaje;
    $this->w = array();

    //Inicialización aleatoria de los pesos y umbral
    for ($i=0; $i < $num_datos_entrada; $i++) { 
		$this->w[$i] = rand(-5,5)/10;
	}
	$this->umbral = rand(-5,5)/10;
	$this->error = 0;
	$this->error_global = 0;

  }

  // Método de aprendizaje de la red.
  public function aprendizaje($file_to_open, $ciclo){

  	//Abrimos el entrenamiento.
	$file = fopen($file_to_open, "r");

	if($file == false){
		echo 'No se ha podido abrir el archivo';
		exit;
	}

	while (($linea = fgetcsv($file, 1000, ";")) !== false) {

		$salida_deseada = $linea[8];
		$salida_obtenida = 0;

		$entradas = array();
		for ($i=0; $i < 7; $i++) { 
			$entradas[$i] = $linea[$i];
		}

		$contador = 0;
		foreach ($entradas as $entrada) {
			$salida_obtenida += $this->w[$contador]*$entrada;		
		}
		$salida_obtenida += $this->umbral;

		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		//Para cada uno de los pesos ajustamos.
		$contador = 0;
		foreach ($this->w as $peso) {
			$incremento_peso = $this->tasa_aprendizaje * $this->error * $entradas[$contador];
			$this->w[$contador] += $incremento_peso;
		}

		//Calcular incremento del umbral.
		$incremento_umbral = $this->tasa_aprendizaje * $this->error;
		$this->umbral += $incremento_umbral;
	}
	fclose($file);

  }

  // Método para obtener el error producido posteriormente al aprendizaje.
  public function error($file_to_open, $ciclo){

  	//Hallamos el error global.
	//Abrimos el entrenamiento.
	$file = fopen($file_to_open, "r");

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
			$salida_obtenida += $this->w[$contador]*$entrada;		
		}
		$salida_obtenida += $this->umbral;

		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		$this->error_global +=  pow($this->error , 2);

	}
	fclose($file);

  }

  // Método para obtener el error producido en la validación
  public function errorvalidacion($file_to_open, $ciclo){

  	//Hallamos el error global.
	//Abrimos el entrenamiento.
	$file = fopen($file_to_open, "r");

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
			$salida_obtenida += $this->w[$contador]*$entrada;		
		}
		$salida_obtenida += $this->umbral;

		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		$this->error_global_validacion +=  pow($this->error , 2);

	}
	fclose($file);

  }

  // Método para mostrar los resultados del aprendizaje
  public function resultados($file_to_show, $ciclo){

  	$resultado = fopen($file_to_show, "w");
  	$html =
	 '
		<style>
			body {
				background: #fafafa url(https://jackrugile.com/images/misc/noise-diagonal.png);
				color: #444;
				font: 100%/30px "Helvetica Neue", helvetica, arial, sans-serif;
				text-shadow: 0 1px 0 #fff;
			}

			strong {
				font-weight: bold; 
			}

			em {
				font-style: italic; 
			}

			table {
				background: #f5f5f5;
				border-collapse: separate;
				box-shadow: inset 0 1px 0 #fff;
				font-size: 12px;
				line-height: 24px;
				margin: 30px auto;
				text-align: left;
				width: 800px;
			}	

			th {
				background: url(https://jackrugile.com/images/misc/noise-diagonal.png), linear-gradient(#777, #444);
				border-left: 1px solid #555;
				border-right: 1px solid #777;
				border-top: 1px solid #555;
				border-bottom: 1px solid #333;
				box-shadow: inset 0 1px 0 #999;
				color: #fff;
			  font-weight: bold;
				padding: 10px 15px;
				position: relative;
				text-shadow: 0 1px 0 #000;	
			}

			th:after {
				background: linear-gradient(rgba(255,255,255,0), rgba(255,255,255,.08));
				content: '';
				display: block;
				height: 25%;
				left: 0;
				margin: 1px 0 0 0;
				position: absolute;
				top: 25%;
				width: 100%;
			}

			th:first-child {
				border-left: 1px solid #777;	
				box-shadow: inset 1px 1px 0 #999;
			}

			th:last-child {
				box-shadow: inset -1px 1px 0 #999;
			}

			td {
				border-right: 1px solid #fff;
				border-left: 1px solid #e8e8e8;
				border-top: 1px solid #fff;
				border-bottom: 1px solid #e8e8e8;
				padding: 10px 15px;
				position: relative;
				transition: all 300ms;
			}

			td:first-child {
				box-shadow: inset 1px 0 0 #fff;
			}	

			td:last-child {
				border-right: 1px solid #e8e8e8;
				box-shadow: inset -1px 0 0 #fff;
			}	

			tr {
				background: url(https://jackrugile.com/images/misc/noise-diagonal.png);	
			}

			tr:nth-child(odd) td {
				background: #f1f1f1 url(https://jackrugile.com/images/misc/noise-diagonal.png);	
			}

			tr:last-of-type td {
				box-shadow: inset 0 -1px 0 #fff; 
			}

			tr:last-of-type td:first-child {
				box-shadow: inset 1px -1px 0 #fff;
			}	

			tr:last-of-type td:last-child {
				box-shadow: inset -1px -1px 0 #fff;
			}	

			tbody:hover td {
				color: transparent;
				text-shadow: 0 0 3px #aaa;
			}

			tbody:hover tr:hover td {
				color: #444;
				text-shadow: 0 1px 0 #fff;
			}
		</style>

		<html>
			<head>
			  <title>Resultados entrenamiento Adaline</title>
			</head>
			<body>
				 <table class="tabla_adaline">
					  <tr>
					    <th><h1> Pesos </h1></th>
					  </tr>
					  <tr>
					    <th>w0</th>
					    <th>w1</th>
					    <th>w2</th>
					    <th>w3</th>
					    <th>w4</th>
					    <th>w5</th>
					    <th>w6</th>
					    <th>w7</th>
					  </tr>
					  <tr>
					    <td>'.$this->w[0].'</td>
					    <td>'.$this->w[1].'</td>
					    <td>'.$this->w[2].'</td>
					    <td>'.$this->w[3].'</td>
					    <td>'.$this->w[4].'</td>
					    <td>'.$this->w[5].'</td>
					    <td>'.$this->w[6].'</td>
					    <td>'.$this->w[7].'</td>
					  </tr>
				 </table>
		 		 <br>
		 		 <table style="width:80%;margin:0 auto;">
				   <tr>
				     <th><h1> Error </h1></th>
				   </tr>
				   <tr>
				     <td>'.$this->error_global.'</td>
				   </tr>
				 </table>
				 <br>
				 <table style="width:80%;margin:0 auto;">
				   <tr>
				     <th><h1> Umbral </h1></th>
				   </tr>
				   <tr>
				     <td>'.$this->umbral.'</td>
				   </tr>
				 </table>
				 <br>
			</body>
		</html>
	 ';
	
	fwrite($resultado, $html);

	fclose($resultado);

  }


  // Método para mostrar los resultados del aprendizaje
  public function entrenamiento($num_ciclos){
  	for ($i=1; $i <= $num_ciclos; $i++) { 
		//Aprendizaje de la red
		$this->aprendizaje('data/entrenamiento.csv', $i);
		$this->error('data/entrenamiento.csv', $i);
		$this->resultados('results/resultados - ciclo '.$i.'.html', $i);

		//Validación
		$this->errorvalidacion('data/validacion.csv', $i);
	}
  }

}