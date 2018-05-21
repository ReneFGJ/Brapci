# File name: oai_py.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

import oai_io, oai_xml, sources

id = 0

# listrecords #########################################################
def listrecords(idx):
    global id
    id = idx
    
    data = sources.le(id)
    url = data[2]
    token = data[11]
    
    # READ URL ##########
    print("\r\n")
    token = listrecords_load(url,token)
    while len(token) > 0:
        token = listrecords_load(url,token)   
    
    # FINISH ############
    sources.update(id,'')
    
    return 1

# listrecords XML #####################################################
def listrecoreds_xml(root):
    global id

    list = root.find('{http://www.openarchives.org/OAI/2.0/}ListIdentifiers')
    token = ''

    for fld in list:
        if (fld.tag == '{http://www.openarchives.org/OAI/2.0/}resumptionToken'):
            token = str(fld.text)
    
    for child in root:
        #print('=1=>', child.tag, child.attrib, child.text)
        identifier = ''
        datestamp = ''
        setSpec = ''
        status = ''
        attrib = ''

        for field in child:
            attrib = field.attrib
            try:            
                identifier = field.find('{http://www.openarchives.org/OAI/2.0/}identifier').text
                datestamp = field.find('{http://www.openarchives.org/OAI/2.0/}datestamp').text
                setSpec = field.find('{http://www.openarchives.org/OAI/2.0/}setSpec').text
                sources.update_identifier(id,identifier,datestamp,setSpec)
            except:
                print("Erro: registro")
                break;
            
    if (token == 'None'):
        token = ''
    sources.update(id,token)
    return token

def listrecords_load(url,token):    
    
    if (len(token) > 0):
        urli = url + '?verb=ListIdentifiers'
        urli = urli + '&resumptionToken='+token
    else:
        urli = url + '?verb=ListIdentifiers&metadataPrefix=oai_dc'
        
    file = 'oai_listrecords.xml'
    print("Carregando... "+urli)
    oai_io.url4file(urli,file)
    
    #print("Lendo XML... "+file)
    rst = oai_xml.read_xml(file)
    token = listrecoreds_xml(rst)
    
    return token