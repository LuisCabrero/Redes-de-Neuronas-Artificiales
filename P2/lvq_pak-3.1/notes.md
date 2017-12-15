# Notes about the commands used
## Prototype Initialization
  * ./eveninit -din ../train1.csv -cout train1.cod -noc 196
  * ./mindist -cin train1.cod
This gives us:
```
	In class       van  49 units, min dist.:  0.371
	In class      saab  49 units, min dist.:  0.360
	In class      opel  49 units, min dist.:  0.411
	In class       bus  49 units, min dist.:  0.334
```
And then:
  * ./balance -din ../train1.csv -cin train1.cod -cout trained1.cod
```
Some codebook vectors are removed
Some new codebook vectors are picked
   0/   0 sec.                ------------------------------------------------------------
Codebook vectors are redistributed
               ------------------------------------------------------------
In class       van  49 units, min dist.: 0.358
In class      saab  49 units, min dist.: 0.368
In class      opel  49 units, min dist.: 0.415
In class       bus  49 units, min dist.: 0.340
```
	
_Why do we use 196 prototypes?_ Because the minimum number of examples for a class is 199 and I like it when every class has the same units :D.

## Trainning
We're going to use olvq1 (optimized learning-rate LVQ1) because fuck it.
  * ./olvq1 -din ../train1.csv -cin trained1.cod -cout trained2.cod -rlen 5000
_Why do we use a running length of 5000?_ Because **fuck you**, thats why.

## Evaluation
  * ./accuracy -din ../test1.csv -cin trained2.cod
```
0/   0 sec. ............................................................

Recognition accuracy:

      bus:   74 entries  91.89 %
     saab:   73 entries  49.32 %
     opel:   72 entries  44.44 %
      van:   66 entries  81.82 %

Total accuracy:   285 entries  66.67 %
```
  * ./classify -din ../test1.csv -cin trained2.cod -dout testOut1.txt
This gives us an output slightly different than the original test1.csv, _why do we do this?_ Because why not.

## Visualization
  * ./sammon -cin trained2.cod -cout trained.sam -ps -rlen 5000

