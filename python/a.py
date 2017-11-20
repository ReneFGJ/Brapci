# FUNCTION: search in python
# @author: Rene Faustino Gabriel Junior
# @version: v0.17.11.19
import sys

file = 'd:/projeto/brapci/search.txt'

#################################### 2
lookup = sys.argv[3:]
offset = int(sys.argv[1])
limit = int(sys.argv[2])
if (offset <= 0):
     offset = 1
if (limit <= 1):
     limit = 25
print('[offset]',offset)
print('[limit]',limit)

for arg in lookup:
    print('[arg]:',arg);

####################### RESULTADO
n=0
with open(file) as myFile:
    for num, line in enumerate(myFile, 1):
        ok = 0
        t = 0
        for lk in lookup:
            if lk in line:
                ok+=1
            t+=1
				
        if ok == t:
            n+=1
            if n > offset:
                if n <= (offset + limit):
                    print('[work]',line[3:13])
print('[total]',n)