from django.shortcuts import render, HttpResponse, get_object_or_404, redirect
from django.template import loader
from source.models import Source, Identify
from source.forms import SourceForm, SourceFormConfirm
from django.core import serializers
import xml.etree.ElementTree as ET

# Create your views here.
def oai_home(request):
    data = {'title' : 'Bem vindo'}
    html = render(request, 'welcome.html',data)
    return html

def oai_identify(request):
    srcs = Source.objects.filter(jnl_active=1).order_by('jnl_name')[:20]
    template = loader.get_template('identify.html')
    context = {
        'source_list': srcs,
        'new': True
    }
    return render(request, 'identify.html',context)

def oai_identify_detail(request, id_jnl):
    srcs = get_object_or_404(Source, pk=id_jnl)
    template = loader.get_template('identify_detail.html')
    context = {
        'jnl': srcs, 'oai': True , 'edit': True
    }
    return render(request, 'identify_detail.html',context)

def oai_register(request):
    form = SourceForm(request.POST or None)
    
    if form.is_valid():
        form.save()
        return redirect('identify')
    
    return render(request,'identify_registrer.html', {'form':form} )

def oai_update(request, id_jnl):
    srcs = get_object_or_404(Source, pk=id_jnl)
    form = SourceForm(request.POST or None, instance=srcs)
    
    if form.is_valid():
        form.save()
        return redirect('identify')
    
    return render(request,'identify_registrer.html', {'form':form, 'jnl': srcs} )

def oai_hidden(request, id_jnl):
    srcs = get_object_or_404(Source, pk=id_jnl)
    form = SourceFormConfirm(request.POST or None, instance=srcs)
    
    if form.is_valid():
        srcs.jnl_active = 0
        srcs.save()
        return redirect('identify')
    
    return render(request,'identify_hidden.html', {'form':form, 'jnl': srcs} )

# ListRecords #####################################################################
def listrecords_oai(request, id_jnl):
    srcs = get_object_or_404(Source, pk=id_jnl)    
    return render(request,'oai_identify.html', {'jnl': srcs, 'oai': True} )
    
def identify_oai(request, id_jnl):
    srcs = get_object_or_404(Source, pk=id_jnl)
    
    reg = Identify()    
    tree = ET.parse('file.xml')
    root = tree.getroot()

    for regs in root.findall('{http://www.openarchives.org/OAI/2.0/}Identify'):
        reg.repositoryName  = regs.find('{http://www.openarchives.org/OAI/2.0/}repositoryName').text
        reg.baseURL  = regs.find('{http://www.openarchives.org/OAI/2.0/}baseURL').text
        reg.protocolVersion  = regs.find('{http://www.openarchives.org/OAI/2.0/}protocolVersion').text
        reg.adminEmail  = regs.find('{http://www.openarchives.org/OAI/2.0/}adminEmail').text
        reg.deletedRecord  = regs.find('{http://www.openarchives.org/OAI/2.0/}deletedRecord').text
        reg.granularity  = regs.find('{http://www.openarchives.org/OAI/2.0/}granularity').text
        reg.compression  = regs.find('{http://www.openarchives.org/OAI/2.0/}compression').text
        reg.earliestDatestamp = regs.find('{http://www.openarchives.org/OAI/2.0/}earliestDatestamp').text
        reg.jnl = id_jnl
        
    regz = Identify.objects.filter(repositoryName = reg.repositoryName).count()
    if (regz == 0):
        reg.save()
            
    context = { 'reg': reg, 'jnl': srcs , 'oai': True}
    return render(request, 'oai_identify.html',context)


