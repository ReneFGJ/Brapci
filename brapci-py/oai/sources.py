# File name: sources.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

import MySQLdb, datetime

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
    
def update_identifier(id='',identifier='',datestamp='',setSpec=''):
    global config    
    status = '1'
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
        sql = sql + " '"+status+"',"
        sql = sql + " '"+str(id)+"')"
        cur.execute(sql)
        print(identifier,"*novo*")
    else:
        #print(identifier,"*ja identificado*")
        None
    db.close()
    
    return True

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
