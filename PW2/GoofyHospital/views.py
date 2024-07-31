from django.http import HttpResponse # Funzioni per gestire le risposte HTTP
from django.shortcuts import render, redirect # Funzioni per renderizzare template e redirigere
from .queries import * # IQuery definite nel modulo queries
from .models import * # Modelli definiti nel modulo models
import json

def home(request):
    # Funzione render per la pagina principale
    return render(request, "home.html")

def ospedali(request):
    # Funzione render per la pagina degli ospedali
    return render(request, 'ospedali.html')

def ricoveri(request):
    # Funzione render per la pagina dei ricoveri
    return render(request, 'ricoveri.html')

def patologie(request):
    # Funzione render per la pagina delle patologie
    return render(request, 'patologie.html')


#CITTADINO

def cittadino(request):
    # Ottiene i parametri dal GET per i dati linkabili
    filtro_cssn_linkabile = request.GET.get('filtro_cssn', '')

    # Ottiene i parametri dal POST per i filtri di ricerca
    filtro_cssn = request.POST.get('filtro_cssn', '')
    filtro_nome = request.POST.get('filtro_nome', '')
    filtro_cognome = request.POST.get('filtro_cognome', '')
    filtro_data_nascita = request.POST.get('filtro_data_nascita', '')
    filtro_luogo_nascita = request.POST.get('filtro_luogo_nascita', '')
    filtro_indirizzo = request.POST.get('filtro_indirizzo', '')

    # Se il filtro linkabile è presente, viene usato per il filtro principale
    if filtro_cssn_linkabile:
        filtro_cssn = filtro_cssn_linkabile

    # Esegue la query per ottenere i cittadini filtrati
    result_set = select_from_cittadino(filtro_cssn, filtro_nome, filtro_cognome,
                                       filtro_data_nascita, filtro_luogo_nascita, filtro_indirizzo)

    # Converte i risultati della query in un formato utilizzabile nel template
    results = []
    for row in result_set:
        result_dict = {
            'cssn': row[0],
            'nome': row[1],
            'cognome': row[2],
            'data_nascita': row[3],
            'luogo_nascita': row[4],
            'indirizzo': row[5],
        }
        results.append(result_dict)

    # Creare il contesto da passare al template
    context = {
        'results': results,
        'filtro_cssn': filtro_cssn,
        'filtro_nome': filtro_nome,
        'filtro_cognome': filtro_cognome,
        'filtro_data_nascita': filtro_data_nascita,
        'filtro_luogo_nascita': filtro_luogo_nascita,
        'filtro_indirizzo': filtro_indirizzo,
    }

    # Renderizzare il template 'cittadino.html' con il contesto
    return render(request, 'cittadino.html', context)


#PATOLOGIE
def patologie(request):
    # Ottenere il filtro linkabile (nome) dal GET
    filtro_nome_linkabile = request.GET.get('filtro_nome', '')

    # Ottenere i filtri di ricerca dal POST
    filtro_codice = request.POST.get('filtro_codice', '')
    filtro_nome = request.POST.get('filtro_nome', '')
    filtro_criticita = request.POST.get('filtro_criticita', '')

    # Se il filtro linkabile (nome) è presente, usalo per il filtro principale
    if filtro_nome_linkabile:
        filtro_nome = filtro_nome_linkabile

    # Eseguire le query per ottenere le patologie
    result_croniche = select_from_patologie_croniche(filtro_codice, filtro_nome, filtro_criticita)
    result_mortali = select_from_patologie_mortali(filtro_codice, filtro_nome, filtro_criticita)

    # Merging dei risultati cronici e mortali
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

    # Query per ottenere i dettagli dei ricoveri
    dettagli_ricoveri_query = """
        SELECT PR.CodPatologia, R.Data, R.CodOspedale, R.CodRicovero, R.Paziente, R.Motivo, R.Costo
        FROM Ricovero R
        JOIN PatologiaRicovero PR ON R.CodRicovero = PR.CodRicovero
    """
    # Eseguire la query per ottenere i dettagli dei ricoveri
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

    # Preparare i risultati finali delle patologie
    patologie = []
    for codice, info in merged_results.items():
        nome_patologia_result = select_from_patologia(codice)
        nome_patologia = nome_patologia_result[0] if nome_patologia_result else 'Nome non trovato'

        patologia = {
            'codice': info['Codice'],
            'nome': nome_patologia,
            'criticita': info['Criticità'],
            'tipi': info['Tipi'],
            'conteggio_ricoveri': len(dettagli_ricoveri.get(info['Codice'], [])),
            'dettagli_ricoveri': json.dumps(dettagli_ricoveri.get(info['Codice'], []))  # Serializza come JSON
        }
        patologie.append(patologia)

    # Creare il contesto da passare al template
    context = {
        'patologie': patologie,
        'filtro_codice': filtro_codice,
        'filtro_nome': filtro_nome,
        'filtro_criticita': filtro_criticita,
    }
    
    # Renderizzare il template 'patologie.html' con il contesto
    return render(request, 'patologie.html', context)

