# Generated by Django 2.0.4 on 2018-05-01 16:27

from django.db import migrations, models


class Migration(migrations.Migration):

    initial = True

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='Sources',
            fields=[
                ('id_jnl', models.IntegerField(primary_key=True, serialize=False, verbose_name='ID')),
                ('jnl_name', models.CharField(max_length=100, verbose_name='Nome da fonte')),
                ('jnl_name_abrev', models.CharField(max_length=30, verbose_name='Nome da fonte')),
                ('jnl_issn_impresso', models.CharField(max_length=15, verbose_name='ISSN')),
                ('jnl_issn_eletronico', models.CharField(max_length=15, verbose_name='eISSN')),
                ('jnl_periodicidade', models.CharField(max_length=1, verbose_name='Periodicidade')),
                ('jnl_ano_inicio', models.CharField(max_length=6, verbose_name='Início da publicação')),
                ('jnl_ano_final', models.CharField(max_length=6, verbose_name='Fim da publicação')),
                ('jnl_url', models.SlugField(verbose_name='URL')),
                ('jnl_url_oai', models.SlugField(verbose_name='URL OAI-PMH')),
                ('jnl_oai_from', models.CharField(max_length=5, verbose_name='Ano de coleta')),
                ('jnl_oai_token', models.CharField(max_length=5, verbose_name='Ano de coleta')),
                ('jnl_oai_last_harvesting', models.DateTimeField(auto_now=True, verbose_name='Update at')),
                ('jnl_cidade', models.IntegerField(verbose_name='Cidade')),
                ('jnl_scielo', models.IntegerField(verbose_name='Scielo')),
                ('jnl_collection', models.CharField(max_length=5, verbose_name='Coleção')),
                ('create_at', models.DateTimeField(auto_now_add=True, verbose_name='Create at')),
                ('update_at', models.DateTimeField(auto_now=True, verbose_name='Update at')),
                ('jnl_active', models.IntegerField(default=1, verbose_name='Active')),
            ],
        ),
    ]
