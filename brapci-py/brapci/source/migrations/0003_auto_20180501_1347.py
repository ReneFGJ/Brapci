# Generated by Django 2.0.4 on 2018-05-01 16:47

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('source', '0002_auto_20180501_1343'),
    ]

    operations = [
        migrations.AlterField(
            model_name='sources',
            name='id_jnl',
            field=models.AutoField(primary_key=True, serialize=False, verbose_name='ID'),
        ),
    ]
