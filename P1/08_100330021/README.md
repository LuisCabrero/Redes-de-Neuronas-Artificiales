# Práctica 1 PROBLEMA DE REGRESIÓN: Resistencia a la compresión del hormigón


## ADALINE

Requisitos: tener instalado PHP preferiblemente v7.x.x o v5.6.x, para comprobarlo ejecutar php -v

:white_check_mark: Modo de ejecución: php entrenamiento.php < tasa_aprendizaje > < num_ciclos >

Entradas:

* tasa_aprendizaje: La tasa de aprendizaje de la red
* num_ciclos: Número de ciclos para ejecutar el proceso de entrenamiento.

* Ficheros utilizados:
	* :memo: entrenamiento.csv en la carpeta data/, que contiene los datos de entrenamiento
	* :memo: validacion.csv en la carpeta data/, que contiene los datos de validación

Salidas:
* Carpeta results: Se crean carpetas con este formato < tasa_aprendizaje > - < num_ciclos >, que contienen los resultados asociados.

## PERCEPTRÓN MULTICAPA (MLP)

Entradas:

* Ficheros utilizados:
	* :memo: entrenamiento.csv en la carpeta data/, que contiene los datos de entrenamiento
	* :memo: validacion.csv en la carpeta data/, que contiene los datos de validación
	* :memo: test.csv en la carpeta data/, que contiene los datos de validación

Salidas:

* Generadas en carpeta results_mlp
