import os
from django.urls import path
from django.contrib.staticfiles import storage
from . import views

urlpatterns = [
    path('', views.oai_home, name='index'),
    path('identify', views.oai_identify, name='identify'),
]
