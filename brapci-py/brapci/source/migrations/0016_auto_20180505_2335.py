# Generated by Django 2.0.4 on 2018-05-06 02:35

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('source', '0015_auto_20180505_2334'),
    ]

    operations = [
        migrations.AlterField(
            model_name='identify',
            name='repositoryName',
            field=models.CharField(max_length=100, null=True, verbose_name='repositoryName'),
        ),
    ]
