import os
from django.urls import path
from django.contrib.staticfiles import storage
from . import views

urlpatterns = [
    path('', views.oai_home, name='index'),
    path('identify', views.oai_identify, name='identify'),
    path('identify_detail/<int:id_jnl>', views.oai_identify_detail, name='identify_detail'),
    path('identify_register', views.oai_register, name='identify_register'),
    path('identify_update/<int:id_jnl>', views.oai_update, name='identify_update'),
    path('identify_hidden/<int:id_jnl>', views.oai_hidden, name='identify_hidden'),
          
    path('identify_oai/<int:id_jnl>', views.identify_oai, name='identify_oai'),
    path('listrecords/<int:id_jnl>', views.listrecords_oai, name='listrecords_oai'),
]
