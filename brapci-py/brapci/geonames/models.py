from django.db import models
#http://api.geonames.org/postalCodeSearch?placename=Porto%20Alegre&username=renefgj
# GeoName API
# http://www.geonames.org/export/geonames-search.html
# http://api.geonames.org/searchJSON?q=london&maxRows=10&username=demo

# Create your models here.
class Place(models.Model):
    
    id_geo = models.AutoField('ID' , primary_key = True)
    geo_uri = models.CharField('URI' , max_length=20, null=True, blank = True)
    geo_postal = models.CharField('Código postal' , max_length=20, null=True, blank = True)
    geo_name = models.CharField('Local' , max_length=20, null=True, blank = True)
    geo_countryCode = models.CharField('Country' , max_length=2, null=True, blank = True)
    geo_lat = models.FloatField('Lat.' , max_length=20, null=True, blank = True)
    geo_lng = models.FloatField('Log.' , max_length=20, null=True, blank = True)
    geo_adminCode1 = models.IntegerField('JurCod1' , null=True, blank = True, default = 0)
    geo_adminName1 = models.CharField('Juridisção 1' , max_length=40, null=True, blank = True)
    geo_adminCode2 = models.IntegerField('JurCod2' , null=True, blank = True, default = 0)
    geo_adminName2 = models.CharField('Juridisção 2' , max_length=40, null=True, blank = True)
    geo_adminCode3 = models.IntegerField('JurCod3' , null=True, blank = True, default = 0)
    geo_adminName3 = models.CharField('Juridisção 3' , max_length=40, null=True, blank = True)

    def __str__(self):
        return self.geo_name