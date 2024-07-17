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


#PATOLOGIE
from .queries import select_from_patologie_croniche, select_from_patologie_mortali
from django.db import connection

def patologie(request):
    filtro_codice = request.POST.get('filtro_codice', '')
    filtro_nome = request.POST.get('filtro_nome', '')
    filtro_criticita = request.POST.get('filtro_criticita', '')

    result_croniche = select_from_patologie_croniche(filtro_codice, filtro_nome, filtro_criticita)
    result_mortali = select_from_patologie_mortali(filtro_codice, filtro_nome, filtro_criticita)

    merged_results = {}
    for row in result_croniche:
        codice = row[0]
        if codice not in merged_results:
            merged_results[codice] = {'Codice': codice, 'Nome': row[1], 'Criticità': row[2], 'Tipi': ['Cronica']}
        else:
            merged_results[codice]['Tipi'].append('Cronica')
    
    for row in result_mortali:
        codice = row[0]
        if codice not in merged_results:
            merged_results[codice] = {'Codice': codice, 'Nome': row[1], 'Criticità': row[2], 'Tipi': ['Mortale']}
        else:
            merged_results[codice]['Tipi'].append('Mortale')

    dettagli_ricoveri_query = """
        SELECT PR.CodPatologia, R.Data, R.CodOspedale, R.CodRicovero, R.Paziente, R.Motivo, R.Costo
        FROM Ricovero R
        JOIN PatologiaRicovero PR ON R.CodRicovero = PR.CodRicovero
    """
    with connection.cursor() as cursor:
        cursor.execute(dettagli_ricoveri_query)
        dettagli_ricoveri_result = cursor.fetchall()

    dettagli_ricoveri = {}
    for row in dettagli_ricoveri_result:
        if row[0] not in dettagli_ricoveri:
            dettagli_ricoveri[row[0]] = []
        dettagli_ricoveri[row[0]].append({
            'Data': row[1],
            'CodOspedale': row[2],
            'CodRicovero': row[3],
            'Paziente': row[4],
            'Motivo': row[5],
            'Costo': row[6]
        })

    patologie = []
    for codice, info in merged_results.items():
        patologia = {
            'codice': info['Codice'],
            'nome': info['Nome'],
            'criticita': info['Criticità'],
            'tipi': info['Tipi'],
            'conteggio_ricoveri': len(dettagli_ricoveri.get(info['Codice'], [])),
            'dettagli_ricoveri': dettagli_ricoveri.get(info['Codice'], [])
        }
        patologie.append(patologia)

    context = {
        'patologie': patologie,
        'merged_results': merged_results,
        'dettagli_ricoveri': dettagli_ricoveri,
    }
    return render(request, 'patologie.html', context)



from django.shortcuts import render
from .queries import select_from_ricovero

def ricoveri(request):
    filtro_cod_ospedale = request.POST.get('filtro_codOspedale', '')
    filtro_cod_ricovero = request.POST.get('filtro_codRicovero', '')
    filtro_paziente = request.POST.get('filtro_paziente', '')
    filtro_data_ricovero = request.POST.get('filtro_dataRicovero', '')
    filtro_durata = request.POST.get('filtro_durata', '')
    filtro_motivo = request.POST.get('filtro_motivo', '')
    filtro_costo = request.POST.get('filtro_costo', '')

    result_set = select_from_ricovero(filtro_cod_ospedale, filtro_cod_ricovero, filtro_paziente,
                                      filtro_data_ricovero, filtro_durata, filtro_motivo, filtro_costo)

    # Convertire i risultati della query in un formato utilizzabile nel template
    results = []
    for row in result_set:
        result_dict = {
            'cod_ospedale': row[0],
            'cod_ricovero': row[1],
            'paziente': row[2],
            'data_ricovero': row[3],
            'durata': row[4],
            'motivo': row[5],
            'costo': row[6],
            'patologia': row[8] 
        }
        results.append(result_dict)

    context = {
        'result': results,
        'filtro_codOspedale': filtro_cod_ospedale,
        'filtro_codRicovero': filtro_cod_ricovero,
        'filtro_paziente': filtro_paziente,
        'filtro_dataRicovero': filtro_data_ricovero,
        'filtro_durata': filtro_durata,
        'filtro_motivo': filtro_motivo,
        'filtro_costo': filtro_costo,
    }

    return render(request, 'ricoveri.html', context)
