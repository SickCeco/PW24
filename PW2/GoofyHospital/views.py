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
