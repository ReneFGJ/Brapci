# Generated by Django 2.0.4 on 2018-05-05 17:11

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('source', '0006_auto_20180505_0834'),
    ]

    operations = [
        migrations.AlterField(
            model_name='source',
            name='jnl_url',
            field=models.CharField(blank=True, max_length=200, null=True, verbose_name='URL'),
        ),
        migrations.AlterField(
            model_name='source',
            name='jnl_url_oai',
            field=models.CharField(blank=True, max_length=200, null=True, verbose_name='URL OAI-PMH'),
        ),
    ]