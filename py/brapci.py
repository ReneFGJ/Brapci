# File name: brapci.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

import sys, string, re,  urllib.request, xml.sax

print('#####################')
print('#   O A I - P M H   #')
print('#              v0.1 #')
print('#####################')
print('by Rene Junior - 2018')

url = 'http://seer.ufrgs.br/index.php/EmQuestao/oai'

import xml.etree.ElementTree as ET


# get url ###############################################################
def geturl(url):
    print('reading ...'+url)
    response = urllib.request.urlopen(url)
    page = str(response.read())
    return page

# read_xml ##############################################################
def read_xml(content):
    tree = ET.parse('svg.xml')
    root = tree.getroot()
    # root = ET.fromstring(country_data_as_string) #via string
    for child in root:
        print('=1=>', child.tag, child.attrib, child.text)
        for childs in child:
            print('=2=>', childs.tag, childs.attrib, childs.text)

    print("# TAG #####")
    for country in root.findall('{http://www.openarchives.org/OAI/2.0/}Identify'):
        rank = country.find('{http://www.openarchives.org/OAI/2.0/}repositoryName').text
        print("== 2 ==>", rank)

# oai_identify #########################################################
def oai_identify(url=''):
    urli = url + '?verb=Identify'
    content = geturl(urli)
    read_xml(content)
    return 1

# savefile ############################################################
def savefile(name,content):
    file = open(name, 'w')
    file.write(content)
    file.close()
    return true


print("#########################################")

# cnt = oai_identify(url)
read_xml('teste')