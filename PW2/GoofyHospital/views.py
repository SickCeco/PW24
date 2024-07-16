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



from django.shortcuts import render
from .models import Cittadino

def cittadino_view(request):
    filtro_cssn = request.GET.get('filtro_cssn', '')
    filtro_nome = request.GET.get('filtro_nome', '')
    filtro_cognome = request.GET.get('filtro_cognome', '')
    filtro_dataNascita = request.GET.get('filtro_dataNascita', '')
    filtro_luogoNascita = request.GET.get('filtro_luogoNascita', '')
    filtro_indirizzo = request.GET.get('filtro_indirizzo', '')

    cittadini = Cittadino.objects.all()

    if filtro_cssn:
        cittadini = cittadini.filter(cssn__icontains=filtro_cssn)
    if filtro_nome:
        cittadini = cittadini.filter(nome__icontains=filtro_nome)
    if filtro_cognome:
        cittadini = cittadini.filter(cognome__icontains=filtro_cognome)
    if filtro_dataNascita:
        cittadini = cittadini.filter(data_nascita=filtro_dataNascita)
    if filtro_luogoNascita:
        cittadini = cittadini.filter(luogo_nascita__icontains=filtro_luogoNascita)
    if filtro_indirizzo:
        cittadini = cittadini.filter(indirizzo__icontains=filtro_indirizzo)

    context = {
        'cittadini': cittadini,
        'request': request,
    }

    return render(request, 'cittadino.html', context)

