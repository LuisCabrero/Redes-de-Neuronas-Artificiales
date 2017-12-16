echo "Experimento SOM"
echo "Introduce dimensión x del mapa"
read xdim
echo "Introduce dimensión y del mapa"
read ydim
mkdir $xdim-$ydim
mkdir $xdim-$ydim/p1
mkdir $xdim-$ydim/p2
mkdir $xdim-$ydim/p3


echo "------------------------- PAREJA 1 ---------------------------"
echo "Inicializando mapa..."
./randinit -din data/Train1.csv -cout $xdim-$ydim/p1/Train1.cod -xdim $xdim -ydim $ydim -topol hexa -neigh bubble
echo "Comenzando entrenamiento..."
./vsom -din data/Train1.csv -cin $xdim-$ydim/p1/Train1.cod -cout $xdim-$ydim/p1/Trained1.cod -rlen 1000 -alpha 0.05 -radius 10
echo "Puliendo entrenamiento..."
./vsom -din data/Train1.csv -cin $xdim-$ydim/p1/Train1.cod -cout $xdim-$ydim/p1/Trained1_2.cod -rlen 10000 -alpha 0.02 -radius 10
echo "Evaluando los resultados..."
./qerror -din data/Train1.csv -cin $xdim-$ydim/p1/Trained1_2.cod 
echo "Calibrado del mapa"
./vcal -din data/Train1.csv -cin $xdim-$ydim/p1/Trained1_2.cod -cout $xdim-$ydim/p1/Trained1_2Cal.cod
echo "Monitorización"
./visual -din data/Test1.csv -cin $xdim-$ydim/p1/Trained1_2Cal.cod -dout $xdim-$ydim/p1/Train1Visual.txt
./sammon -cin $xdim-$ydim/p1/Trained1_2.cod -cout $xdim-$ydim/p1/train1.sam -rlen 100 -ps
./umat -cin $xdim-$ydim/p1/Trained1_2Cal.cod > $xdim-$ydim/p1/train1.ps

echo "------------------------- PAREJA 2 ---------------------------"
echo "Inicializando mapa..."
./randinit -din data/Train2.csv -cout $xdim-$ydim/p2/Train2.cod -xdim $xdim -ydim $ydim -topol hexa -neigh bubble
echo "Comenzando entrenamiento..."
./vsom -din data/Train2.csv -cin $xdim-$ydim/p2/Train2.cod -cout $xdim-$ydim/p2/Trained2.cod -rlen 1000 -alpha 0.05 -radius 10
echo "Puliendo entrenamiento..."
./vsom -din data/Train2.csv -cin $xdim-$ydim/p2/Train2.cod -cout $xdim-$ydim/p2/Trained2_2.cod -rlen 10000 -alpha 0.02 -radius 10
echo "Evaluando los resultados..."
./qerror -din data/Train2.csv -cin $xdim-$ydim/p2/Trained2_2.cod 
echo "Calibrado del mapa"
./vcal -din data/Train2.csv -cin $xdim-$ydim/p2/Trained2_2.cod -cout $xdim-$ydim/p2/Trained2_2Cal.cod
echo "Monitorización"
./visual -din data/Test2.csv -cin $xdim-$ydim/p2/Trained2_2Cal.cod -dout $xdim-$ydim/p2/Train2Visual.txt
./sammon -cin $xdim-$ydim/p2/Trained2_2.cod -cout $xdim-$ydim/p2/train2.sam -rlen 100 -ps
./umat -cin $xdim-$ydim/p2/Trained2_2Cal.cod > $xdim-$ydim/p2/train2.ps

echo "------------------------- PAREJA 3 ---------------------------"
echo "Inicializando mapa..."
./randinit -din data/Train3.csv -cout $xdim-$ydim/p3/Train3.cod -xdim $xdim -ydim $ydim -topol hexa -neigh bubble
echo "Comenzando entrenamiento..."
./vsom -din data/Train3.csv -cin $xdim-$ydim/p3/Train3.cod -cout $xdim-$ydim/p3/Trained3.cod -rlen 1000 -alpha 0.05 -radius 10
echo "Puliendo entrenamiento..."
./vsom -din data/Train3.csv -cin $xdim-$ydim/p3/Train3.cod -cout $xdim-$ydim/p3/Trained3_2.cod -rlen 10000 -alpha 0.02 -radius 10
echo "Evaluando los resultados..."
./qerror -din data/Train3.csv -cin $xdim-$ydim/p3/Trained3_2.cod 
echo "Calibrado del mapa"
./vcal -din data/Train3.csv -cin $xdim-$ydim/p3/Trained3_2.cod -cout $xdim-$ydim/p3/Trained3_2Cal.cod
echo "Monitorización"
./visual -din data/Test3.csv -cin $xdim-$ydim/p3/Trained3_2Cal.cod -dout $xdim-$ydim/p3/Train3Visual.txt
./sammon -cin $xdim-$ydim/p3/Trained3_2.cod -cout $xdim-$ydim/p3/train3.sam -rlen 100 -ps
./umat -cin $xdim-$ydim/p3/Trained3_2Cal.cod > $xdim-$ydim/p3/train3.ps