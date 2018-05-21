# File name: brapci.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

import sys, string, re,  urllib.request, xml.sax
import oai_io, oai_xml, oai_identify, oai_listrecords

print('#####################')
print('#   O A I - P M H   #')
print('#              v0.1 #')
print('#####################')
print('by Rene Junior - 2018')

url = 'http://seer.ufrgs.br/index.php/EmQuestao/oai'

jnl = 1

# cnt = oai_identify(url)
#oai_identify.identify(1)
oai_listrecords.listrecords(1)

