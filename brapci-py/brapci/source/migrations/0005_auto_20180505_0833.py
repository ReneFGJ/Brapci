# Generated by Django 2.0.4 on 2018-05-05 11:33

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('source', '0004_auto_20180505_0809'),
    ]

    operations = [
        migrations.AlterField(
            model_name='source',
            name='jnl_cidade',
            field=models.IntegerField(default=0, null=True, verbose_name='Cidade'),
        ),
    ]
