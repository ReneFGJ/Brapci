# File name: oai_xml.py
# Author: Rene F. Gabriel Junior <renefgj@gmail.com>
# Date created: 2018-04-30
# Date last modified: 2018-04-30
# Python Version: 3.5

import xml.etree.ElementTree as ET

# read_xml ##############################################################
def read_xml(content):
    tree = ET.parse(content)
    #tree = ET.fromstring(content)
    root = tree.getroot()
        
    return root