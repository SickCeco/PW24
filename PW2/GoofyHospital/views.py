from django.http import HttpResponse
from django.shortcuts import render

def home(request):
    return render(request, "home.html")

def funct(request):
    return HttpResponse("Ciao Tubw")

def cittadini(request):
    return render(request, 'cittadini.html')

def ospedali(request):
    return render(request, 'ospedali.html')

def ricoveri(request):
    return render(request, 'ricoveri.html')

def patologie(request):
    return render(request, 'patologie.html')


# views.py

# views.py

from django.shortcuts import render
from .models import Cittadino

def cittadino_view(request):
    # Recupera i parametri di filtro dalla richiesta GET
    filtro_cssn = request.GET.get('filtro_cssn', '')
    filtro_nome = request.GET.get('filtro_nome', '')
    filtro_cognome = request.GET.get('filtro_cognome', '')
    filtro_data_nascita = request.GET.get('filtro_dataNascita', '')
    filtro_luogo_nascita = request.GET.get('filtro_luogoNascita', '')
    filtro_indirizzo = request.GET.get('filtro_indirizzo', '')

    # Esegui la query utilizzando il metodo del manager personalizzato
    cittadini = Cittadino.objects.select_from_cittadino(
        filtro_cssn=filtro_cssn,
        filtro_nome=filtro_nome,
        filtro_cognome=filtro_cognome,
        filtro_data_nascita=filtro_data_nascita,
        filtro_luogo_nascita=filtro_luogo_nascita,
        filtro_indirizzo=filtro_indirizzo
    )

    # Passa i dati alla template 'cittadino.html'
    return render(request, 'cittadino.html', {'cittadini': cittadini})