# OSPEDALE
def ospedali(request):
    success_message = ""
    error_message = ""

    # Gestione delle operazioni tramite POST (inserimento, aggiornamento, eliminazione)
    if request.method == 'POST':
        operation = request.POST.get('operation')
        if operation == 'insert':
            nome = request.POST.get('filtro_nome')
            citta = request.POST.get('filtro_citta')
            indirizzo = request.POST.get('filtro_indirizzo')
            direttore_sanitario = request.POST.get('filtro_direttoreSanitario')

            result = insert_into_ospedale(nome, citta, indirizzo, direttore_sanitario)

            if "Errore" in result:
                error_message = result
            else:
                success_message = "Ospedale inserito con successo."

        elif operation == 'delete':
            codice = request.POST.get('filtro_codice')

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

            result = update_ospedale(codice, nome, citta, indirizzo, direttore_sanitario)

            if "Errore" in result:
                error_message = result
            else:
                success_message = "Ospedale aggiornato con successo."

    # Gestione dei filtri tramite GET
    filtro_codice = request.GET.get('filtro_codice', '')
    
    if filtro_codice:
        # Se il filtro codice è presente nei parametri GET, usalo per la selezione
        result_set = select_from_ospedale(filtro_codice, '', '', '', '')
    else:
        # Se il filtro codice non è presente nei parametri GET, usa i filtri POST se disponibili
        filtro_codice = request.POST.get('filtro_codice', '')
        filtro_nome = request.POST.get('filtro_nome', '')
        filtro_citta = request.POST.get('filtro_citta', '')
        filtro_indirizzo = request.POST.get('filtro_indirizzo', '')
        filtro_direttoreSanitario = request.POST.get('filtro_direttoreSanitario', '')

        result_set = select_from_ospedale(filtro_codice, filtro_nome, filtro_citta, filtro_indirizzo, filtro_direttoreSanitario)

    # Convertire i risultati della query in un formato utilizzabile nel template
    results = []
    for row in result_set:
        direttore_sanitario_cssn = row[4]
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

    # Ottenere i direttori sanitari liberi e tutti i direttori
    direttori_liberi_result_set = select_direttori_liberi()
    tutti_direttori_result_set = select_tutti_direttori()

    direttori_liberi = []
    for row in direttori_liberi_result_set:
        direttori_liberi.append({
            'cssn': row[0],  
            'nome': f"{row[1]} {row[2]}" 
        })

    tutti_direttori = []
    for row in tutti_direttori_result_set:
        tutti_direttori.append({
            'cssn': row[0], 
            'nome': f"{row[1]} {row[2]}"
        })

    # Creare il contesto da passare al template
    context = {
        'result': results,
        'filtro_codice': filtro_codice,
        'filtro_nome': filtro_nome if not filtro_codice else '',
        'filtro_citta': filtro_citta if not filtro_codice else '',
        'filtro_indirizzo': filtro_indirizzo if not filtro_codice else '',
        'filtro_direttoreSanitario': filtro_direttoreSanitario if not filtro_codice else '',
        'direttori_liberi': direttori_liberi,
        'tutti_direttori': tutti_direttori,
        'success_message': success_message,
        'error_message': error_message,
    }

    # Renderizzare il template 'ospedali.html' con il contesto
    return render(request, 'ospedali.html', context)



def ricoveri(request):
    # Ottenere i filtri di ricerca dal POST
    filtro_cod_ospedale = request.POST.get('filtro_codOspedale', '')
    filtro_cod_ricovero = request.POST.get('filtro_codRicovero', '')
    filtro_paziente = request.POST.get('filtro_paziente', '')
    filtro_data_ricovero = request.POST.get('filtro_dataRicovero', '')
    filtro_durata = request.POST.get('filtro_durata', '')
    filtro_motivo = request.POST.get('filtro_motivo', '')
    filtro_costo = request.POST.get('filtro_costo', '')

    # Eseguire la query per ottenere i ricoveri filtrati
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
            'patologia': row[8] # Nota: la patologia è mappata a index 8, non definito in query
        }
        results.append(result_dict)

    # Creare il contesto da passare al template
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

    # Renderizzare il template 'ricoveri.html' con il contesto
    return render(request, 'ricoveri.html', context)
