<?php

	//MODO DE EJECUCIÓN: php obtenerporcentajeacierto.php <ruta_al_csv1> <ruta_al_txt1>
	//MODO DE EMPLEO 2: php obtenerporcentajeacierto.php --help
	//csv1: csv con el fichero de test
	//txt1: txt con el fichero generado por visual

	$csv1 = $argv[1];

	if($csv1 == "--help"){
		echo "MODO DE EJECUCIÓN: php obtenerporcentajeacierto.php <ruta_al_csv1> <ruta_al_txt1>\n";
		echo "csv1: csv con el fichero de test\n";
		echo "txt1: txt con el fichero generado por visual de som_pak\n";
		exit;
	}

	$txt1 = $argv[2];

	

	//Leemos el csv de la ruta
	
	$file1 = fopen($csv1, "r");

	if($file1 == false){
		echo 'No se ha podido abrir el archivo csv';
		exit;
	}

	//Pasamos el txt a un array
	$contenido_txt = file($txt1);

	//Del txt nos interesa solamente la ultima posicion separada por espacios
	
	$valores_predichos = array();
	foreach ($contenido_txt as $content) {
		$elementos = explode(" ", $content);
		$valores_predichos[] = $elementos[count($elementos)-2];
	}

	$total_patrones = 0;
	$total_aciertos_bus = 0;
	$total_aciertos_saab = 0;
	$total_aciertos_opel = 0;
	$total_aciertos_van = 0;
	$numero_bus = 0;
	$numero_saab = 0;
	$numero_opel = 0;
	$numero_van = 0;
	$posicion_valores = 1;
	//Leemos línea por línea el csv
	//La primera linea es basura
	$linea = fgetcsv($file1, 1000, " ");
	while (($linea = fgetcsv($file1, 1000, " ")) !== false) {
		$total_patrones++;
		$valor_predicho = $valores_predichos[$posicion_valores];
		$valor_obtenido = $linea[count($linea)-1];
		$posicion_valores++;

		if($valor_predicho != $valor_obtenido){
			if($valor_obtenido == "bus"){
				$numero_bus++;
			}elseif($valor_obtenido == "saab"){
				$numero_saab++;
			}elseif($valor_obtenido == "van"){
				$numero_van++;
			}elseif($valor_obtenido == "opel"){
				$numero_opel++;
			}
		}else{
			if($valor_obtenido == "bus"){
				$total_aciertos_bus++;
				$numero_bus++;
			}elseif($valor_obtenido == "saab"){
				$total_aciertos_saab++;
				$numero_saab++;
			}elseif($valor_obtenido == "van"){
				$total_aciertos_van++;
				$numero_van++;
			}elseif($valor_obtenido == "opel"){
				$total_aciertos_opel++;
				$numero_opel++;
			}
		}

	}

	$porcentaje_acierto_bus = $total_aciertos_bus/$numero_bus;
	$porcentaje_acierto_saab = $total_aciertos_saab/$numero_saab;
	$porcentaje_acierto_van = $total_aciertos_van/$numero_van;
	$porcentaje_acierto_opel = $total_aciertos_opel/$numero_opel;

	$porcentaje_acierto_total = ($total_aciertos_bus+$total_aciertos_saab+$total_aciertos_van+$total_aciertos_opel)/$total_patrones;
	echo "Porcentaje de acierto total: ".$porcentaje_acierto_total."\n";
	echo "Porcentaje de acierto bus: ".$porcentaje_acierto_bus."\n";
	echo "Porcentaje de acierto saab: ".$porcentaje_acierto_saab."\n";
	echo "Porcentaje de acierto van: ".$porcentaje_acierto_van."\n";
	echo "Porcentaje de acierto opel: ".$porcentaje_acierto_opel."\n";

	exit;

?>