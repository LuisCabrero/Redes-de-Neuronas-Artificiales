echo "Experimentación lvq"
mkdir 180
./eveninit -din data/Train1.csv -cout 180/Train1.cod -noc 180
./mindist -cin 180/Train1.cod
./balance -din data/Train1.csv -cin 180/Train1.cod -cout 180/Trained1.cod
./olvq1 -din data/Train1.csv -cin 180/Trained1.cod -cout 180/Trained1_2.cod -rlen 5000
./accuracy -din data/Test1.csv -cin 180/Trained1_2.cod
./classify -din data/Test1.csv -cin 180/Trained1_2.cod -dout 180/testOut1.txt
./sammon -cin 180/Trained1_2.cod -cout 180/Trained.sam -ps -rlen 5000
