echo "Experimentación lvq"
mkdir 10
./eveninit -din data/Train1.csv -cout 10/Train1.cod -noc 10
./mindist -cin 10/Train1.cod
./balance -din data/Train1.csv -cin 10/Train1.cod -cout 10/Trained1.cod
./olvq1 -din data/Train1.csv -cin 10/Trained1.cod -cout 10/Trained1_2.cod -rlen 5000
./accuracy -din data/Test1.csv -cin 10/Trained1_2.cod
./classify -din data/Test1.csv -cin 10/Trained1_2.cod -dout 10/testOut1.txt
./sammon -cin 10/Trained1_2.cod -cout 10/Trained.sam -ps -rlen 5000
