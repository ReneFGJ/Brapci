# File name: oai_py.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

import oai_io, oai_xml, sources

# oai_identify #########################################################
def identify(id=''):
    data = sources.le(1)
    url = data[2]
    
    identify_load(url)
    sources.update(id)    
    
def identify_load(url):    
    urli = url + '?verb=Identify'
    file = 'oai.xml'
    print("\r\nCarregando... "+urli)
    oai_io.url4file(urli,file)
    
    print("Lendo XML... "+file)
    oai_xml.read_xml(file)
    
    return 1