library('rjson')
 
x1 <- commandArgs()[5]
x2 <- commandArgs()[6]
 
xx1 <- fromJSON(x1)
xx2 <- fromJSON(x2)
 
cat(toJSON(c(xx1,xx2)))