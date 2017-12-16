echo "Experimentación lvq"
mkdir 186
./eveninit -din data/Train1.csv -cout 186/Train1.cod -noc 186
./mindist -cin 186/Train1.cod
./balance -din data/Train1.csv -cin 186/Train1.cod -cout 186/Trained1.cod
./olvq1 -din data/Train1.csv -cin 186/Trained1.cod -cout 186/Trained1_2.cod -rlen 5000
./accuracy -din data/Test1.csv -cin 186/Trained1_2.cod
./classify -din data/Test1.csv -cin 186/Trained1_2.cod -dout 186/testOut1.txt
./sammon -cin 186/Trained1_2.cod -cout 186/Trained.sam -ps -rlen 5000