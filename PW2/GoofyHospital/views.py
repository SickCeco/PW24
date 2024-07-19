from django.http import HttpResponse # type: ignore
from django.shortcuts import render # type: ignore
from .queries import * 
from .models import *
import json

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


#CITTADINO

def cittadino_view(request):
    filtro_cssn = request.POST.get('filtro_cssn', '')
    filtro_nome = request.POST.get('filtro_nome', '')
    filtro_cognome = request.POST.get('filtro_cognome', '')
    filtro_data_nascita = request.POST.get('filtro_data_nascita', '')
    filtro_luogo_nascita = request.POST.get('filtro_luogo_nascita', '')
    filtro_indirizzo = request.POST.get('filtro_indirizzo', '')

    cittadini = Cittadino.objects.all()

    if filtro_cssn:
        cittadini = cittadini.filter(cssn__icontains=filtro_cssn)
    if filtro_nome:
        cittadini = cittadini.filter(nome__icontains=filtro_nome)
    if filtro_cognome:
        cittadini = cittadini.filter(cognome__icontains=filtro_cognome)
    if filtro_data_nascita:
        cittadini = cittadini.filter(DataNascita=filtro_data_nascita)
    if filtro_luogo_nascita:
        cittadini = cittadini.filter(LuogoNascita__icontains=filtro_luogo_nascita)
    if filtro_indirizzo:
        cittadini = cittadini.filter(indirizzo__icontains=filtro_indirizzo)

    context = {
        'results': cittadini,
        'filtro_cssn': filtro_cssn,
        'filtro_nome': filtro_nome,
        'filtro_cognome': filtro_cognome,
        'filtro_data_nascita': filtro_data_nascita,
        'filtro_luogo_nascita': filtro_luogo_nascita,
        'filtro_indirizzo': filtro_indirizzo,
    }

    return render(request, 'cittadino.html', context)



#PATOLOGIE
from .queries import select_from_patologia

def patologie(request):
    # Esistenti logica per filtro_codice, filtro_nome, filtro_criticita
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
        # Utilizzo della query select_from_patologia per ottenere il nome della patologia
        nome_patologia_result = select_from_patologia(codice)
        nome_patologia = nome_patologia_result[0][0] if nome_patologia_result else 'Nome non trovato'

        patologia = {
            'codice': info['Codice'],
            'nome': nome_patologia,  # Utilizzo del nome ottenuto dalla query
            'criticita': info['Criticità'],
            'tipi': info['Tipi'],
            'conteggio_ricoveri': len(dettagli_ricoveri.get(info['Codice'], [])),
            'dettagli_ricoveri': json.dumps(dettagli_ricoveri.get(info['Codice'], []))  # Serializza come JSON
        }
        patologie.append(patologia)

    context = {
        'patologie': patologie,
        'merged_results': merged_results,
        'dettagli_ricoveri': dettagli_ricoveri,
    }
    return render(request, 'patologie.html', context)


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

# OSPEDALE
from django.shortcuts import render, redirect
from django.http import HttpResponse
from .queries import insert_into_ospedale, update_ospedale, delete_from_ospedale, select_from_ospedale, select_direttori_liberi, select_tutti_direttori
from .models import Cittadino  # Assicurati che il modello Cittadino sia importato correttamente

def ospedali(request):
    success_message = ""
    error_message = ""

    if request.method == 'POST':
        operation = request.POST.get('operation')
        if operation == 'insert':
            nome = request.POST.get('filtro_nome')
            citta = request.POST.get('filtro_citta')
            indirizzo = request.POST.get('filtro_indirizzo')
            direttore_sanitario = request.POST.get('filtro_direttoreSanitario')

            # Esegui l'inserimento nel database
            result = insert_into_ospedale(nome, citta, indirizzo, direttore_sanitario)

            if "Errore" in result:
                error_message = result
            else:
                success_message = "Ospedale inserito con successo."

        elif operation == 'delete':
            codice = request.POST.get('filtro_codice')

            # Esegui l'eliminazione dal database
            result = delete_from_ospedale(codice)

            if "Errore" in result:
                error_message = result
            else:
                success_message = "Ospedale eliminato con successo."
        
        elif operation == 'update':
            codice = request.POST.get('filtro_codice')
            nome = request.POST.get('filtro_nome_new', '')
            citta = request.POST.get('filtro_citta_new', '')
            indirizzo = request.POST.get('filtro_indirizzo_new', '')
            direttore_sanitario = request.POST.get('filtro_direttoreSanitario_new', '')

            # Esegui l'aggiornamento nel database
            result = update_ospedale(codice, nome, citta, indirizzo, direttore_sanitario)

            if "Errore" in result:
                error_message = result
            else:
                success_message = "Ospedale aggiornato con successo."

    filtro_codice = request.POST.get('filtro_codice', '')
    filtro_nome = request.POST.get('filtro_nome', '')
    filtro_citta = request.POST.get('filtro_citta', '')
    filtro_indirizzo = request.POST.get('filtro_indirizzo', '')
    filtro_direttoreSanitario = request.POST.get('filtro_direttoreSanitario', '')

    # Query per i risultati degli ospedali
    result_set = select_from_ospedale(filtro_codice, filtro_nome, filtro_citta,
                                      filtro_indirizzo, filtro_direttoreSanitario)

    results = []
    for row in result_set:
        direttore_sanitario_cssn = row[4]
        # Query per ottenere il nome completo del direttore sanitario
        try:
            direttore = Cittadino.objects.get(cssn=direttore_sanitario_cssn)
            direttore_nome_completo = f"{direttore.nome} {direttore.cognome}"
        except Cittadino.DoesNotExist:
            direttore_nome_completo = "N/A"

        result_dict = {
            'Codice': row[0],
            'Nome': row[1],
            'Citta': row[2],
            'Indirizzo': row[3],
            'DirettoreSanitarioCSSN': direttore_sanitario_cssn,
            'DirettoreSanitarioNome': direttore_nome_completo
        }
        results.append(result_dict)

    # Query per i direttori sanitari liberi e tutti i direttori sanitari
    direttori_liberi_result_set = select_direttori_liberi()
    tutti_direttori_result_set = select_tutti_direttori()

    # Trasforma i result set in liste di dizionari
    direttori_liberi = []
    for row in direttori_liberi_result_set:
        direttori_liberi.append({
            'cssn': row[0],  
            'nome': f"{row[1]} {row[2]}"  # Unisce nome e cognome
        })

    tutti_direttori = []
    for row in tutti_direttori_result_set:
        tutti_direttori.append({
            'cssn': row[0], 
            'nome': f"{row[1]} {row[2]}"  # Unisce nome e cognome
        })

    context = {
        'result': results,
        'cod_ospedale': filtro_codice,
        'filtro_codice': filtro_codice,
        'filtro_nome': filtro_nome,
        'filtro_citta': filtro_citta,
        'filtro_indirizzo': filtro_indirizzo,
        'filtro_direttoreSanitario': filtro_direttoreSanitario,
        'direttori_liberi': direttori_liberi,
        'tutti_direttori': tutti_direttori,
        'success_message': success_message,
        'error_message': error_message,
    }

    return render(request, 'ospedali.html', context)
