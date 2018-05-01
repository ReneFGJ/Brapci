from django.shortcuts import render, HttpResponse
from django.template import loader


# Create your views here.
def oai_home(request):
    data = {'title' : 'Bem vindo'}
    html = render(request, 'oai/welcome.html',data)
    return html
 
def oai_identify(request):
    return HttpResponse("OAI - Identify")