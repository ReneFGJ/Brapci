# File name: oai_py.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

#import sys, string, re,  urllib.request, xml.sax
import sys, urllib.request

# get url ###############################################################
def geturl(url):
    print('\n\rReading ...'+url)
    
    with urllib.request.urlopen('http://python.org/') as response:
        html = response.read()
    #response = urllib.request.urlopen(url)
    #page = str(response.read())
    return html

def url4file(url, file):
    testfile = urllib.request.URLopener()
    testfile.retrieve(url, file)

# savefile ############################################################
def savefile(name,content):
    file = open(name, 'w')
    file.write(content)
    file.close()
    return true
