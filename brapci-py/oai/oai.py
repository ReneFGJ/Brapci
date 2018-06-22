# File name: brapci.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

import sys, string, re,  urllib.request, xml.sax, datetime, MySQLdb


import xml.etree.ElementTree as ET

############################################## SOURCE ####################
def le(id):
    global config
    db = MySQLdb.connect(**config)    
    #cnx = mysql.connector.connect(**config)   
    
    cur = db.cursor()
    # Use all the SQL you like
    cur.execute("SELECT * FROM source_identify where id_i = "+ str(id))
    
    # print all the first cell of all the rows
    db.close()
    data = ()
    for row in cur:
        data = row
        #print(data)
    return data

# read_xml ##############################################################
def read_xml(content):
    tree = ET.parse(content)
    #tree = ET.fromstring(content)
    root = tree.getroot()
        
    return root

# oai_identify #########################################################
def identify(id=''):
    data = le(id)
    url = data[2]
    identify_load(url,id)
    update(id)
    return "<response>ok</response>"    
    
def identify_load(url,id):    
    urli = url + '?verb=Identify'
    file = 'cache/oai_identify_'+strzero(id,7)+'.xml'
    rs = "<url>"+urli+"</uri>"
    url4file(urli,file)
    read_xml(file)    
    return rs

def now():
    now = datetime.datetime.now()
    nd = str(now.year)+"-"+strzero(now.month,2)+"-"+strzero(now.day,2)+"T"
    nt = strzero(now.hour,2)+"-"+strzero(now.minute,2)+"-"+strzero(now.second,2)+"Z"
    n = nd + nt
    return n 

def strzero(n,s=5):
    n = str(n);
    while (len(n) < s):
        n = "0"+n
    return n

# listrecords #########################################################
def listrecords(idx):
    global id
    id = idx
    
    data = le(id)
    url = data[2]
    token = data[11]
    rs = ''
    
    # READ URL ##########
    token = listrecords_load(url,token)
    while len(token) > 0:
        token = listrecords_load(url,token)   
        rs = rs + '<token>'+token+'</token>'
        
    # FINISH ############
    update(id,'')    
    rs = '<token>resposta</token>'
    return rs

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
                try:
                    attrib['status']
                    status = 1
                except:
                    status = 0
                
                update_identifier(id,identifier,datestamp,setSpec,status)
            except:
                #print("Erro: registro")
                #break;
                n = 0
            
    if (token == 'None'):
        token = ''
    update(id,token)
    return token

def listrecords_load(url,token):    
    
    if (len(token) > 0):
        urli = url + '?verb=ListIdentifiers'
        urli = urli + '&resumptionToken='+token
    else:
        urli = url + '?verb=ListIdentifiers&metadataPrefix=oai_dc'
        
    file = 'cache/oai_listrecords.xml'
    #print("<read>"+urli+"</read>")
    url4file(urli,file)
    
    #print("Lendo XML... "+file)
    rst = read_xml(file)
    token = listrecoreds_xml(rst)
    
    return token

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

config = {
  'user': 'root',
  'password': 'root',
  'host': 'localhost',
  'database': 'brapci',
  'db': 'brapci',
}

def update(id,token=''):
    global config
    
    db = MySQLdb.connect(**config)    
    data = '2018-05-20 23:00:00'

    sql = "update source_identify set lastUpdate = '"+ data +"', "
    sql = sql + "token = '"+ str(token)+"' " 
    sql = sql + "where id_i = " + str(id)

    cur = db.cursor()
    cur.execute(sql) 
    db.commit()   
    db.close()
    
def update_identifier(id='',identifier='',datestamp='',setSpec='',delete=0):
    global config    
    rs = ""

    db = MySQLdb.connect(**config)
    sql = "select * from source_listidentifier where li_identifier = '"+identifier+"' and li_jnl = " + str(id)
    cur = db.cursor()
    cur.execute(sql)
    rst = cur.rowcount
    
    if rst == 0:
        sql = "insert into source_listidentifier " 
        sql = sql + " (li_identifier, li_datestamp, li_setSpec, li_status, li_jnl) "
        sql = sql + " values "
        sql = sql + " ('"+identifier+"',"
        sql = sql + " '"+datestamp+"',"
        sql = sql + " '"+setSpec+"',";
        sql = sql + " '1',"
        sql = sql + " '"+str(id)+"')"
        cur.execute(sql)
        rs = rs + '<record status="new">'+identifier+'</record>'
    else:
        sql = "update source_listidentifier set " 
        sql = sql + " li_setSpec = '"+setSpec+"', li_deleted = '"+str(delete)+"' "
        sql = sql + " where li_identifier = '"+identifier+"' and li_jnl = " + str(id)
        cur.execute(sql)
        rs = rs + '<record status="update">'+identifier+'</record>'        
    db.close()
    
    return rs

##############################################################
nw = now()
proc = '<responseDate>'+nw+'</responseDate>';
version = '1.0a'
about = '(c)2018 - Rene F. Gabriel Junior'
cmd = ''
id = ''

if len(sys.argv) >= 3:
    cmd = sys.argv[1]
    id = str(sys.argv[2])

if cmd == '':
    proc = '<result>null</result>'

if cmd == 'identify':
    idx = int(id)
    id = str(sys.argv[2])
    proc = identify(idx)   

if cmd == 'listrecords':
    idx = int(id)
    id = str(sys.argv[2])
    proc = listrecords(idx)

print('<?xml version="1.0" encoding="UTF-8"?>')
print('<OAI-PMH>')
print(proc)
print('<command journal="'+id+'">'+cmd+'</command>')
print('<version>'+version+'</version>')
print('<about>'+about+'</about>')
print('</OAI-PMH>')
