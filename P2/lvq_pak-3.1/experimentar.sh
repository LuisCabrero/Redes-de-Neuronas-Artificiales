echo "Experimentación lvq"
./eveninit -din data/Train1.csv -cout 196/Train1.cod -noc 196
./mindist -cin 196/Train1.cod
./balance -din data/Train1.csv -cin 196/Train1.cod -cout 196/Trained1.cod
./olvq1 -din data/Train1.csv -cin 196/Trained1.cod -cout 196/Trained1_2.cod -rlen 5000
./accuracy -din data/Test1.csv -cin 196/Trained1_2.cod
./classify -din data/Test1.csv -cin 196/Trained1_2.cod -dout 196/testOut1.txt
./sammon -cin 196/Trained1_2.cod -cout 196/Trained.sam -ps -rlen 5000
