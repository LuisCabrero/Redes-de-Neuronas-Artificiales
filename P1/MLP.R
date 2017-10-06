#install.packages("RSNNS")
library(RSNNS)

#fijar semilla para poder reproducir experimentos
set.seed(1)

#Leer ficheros de los tres ficheros (cuidado con el formato)

trainSet <- read.csv("data/entrenamiento.csv",dec=",",sep=";",header = F)
validSet <- read.csv( "data/validacion.csv",dec=",",sep=";",header = F)
testSet  <- read.csv("data/test.csv",dec=",",sep=";",header = F)


#num de columna de salida
target <- ncol(trainSet)

# Hay que ir configurando los parámetros de la red e ir probando
#SELECCION DE LOS PARAMETROS DE LA RED
topologia        <- c(50)   #una capa oculta de 50 neuronas. M?s capas ej: c(20,10) Para añadir más capas se introducen más parámetros
razonAprendizaje <- 0.2
ciclosMaximos    <- 1000

#EJECUCION DEL APRENDIZAJE Y GENERACION DEL MODELO
# en Rsnns se llama test a nuestro fichero de validaci?n
model <- mlp(x= trainSet[,-target],
             y= trainSet[, target],
             inputsTest=  validSet[,-target],  #Conjunto de validación
             targetsTest= validSet[, target],
             size= topologia, #Tamaño de la red
             maxit=ciclosMaximos,  #Ciclos máximos
             learnFuncParams=c(razonAprendizaje),  #Parámetros de aprendizaje, solo pasamos la tasa.
             shufflePatterns = F  #Al ya estar aleatorizado se deja como está, si no lo estuviese se cambia.
             )

#GRAFICO DE LA EVOLUCION DEL ERROR 
plotIterativeError(model)

#FUNCION que calcula el error cuadr?tico medio MSE
MSE <- function(pred,obs) sum((pred - obs)^2) / length(obs)

#VECTOR DE LOS ERRORES
errors <- data.frame(TrainRMSE= MSE(pred= predict(model,trainSet[,-target]),obs= trainSet[,target]),
            ValidRMSE= MSE(pred= predict(model,validSet[,-target]),obs= validSet[,target]),
            TestRMSE=  MSE(pred= predict(model, testSet[,-target]),obs=  testSet[,target]))

#TABLA CON LOS ERRORES POR CICLO
iterativeErrors <- data.frame(MSETrain= (model$IterativeFitError/ nrow(trainSet)),
                              MSEValid= (model$IterativeTestError/nrow(validSet)))

#SALIDAS DE LA RED
outputs <- list(train=   c(predict(model,trainSet[,-target])),
                      valid= c(predict(model,validSet[,-target])),
                      test=  c(predict(model, testSet[,-target])))
                

#GUARDAR RESULTADOS
#Hay que ir renombrando los ficheros para ir realizando pruebas y que no se pisen.
#Crear distintas carpetas para ir guardando los resultados en función de cada prueba.
saveRDS(model,"results_mlp/nnet.rds")   ## para leeer usar readRDS(model, ")
write.csv(errors,"results_mlp/finalErrors.csv",row.names=F)
write.csv(iterativeErrors,"results_mlp/iterativeErrors.csv")
write.csv(outputs$train,"results_mlp/trainOutputs.csv")
write.csv(outputs$valid,"results_mlp/validOutputs.csv")
write.csv(outputs$test,"results_mlp/testOutputs.csv")

#plot de la salida de test
#habría que desnormalizarlo para obtener la gráfica correctamente.
x=1:nrow(testSet)
plot(x,outputs$test,type="b",col="red",main="pred (red) vs obs (blue)")
lines(x,testSet[,target],type="b",col="blue")


