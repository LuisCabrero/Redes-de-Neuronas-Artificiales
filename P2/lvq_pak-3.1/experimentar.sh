echo "Experimentación lvq"
mkdir 196
./eveninit -din data/Train3.csv -cout 196/Train3.cod -noc 196
./mindist -cin 196/Train3.cod
./balance -din data/Train3.csv -cin 196/Train3.cod -cout 196/Trained3.cod
./olvq1 -din data/Train3.csv -cin 196/Trained3.cod -cout 196/Trained3_2.cod -rlen 5000
./accuracy -din data/Test3.csv -cin 196/Trained3_2.cod
./classify -din data/Test3.csv -cin 196/Trained3_2.cod -dout 196/testOut3.txt
./sammon -cin 196/Trained3_2.cod -cout 196/Trained.sam -ps -rlen 5000