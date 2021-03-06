# Generated by Django 2.0.4 on 2018-05-06 13:23

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('source', '0018_identify_earliestdatestamp'),
    ]

    operations = [
        migrations.CreateModel(
            name='ListIdentifier',
            fields=[
                ('id_li', models.AutoField(primary_key=True, serialize=False, verbose_name='ID')),
                ('li_identifier', models.CharField(max_length=100, null=True, verbose_name='identifier')),
                ('li_datestamp', models.DateTimeField(verbose_name='Update at')),
                ('li_setSpec', models.CharField(max_length=30, null=True, verbose_name='setSpec')),
                ('li_status', models.CharField(max_length=10, null=True, verbose_name='status')),
                ('li_jnl', models.IntegerField(default=0, verbose_name='jnl')),
            ],
        ),
    ]
