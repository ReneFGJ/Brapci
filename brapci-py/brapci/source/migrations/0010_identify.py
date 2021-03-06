# Generated by Django 2.0.4 on 2018-05-05 21:44

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('source', '0009_auto_20180505_1453'),
    ]

    operations = [
        migrations.CreateModel(
            name='Identify',
            fields=[
                ('id', models.AutoField(primary_key=True, serialize=False, verbose_name='ID')),
                ('repositoryName', models.CharField(blank=True, max_length=100, null=True, verbose_name='repositoryName')),
                ('protocolVersion', models.CharField(blank=True, max_length=10, null=True, verbose_name='protocolVersion')),
                ('adminEmail', models.CharField(blank=True, max_length=100, null=True, verbose_name='adminEmail')),
                ('granularity', models.CharField(blank=True, max_length=10, null=True, verbose_name='granularity')),
                ('compression', models.CharField(blank=True, max_length=10, null=True, verbose_name='compression')),
            ],
        ),
    ]
