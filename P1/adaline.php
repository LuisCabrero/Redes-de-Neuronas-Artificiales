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
  var $error_global;   			//Error cuadrático medio global.
  var $error_global_validacion; //Error cuadrático medio global en el proceso de validación
  var $error_global_test;		//Error cuadrático medio global en test.
  var $num_datos_entrada;   	//Número de datos de entrada.
  var $array_errores_ent;		//Array para guardar los errores globales por cada ciclo.
  var $array_errores_val;		//Array para guardar los errores globales de validación por cada ciclo.  
  var $array_salidas_test;		//Array para guardar las salidas desnormalizadas en proceso de test.
  var $array_salidas_d_test;	//Array para guardar las salidas deseadas en proceso de test.
  var $total_test;				//Número total de datos de test.
  var $sal_ent_desn;			//Array para ir almacenando las salidas desnormalizadas de entrenamiento.
  var $sal_ent_nor;				//Array para ir almacenando las salidas normalizadas de entrenamiento.
  var $sal_val_desn;			//Array para ir almacenando las salidas desnormalizadas de validación.
  var $sal_val_nor;				//Array para ir almacenando las salidas normalizadas de validación.

  // Método constructor y que inicializa los pesos y umbral aleatoriamente.
  public function __construct($tasa_aprendizaje) {

  	if($tasa_aprendizaje < 0 || $tasa_aprendizaje > 1){
  		echo 'La tasa de aprendizaje debe ser un número real comprendido entre 0 y 1' . PHP_EOL;
  		exit;
  	}

    $this->tasa_aprendizaje = $tasa_aprendizaje;
    $this->tasa_aprendizaje+=0;
    $this->w = array();

    //Inicialización aleatoria de los pesos y umbral
    for ($i=0; $i < 8; $i++) { 
		$this->w[$i] = rand(-5,5)/10;
	}
	$this->umbral = rand(-5,5)/10;
	$this->error = 0;
	$this->error_global = 0;

	$this->total_test = 0;

	$this->array_errores_ent = array();
	$this->array_errores_val = array();
	$this->array_salidas_test = array();
	$this->array_salidas_d_test = array();

	$this->sal_ent_desn = array();
	$this->sal_ent_nor = array();
	$this->sal_val_desn = array();
	$this->sal_val_nor = array();

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

		$numero_elementos = count($linea);

		$salida_deseada = floatval ($linea[$numero_elementos - 1]);
		$salida_obtenida = 0;

		$entradas = array();
		for ($i=0; $i < $numero_elementos - 1; $i++) { 
			$entradas[$i] = $linea[$i];
			$entradas[$i] = floatval ($entradas[$i]);
		}

		$contador = 0;
		foreach ($entradas as $entrada) {
			$salida_obtenida += $this->w[$contador]*$entrada;
			$contador++;
		}
		$salida_obtenida += $this->umbral;

		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		//Para cada uno de los pesos ajustamos.
		$contador = 0;
		foreach ($this->w as $peso) {
			$incremento_peso = $this->tasa_aprendizaje * $this->error * $entradas[$contador];
			$this->w[$contador] += $incremento_peso;
			$contador++;
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

	$numero_errores = 0;

	while (($linea = fgetcsv($file, 1000, ";")) !== false) {

		$numero_elementos = count($linea);

		$salida_deseada = $linea[$numero_elementos - 1];
		$salida_obtenida = 0;
		$contador = 0;

		$entradas = array();
		for ($i=0; $i < $numero_elementos - 1; $i++) { 
			$entradas[$i] = $linea[$i];
			$entradas[$i] = floatval ($entradas[$i]);
		}

		foreach ($entradas as $entrada) {
			$salida_obtenida += $this->w[$contador]*$entrada;
			$contador++;	
		}
		$salida_obtenida += $this->umbral;

		$file2 = fopen('data/desnormalizar.csv', "r");
		$maxmin = fgetcsv($file2, 1000, ";");
		$s_desn = $salida_obtenida*($maxmin[0] - $maxmin[1]) + $maxmin[1];
		array_push($this->sal_ent_desn, $s_desn);
		array_push($this->sal_ent_nor, $salida_obtenida);

		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		$this->error_global +=  pow($this->error , 2);

		$numero_errores++;
	}

	$this->error_global = $this->error_global/$numero_errores;




	fclose($file);

  }

  // Método para obtener el error producido por el conjunto de validación
  public function errorvalidacion($file_to_open, $ciclo){

  	//Hallamos el error global.
	//Abrimos el entrenamiento.
	$file = fopen($file_to_open, "r");

	if($file == false){
		echo 'No se ha podido abrir el archivo';
		exit;
	}

	$numero_errores = 0;

	while (($linea = fgetcsv($file, 1000, ";")) !== false) {

		$numero_elementos = count($linea);

		$salida_deseada = floatval($linea[$numero_elementos - 1]);
		$salida_obtenida = 0;
		$contador = 0;

		$entradas = array();
		for ($i=0; $i < $numero_elementos - 1; $i++) { 
			$entradas[$i] = $linea[$i];
		}

		foreach ($entradas as $entrada) {
			$salida_obtenida += $this->w[$contador]*$entrada;
			$contador++;	
		}
		$salida_obtenida += $this->umbral;

		$file2 = fopen('data/desnormalizar.csv', "r");
		$maxmin = fgetcsv($file2, 1000, ";");
		$s_desn = $salida_obtenida*($maxmin[0] - $maxmin[1]) + $maxmin[1];
		array_push($this->sal_val_desn, $s_desn);
		array_push($this->sal_val_nor, $salida_obtenida);

		
		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		$this->error_global_validacion +=  pow($this->error , 2);

		$numero_errores++;
	}

	$this->error_global_validacion = $this->error_global_validacion/$numero_errores;

	fclose($file);



  }

  // Método para obtener el error producido posteriormente al aprendizaje.
  public function errortest($file_to_open){

  	//Hallamos el error global.
	//Abrimos el entrenamiento.
	$file = fopen($file_to_open, "r");

	if($file == false){
		echo 'No se ha podido abrir el archivo';
		exit;
	}

	$numero_errores = 0;

	$this->patrones_test = 0;

	while (($linea = fgetcsv($file, 1000, ";")) !== false) {

		$numero_elementos = count($linea);

		$salida_deseada = floatval($linea[$numero_elementos - 1]);
		$salida_obtenida = 0;
		$contador = 0;

		$entradas = array();
		for ($i=0; $i < $numero_elementos - 1; $i++) { 
			$entradas[$i] = $linea[$i];
		}

		foreach ($entradas as $entrada) {
			$salida_obtenida += $this->w[$contador]*$entrada;
			$contador++;	
		}
		$salida_obtenida += $this->umbral;

		/*Desnormalizamos la salida obtenida y la almacenamos en el array */

		$file2 = fopen('data/desnormalizar.csv', "r");
		$maxmin = fgetcsv($file2, 1000, ";");
		$s_desn = $salida_obtenida*($maxmin[0] - $maxmin[1]) + $maxmin[1];
		array_push($this->array_salidas_test, $s_desn);
		$sDes_desn = $salida_deseada*($maxmin[0] - $maxmin[1]) + $maxmin[1];

		array_push($this->array_salidas_d_test, $sDes_desn);

		//Calculamos el error.
		$error_test = $salida_deseada - $salida_obtenida;
		$this->error_global_test +=  pow($error_test , 2);

		$numero_errores++;

		$this->total_test++;
	}

	$this->error_global_test = $this->error_global_test/$numero_errores;

	fclose($file);



  }

  // Método para mostrar los resultados del aprendizaje
  public function resultados($folder, $ciclo, $num_ciclos){

  	//Creamos una carpeta para estos parámetros si no existe.
  	if(!file_exists (dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos)){
  		mkdir(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos, 0755);
  	}

  	$resultado = fopen(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos.'/resultados ciclo '.$ciclo.'.html', "w");
  	$html =
	 '
		<style>
			.tabla_adaline{
				width:80%;
				margin:0 auto;
			}
			table, td, th {    
			    border: 1px solid #ddd;
			    text-align: left;
			}

			table {
			    border-collapse: collapse;
			    width: 100%;
			}

			th, td {
			    padding: 15px;
			}
		</style>

		<html>
			<head>
			  <title>Resultados Adaline ciclo '.$ciclo.'</title>
			</head>
			<body>
				 <div style="margin:0 auto;"><h1> Salidas Adaline ciclo '.$ciclo.'</h1></div>
				 <table class="tabla_adaline">
					  <tr>
					    <th colspan="8"><h2> Salidas entrenamiento</h2></th>
					  </tr>
					  <tr>
					    <th>Salida entrenamiento normalizada</th>
					    <th>Salida entrenamiento desnormalizada</th>
					  </tr>

					  ';

					   for ($i=0; $i < count($this->sal_ent_nor); $i++) { 
					   	$html .= '

							  <tr>
							    <td>'.$this->sal_ent_nor[$i].'</td>
							    <td>'.$this->sal_ent_desn[$i].'</td>
							  </tr>

					   	';
					   }

					 
	$html .= ' </table>

			   <table class="tabla_adaline">
					  <tr>
					    <th colspan="8"><h2> Salidas validación</h2></th>
					  </tr>
					  <tr>
					    <th>Salida validación normalizada</th>
					    <th>Salida validación desnormalizada</th>
					  </tr>

					  ';

					   for ($i=0; $i < count($this->sal_val_nor); $i++) { 
					   	$html .= '

							  <tr>
							    <td>'.$this->sal_val_nor[$i].'</td>
							    <td>'.$this->sal_val_desn[$i].'</td>
							  </tr>

					   	';
					   }
			   
	$html .= ' </table>

			</body>
		</html>
	 ';
	
	fwrite($resultado, $html);

	fclose($resultado);

  }


  // Método para mostrar los errores globales de entrenamiento y validación en una tabla,
  public function mostrarerrores($folder, $num_ciclos){

  	//Creamos una carpeta para estos parámetros si no existe.
  	if(!file_exists (dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos)){
  		mkdir(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos, 0755);
  	}

  	$resultado = fopen(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos.'/data.html', "w");

  	$html =
	 '
		<style>
			.tabla_adaline{
				width:80%;
				margin:0 auto;
			}
			table, td, th {    
			    border: 1px solid #ddd;
			    text-align: left;
			}

			table {
			    border-collapse: collapse;
			    width: 100%;
			}

			th, td {
			    padding: 15px;
			}
		</style>

		<html>
			<head>
			  <title>Datos Adaline</title>


				<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
				<script type="text/javascript">
			      google.charts.load("current", {"packages":["corechart"]});
			      google.charts.setOnLoadCallback(drawChart);

			      function drawChart() {
			        var data = google.visualization.arrayToDataTable([
			          ["Cycle", "Training error", "Validation error"],';


	for ($i=0; $i < $num_ciclos; $i++) { 
		if($i == $num_ciclos -1){
			$html .='[ '.$i.',      '.$this->array_errores_ent[$i].',       '.$this->array_errores_val[$i].']';
		}else{
			$html .='[ '.$i.',      '.$this->array_errores_ent[$i].',       '.$this->array_errores_val[$i].'],';
		}
		
	}

	$html .= '
			        ]);

			        var options = {
			          title: "Errores por ciclo",
			          curveType: "function",
			          legend: { position: "bottom" }
			        };

			        var chart = new google.visualization.LineChart(document.getElementById("curve_chart"));

			        chart.draw(data, options);
			      }
			    </script>

			</head>
			<body>
				 <div style="margin:0 auto;"><h1>Errores producidos en proceso entrenamiento Adaline ('.$this->tasa_aprendizaje.','.$num_ciclos.')</h1></div>

				  <div style="margin:0 auto;"><h3>Error sobre conjunto de test: '.$this->error_global_test.'</h3></div>

				 <div id="curve_chart" style="width: 900px; height: 500px;"></div>


				 <table class="tabla_adaline">
					  <tr>
					    <th><h2> Salidas obtenidas desnormalizadas en test </h2></th>
					  </tr>';

	for ($i=0; $i < $this->total_test; $i++) { 
		
		$html .= 
			'<tr>
	   	       <td>'.$this->array_salidas_test[$i].'</td>
		     </tr>';
	}

	$html .=
	 '		  </table>
				
				<br><br>

				 <table class="tabla_adaline" style="margin: 0 auto; width:80%;">
					  <tr>
					    <th colspan="8"><h2> Pesos </h2></th>
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
					    <th>Umbral</th>
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
					    <td>'.$this->umbral.'</td>
					  </tr>
				 </table>

				<br><br>

				 <table class="tabla_adaline">
					  <tr>
					    <th><h2> Ciclo </h2></th>
					    <th><h2> Error medio de entrenamiento </h2></th>
					    <th><h2> Error medio de validacion </h2></th>
					  </tr>';

	for ($i=0; $i < $num_ciclos; $i++) { 
		
		$html .= 
			'<tr>
			   <td>'.$i.'</td>
			   <td>'.$this->array_errores_ent[$i].'</td>
	   	       <td>'.$this->array_errores_val[$i].'</td>
		     </tr>';
	}

	$html .=
	 '		  </table>
		 		 
			</body>
		</html>
	 ';
	
	fwrite($resultado, $html);

	fclose($resultado);
  }

  // Método para mostrar los datos de test.
  public function datostest($folder, $num_ciclos){

  	//Creamos una carpeta para estos parámetros si no existe.
  	if(!file_exists (dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos)){
  		mkdir(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos, 0755);
  	}

  	$resultado = fopen(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos.'/testdata.html', "w");

  	//Hallamos el error basándonos en las salidas sin normalizar.

  	$error_sin_normalizar = 0;
  	for ($i=0; $i < $this->total_test; $i++) { 
  		$error = floatval($this->array_salidas_test[$i]) - floatval($this->array_salidas_d_test[$i]);
  		$error_sin_normalizar += pow($error, 2);
  	}
  	$error_sin_normalizar = $error_sin_normalizar/$this->total_test;

  	$html =
	 '
		<style>
			.tabla_adaline{
				width:80%;
				margin:0 auto;
			}
			table, td, th {    
			    border: 1px solid #ddd;
			    text-align: left;
			}

			table {
			    border-collapse: collapse;
			    width: 100%;
			}

			th, td {
			    padding: 15px;
			}
		</style>

		<html>
			<head>
			  <title>Datos Test Adaline</title>


				<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
				<script type="text/javascript">
			      google.charts.load("current", {"packages":["corechart"]});
			      google.charts.setOnLoadCallback(drawChart);

			      function drawChart() {
			        var data = google.visualization.arrayToDataTable([
			          ["Pattern", "Salida obtenida", "Salida deseada"],';


	for ($i=0; $i < $this->total_test; $i++) { 
		if($i == $num_ciclos -1){
			$html .='[ '.$i.',      '.$this->array_salidas_test[$i].',       '.$this->array_salidas_d_test[$i].']';
		}else{
			$html .='[ '.$i.',      '.$this->array_salidas_test[$i].',       '.$this->array_salidas_d_test[$i].'],';
		}
		
	}

	$html .= '
			        ]);

			        var options = {
			          title: "Salidas por patrón",
			          curveType: "function",
			          legend: { position: "bottom" }
			        };

			        var chart = new google.visualization.LineChart(document.getElementById("curve_chart"));

			        chart.draw(data, options);
			      }
			    </script>

			</head>
			<body>
				 <div style="margin:0 auto;"><h1>Resultados test Adaline ('.$this->tasa_aprendizaje.','.$num_ciclos.')</h1></div>

				  <div style="margin:0 auto;"><h3>Error sobre conjunto de test: '.$this->error_global_test.'</h3></div>
				  <div style="margin:0 auto;"><h3>Error sin normalizar sobre conjunto de test: '.$error_sin_normalizar.'</h3></div>
				 <div id="curve_chart" style="width: 900px; height: 500px;"></div>
		 		 
			</body>
		</html>
	';
	
	fwrite($resultado, $html);

	fclose($resultado);
  }

  // Método para mostrar los resultados del aprendizaje
  public function ejecutaradaline($num_ciclos){
  	for ($i=1; $i <= $num_ciclos; $i++) { 
		//Aprendizaje de la red
		$this->aprendizaje('data/entrenamiento.csv', $i);
		$this->error('data/entrenamiento.csv', $i);
		//Validación
		$this->errorvalidacion('data/validacion.csv', $i);

		//$this->resultados('results', $i, $num_ciclos);

		array_push($this->array_errores_ent, $this->error_global);
		array_push($this->array_errores_val, $this->error_global_validacion);
	}

	$this->errortest('data/test.csv');

	$this->mostrarerrores('results', $num_ciclos);
  	$this->datostest('results', $num_ciclos);
  }

}