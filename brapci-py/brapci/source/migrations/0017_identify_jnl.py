# Generated by Django 2.0.4 on 2018-05-06 02:54

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('source', '0016_auto_20180505_2335'),
    ]

    operations = [
        migrations.AddField(
            model_name='identify',
            name='jnl',
            field=models.IntegerField(default=0, verbose_name='jnl'),
        ),
    ]
