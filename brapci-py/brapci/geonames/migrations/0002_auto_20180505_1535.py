# Generated by Django 2.0.4 on 2018-05-05 18:35

from django.db import migrations


class Migration(migrations.Migration):

    dependencies = [
        ('geonames', '0001_initial'),
    ]

    operations = [
        migrations.RenameModel(
            old_name='Places',
            new_name='Place',
        ),
    ]
