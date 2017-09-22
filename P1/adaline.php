<?php

/*
* CLASE:        Adaline
* DESCRIPCIÓN:  Implementa el uso de este tipo de red neuronal
* AUTOR:        Luis Cabrero García.
* FECHA:        22/09/2017
*/

class Adaline {

  var $tasa_aprendizaje;    //Controla el cambio que sufren los pesos de una iteración a otra (entre 0 y 1)
  var $w;  					//Array de pesos
  var $umbral;     			//Umbral
  var $error;   			//Error producido en un patrón.
  var $error_global;   		//Error cuadrático global.
  var $num_datos_entrada;   //Número de datos de entrada.

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
  public function aprendizaje($file_to_open){

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

  // Método de aprendizaje de la red.
  public function error($file_to_open){

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

  // Método para mostrar los resultados del aprendizaje
  public function resultados($file_to_show){

  	$resultado = fopen($file_to_show, "w");
	

  	$tabla_resultados =
	 '<table style="width:80%;margin:0 auto;">
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
		    <td>'.$w[0].'</td>
		    <td>'.$w[1].'</td>
		    <td>'.$w[2].'</td>
		    <td>'.$w[3].'</td>
		    <td>'.$w[4].'</td>
		    <td>'.$w[5].'</td>
		    <td>'.$w[6].'</td>
		    <td>'.$w[7].'</td>
		  </tr>
	 </table>
	 <br>';

	$tabla_resultados .= '
		<table style="width:80%;margin:0 auto;">
		  <tr>
		    <th><h1> Error </h1></th>
		  </tr>
		  <tr>
		    <td>'.$error_global.'</td>
		  </tr>
		</table>
		<br>';

	$tabla_resultados .= '
		<table style="width:80%;margin:0 auto;">
		  <tr>
		    <th><h1> Umbral </h1></th>
		  </tr>
		  <tr>
		    <td>'.$umbral.'</td>
		  </tr>
		</table>
		<br>';	
	
	fwrite($resultado, $tabla_resultados);

	fclose($resultado);

  }

}