from django import forms
from .models import Source

class SourceForm(forms.ModelForm):
    class Meta:
        model = Source
        fields = ['id_jnl','jnl_name','jnl_issn_impresso'] 
        
class SourceFormConfirm(forms.ModelForm):
    class Meta:
        model = Source
        fields = ['id_jnl']         