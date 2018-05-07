from django.db import models
from django.utils import timezone

# Create your models here.
class ListIdentifier(models.Model):
    id_li = models.AutoField('ID' , primary_key = True)
    li_identifier = models.CharField('identifier' , max_length=100, null=True)
    li_datestamp = models.DateTimeField('Update at')
    li_setSpec = models.CharField('setSpec' , max_length=30, null=True)
    li_status = models.CharField('status' , max_length=10, null=True)
    li_jnl = models.IntegerField('jnl',default=0)

class Identify(models.Model):
    id_i = models.AutoField('ID' , primary_key = True)
    repositoryName = models.CharField('repositoryName' , max_length=100, null=True)
    baseURL = models.CharField('baseURL' , max_length=100, null=True)
    protocolVersion = models.CharField('protocolVersion' , max_length=5, null=True)
    adminEmail = models.CharField('adminEmail' , max_length=100, null=True)
    deletedRecord = models.CharField('deletedRecord' , max_length=20, null=True)
    granularity = models.CharField('granularity' , max_length=20, null=True)
    compression = models.CharField('compression' , max_length=20, null=True)
    earliestDatestamp = models.DateTimeField('Update at', auto_now = True)
    jnl = models.IntegerField('jnl',default=0)
   

class Source(models.Model):    
    id_jnl = models.AutoField('ID' , primary_key = True)
    jnl_name = models.CharField('Nome da fonte' , max_length=100, null=True, blank = True)
    jnl_name_abrev = models.CharField('Nome da fonte' , max_length=30, null=True, blank = True)
    jnl_issn_impresso = models.CharField('ISSN' , max_length=15, null=True, blank = True)
    jnl_issn_eletronico = models.CharField('eISSN' , max_length=15, null=True, blank = True)
    jnl_periodicidade  = models.CharField('Periodicidade' , max_length=1, null=True, blank = True)
    jnl_ano_inicio  = models.CharField('Início da publicação' , max_length=6, null=True, blank = True)
    jnl_ano_final = models.CharField('Fim da publicação' , max_length=6, null=True, blank = True)
    
    jnl_url = models.CharField('URL', null=True, blank = True, max_length=200)
    jnl_url_oai = models.CharField('URL OAI-PMH', null=True, blank = True, max_length=200)
    jnl_oai_from = models.CharField('Ano de coleta' , max_length=5, null=True)
    jnl_oai_token = models.CharField('Ano de coleta' , max_length=5, null=True)
    jnl_oai_last_harvesting = models.DateTimeField('Update at', auto_now = True)
    
    jnl_cidade = models.IntegerField('Cidade', null=False, default=0)
    jnl_scielo = models.IntegerField('Scielo', null=False, default=0)
    jnl_collection = models.CharField('Coleção' , max_length=5, null=True)
    create_at = models.DateTimeField('Create at', auto_now_add = True , null=True)
    update_at = models.DateTimeField('Update at', auto_now = True , null=True)
    
    jnl_active = models.IntegerField('Active', default = 1, null=True)
    
    def __str__(self):
        return self.jnl_name  
    
class ListRecords(models.Model):
     
    id_lr = models.AutoField('ID' , primary_key = True) 
    lr_identifier = models.CharField('Identifier' , max_length=100, blank = True)
    lr_datestamp = models.DateTimeField('datestamp')
    lr_setSpec = models.DateTimeField('setSpec' , max_length=20, blank = True)
    lr_status = models.CharField('status' , max_length=20, blank = True)
    lr_jnl = models.IntegerField('Journal' , default = 0)
    
    def __str__(self):
        return self.lr_identifier
    
    
    
    