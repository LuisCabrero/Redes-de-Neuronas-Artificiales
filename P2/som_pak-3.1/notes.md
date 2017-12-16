# Notes about the commands used
## Map Initialization
  * randinit -din Train1.csv -cout Train1.cod -xdim 10 -ydim 10 -topol hexa -neigh bubble
  Where -cout indicates the map file, -xdim and -ydim the map size , -topol the topology and -neigh the neighbours.

## Trainning
  We will use SOM algorithm, wich is going to be executed twice:
  * vsom -din Train1.csv -cin Train1.cod -cout Trained1.cod
-rlen 1000 -alpha 0.05 -radius 10
  Where -din and -cin are the files needed, -cout the file generated and -rlen the number of steps in training. -alpha is the initial learning rate and -radius is the initial radius of the training area.
  * vsom -din Train1.csv -cin Train1.cod -cout Trained1_2.cod
-rlen 10000 -alpha 0.02 -radius 3
  Here we just adjust the parameters.

## Evaluation
  * qerror -din Train1.csv -cin Trained1_2.cod 
  Measure the medium error for each of the training examples (distance between each example and his nearest reference vector)

## Calibrate the map
  * vcal -din Train1.csv -cin Trained1_2.cod -cout Trained1_2Cal.cod
  Adds the class for each pattern.

## Monitoring
  Three possibilities:
  * visual -din Test1.csv -cin Trained1_2.cod -dout Train1Visual.txt
  Returns a list with the nearest cells to the data vector and the distance between them. Also can be executed using the calibrated file.
  * sammon -cin Trained1_2.cod -cout diabetes.sam –rlen 100 -ps
  Generates a 2D image with the prototypes and a postcript with the image.
  * umat -cin Trained1_2Cal.cod > diab.ps
  Generates a umatrix where you can see distance represented.
  
