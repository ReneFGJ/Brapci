from django.db import models

# Create your models here.
class Sources(models.Model):
    
    id_jnl = models.AutoField('ID' , primary_key = True)
    jnl_name = models.CharField('Nome da fonte' , max_length=100, null=True, blank = True)
    jnl_name_abrev = models.CharField('Nome da fonte' , max_length=30, null=True, blank = True)
    jnl_issn_impresso = models.CharField('ISSN' , max_length=15, null=True, blank = True)
    jnl_issn_eletronico = models.CharField('eISSN' , max_length=15, null=True, blank = True)
    jnl_periodicidade  = models.CharField('Periodicidade' , max_length=1, null=True, blank = True)
    jnl_ano_inicio  = models.CharField('Início da publicação' , max_length=6, null=True, blank = True)
    jnl_ano_final = models.CharField('Fim da publicação' , max_length=6, null=True, blank = True)
    
    jnl_url = models.SlugField('URL', null=True, blank = True)
    jnl_url_oai = models.SlugField('URL OAI-PMH', null=True, blank = True)
    jnl_oai_from = models.CharField('Ano de coleta' , max_length=5, null=True)
    jnl_oai_token = models.CharField('Ano de coleta' , max_length=5, null=True)
    jnl_oai_last_harvesting = models.DateTimeField('Update at', auto_now = True)
    
    jnl_cidade = models.IntegerField('Cidade', null=True)
    jnl_scielo = models.IntegerField('Scielo', null=True)
    jnl_collection = models.CharField('Coleção' , max_length=5, null=True)
    create_at = models.DateTimeField('Create at', auto_now_add = True , null=True)
    update_at = models.DateTimeField('Update at', auto_now = True , null=True)
    
    jnl_active = models.IntegerField('Active', default = 1, null=True)