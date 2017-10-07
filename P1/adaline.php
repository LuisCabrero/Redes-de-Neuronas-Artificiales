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
  var $array_errores_ent;		//Array para guardar los errores globales por cada ciclo.
  var $array_errores_val;		//Array para guardar los errores globales de validación por cada ciclo.  

  // Método constructor y que inicializa los pesos y umbral aleatoriamente.
  public function __construct($tasa_aprendizaje) {

  	if($tasa_aprendizaje < 0 || $tasa_aprendizaje > 1){
  		echo 'La tasa de aprendizaje debe ser un número real comprendido entre 0 y 1' . PHP_EOL;
  		exit;
  	}

    $this->tasa_aprendizaje = $tasa_aprendizaje;
    $this->w = array();

    //Inicialización aleatoria de los pesos y umbral
    for ($i=0; $i < 8; $i++) { 
		$this->w[$i] = rand(-5,5)/10;
	}
	$this->umbral = rand(-5,5)/10;
	$this->error = 0;
	$this->error_global = 0;

	$this->array_errores_ent = array();
	$this->array_errores_val = array();

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

		$salida_deseada = $linea[$numero_elementos - 1];
		$salida_obtenida = 0;

		$entradas = array();
		for ($i=0; $i < $numero_elementos - 1; $i++) { 
			$entradas[$i] = $linea[$i];
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

	while (($linea = fgetcsv($file, 1000, ";")) !== false) {

		$numero_elementos = count($linea);

		$salida_deseada = $linea[$numero_elementos - 1];
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

		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		$this->error_global +=  pow($this->error , 2);

	}
	fclose($file);

  }

  // Método para obtener el error producido posteriormente al aprendizaje.
  public function errorvalidacion($file_to_open, $ciclo){

  	//Hallamos el error global.
	//Abrimos el entrenamiento.
	$file = fopen($file_to_open, "r");

	if($file == false){
		echo 'No se ha podido abrir el archivo';
		exit;
	}

	while (($linea = fgetcsv($file, 1000, ";")) !== false) {

		$numero_elementos = count($linea);

		$salida_deseada = $linea[$numero_elementos - 1];
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

		
		//Calculamos el error.
		$this->error = $salida_deseada - $salida_obtenida;
		$this->error_global_validacion +=  pow($this->error , 2);
	}
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
			  <title>Resultados entrenamiento Adaline ciclo '.$ciclo.'</title>
			</head>
			<body>
				 <div style="margin:0 auto;"><h1> Resultados entrenamiento Adaline ciclo '.$ciclo.'</h1></div>
				 <table class="tabla_adaline">
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
				     <th colspan="2"><h2> Error global aprendizaje</h2></th>
				   </tr>
				   <tr>
				   	 <td>Valor sin redondear</td>
				     <td>'.$this->error_global.'</td>
				   </tr>
				   <tr>
				   	 <td>Valor redondeado 10 decimales</td>
				     <td>'.round($this->error_global,10).'</td>
				   </tr>
				 </table>
				 <table style="width:80%;margin:0 auto;">
				   <tr>
				     <th colspan="2"><h2> Error global validacion</h2></th>
				   </tr>
				   <tr>
				   	 <td>Valor sin redondear</td>
				     <td>'.$this->error_global_validacion.'</td>
				   </tr>
				   <tr>
				   	 <td>Valor redondeado 10 decimales</td>
				     <td>'.round($this->error_global_validacion,10).'</td>
				   </tr>
				 </table>
				 <br>
				 <table style="width:80%;margin:0 auto;">
				   <tr>
				     <th><h2> Umbral </h2></th>
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


  // Método para mostrar los errores globales de entrenamiento y validación en una tabla,
  public function mostrarerrores($folder, $num_ciclos){

  	//Creamos una carpeta para estos parámetros si no existe.
  	if(!file_exists (dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos)){
  		mkdir(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos, 0755);
  	}

  	$resultado = fopen(dirname(__FILE__).'/'.$folder.'/'.$this->tasa_aprendizaje.' - '.$num_ciclos.'/errores.html', "w");

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
			  <title>Errores producidos en proceso entrenamiento Adaline</title>


				<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
			    <script type="text/javascript">
			      google.charts.load("current", {"packages":["corechart"]});
			      google.charts.setOnLoadCallback(drawChart);

			      function drawChart() {
			        var data = google.visualization.arrayToDataTable([
			        ["Ciclo", "Error global"],';


	for ($i=0; $i < $num_ciclos; $i++) { 
		if($i == $num_ciclos -1){
			$html .='[ '.$i.',      '.$this->array_errores_ent[$i].']';
		}else{
			$html .='[ '.$i.',      '.$this->array_errores_ent[$i].'],';
		}
		
	}

	$maximo = 0;
	foreach ($this->array_errores_ent as $error_ent) {
		if($error_ent > $maximo){
			$maximo = $error_ent;
		}
	}


	$html .='
			        ]);

			        var options = {
			          title: "Errores en aprendizaje por ciclo",
			          hAxis: {title: "Ciclo", minValue: 0, maxValue: '.$num_ciclos.' },
			          vAxis: {title: "Error global", minValue: 0, maxValue: '.$maximo.' },
			          legend: "none"
			        };

			        var chart = new google.visualization.ScatterChart(document.getElementById("chart_div"));

			        chart.draw(data, options);
			      }
			    </script>';


	$html .='	<script type="text/javascript">
			      google.charts.load("current", {"packages":["corechart"]});
			      google.charts.setOnLoadCallback(drawChart2);

			      function drawChart2() {
			        var data = google.visualization.arrayToDataTable([
			        ["Ciclo", "Error global validación"],';


					for ($i=0; $i < $num_ciclos; $i++) { 
						if($i == $num_ciclos -1){
							$html .='[ '.$i.',      '.$this->array_errores_val[$i].']';
						}else{
							$html .='[ '.$i.',      '.$this->array_errores_val[$i].'],';
						}
						
					}

					$maximo = 0;
					foreach ($this->array_errores_val as $error_val) {
						if($error_val > $maximo){
							$maximo = $error_val;
						}
					}


	$html .='
			        ]);

			        var options = {
			          title: "Errores en validación por ciclo",
			          hAxis: {title: "Ciclo", minValue: 0, maxValue: '.$num_ciclos.' },
			          vAxis: {title: "Error global validación", minValue: 0, maxValue: '.$maximo.' },
			          legend: "none",
			          colors: ["#ff0000"]

			        };

			        var chart = new google.visualization.ScatterChart(document.getElementById("chart_div2"));

			        chart.draw(data, options);
			      }
			    </script>

			</head>
			<body>
				 <div style="margin:0 auto;"><h1>Errores producidos en proceso entrenamiento Adaline ('.$this->tasa_aprendizaje.','.$num_ciclos.')</h1></div>

				 <div id="chart_div" style="width: 900px; height: 500px;"></div>

				 <div id="chart_div2" style="width: 900px; height: 500px;"></div>

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

  // Método para mostrar los resultados del aprendizaje
  public function entrenamiento($num_ciclos){
  	for ($i=1; $i <= $num_ciclos; $i++) { 
		//Aprendizaje de la red
		$this->aprendizaje('data/entrenamiento.csv', $i);
		$this->error('data/entrenamiento.csv', $i);
		//Validación
		$this->errorvalidacion('data/validacion.csv', $i);

		$this->resultados('results', $i, $num_ciclos);

		array_push($this->array_errores_ent, $this->error_global);
		array_push($this->array_errores_val, $this->error_global_validacion);
	}

	$this->mostrarerrores('results', $num_ciclos);
  }

}