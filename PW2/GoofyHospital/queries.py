from django.db import connection

def select_from_cittadino(filtro_cssn='', filtro_nome='', filtro_cognome='', filtro_data_nascita='', filtro_luogo_nascita='', filtro_indirizzo=''):
    query = "SELECT * FROM Cittadino WHERE 1=1"
    
    params = []
    
    if filtro_cssn:
        query += " AND CSSN = %s"
        params.append(filtro_cssn)
    if filtro_nome:
        query += " AND Nome LIKE %s"
        params.append(f"%{filtro_nome}%")
    if filtro_cognome:
        query += " AND Cognome LIKE %s"
        params.append(f"%{filtro_cognome}%")
    if filtro_data_nascita:
        query += " AND DataNascita = %s"
        params.append(filtro_data_nascita)
    if filtro_luogo_nascita:
        query += " AND LuogoNascita LIKE %s"
        params.append(f"%{filtro_luogo_nascita}%")
    if filtro_indirizzo:
        query += " AND Indirizzo LIKE %s"
        params.append(f"%{filtro_indirizzo}%")
    
    with connection.cursor() as cursor:
        cursor.execute(query, params)
        results = cursor.fetchall()
    
    return results

def select_direttore_libero():
    query = "SELECT CSSN, Nome, Cognome FROM Cittadino WHERE CSSN NOT IN (SELECT DirettoreSanitario FROM Ospedale)"
    with connection.cursor() as cursor:
        cursor.execute(query)
        results = cursor.fetchall()
    return results

def select_direttore():
    query = "SELECT CSSN, Nome, Cognome FROM Cittadino"
    with connection.cursor() as cursor:
        cursor.execute(query)
        results = cursor.fetchall()
    return results

def select_from_ricovero(filtro_cod_ospedale='', filtro_cod_ricovero='', filtro_paziente='', filtro_data_ricovero='', filtro_durata='', filtro_motivo='', filtro_costo=''):
    query = """
        SELECT r.*, pr.CodPatologia
        FROM Ricovero r
        INNER JOIN PatologiaRicovero pr ON r.CodOspedale = pr.CodOspedale AND r.CodRicovero = pr.CodRicovero
        WHERE 1=1
    """
    
    params = []
    
    if filtro_cod_ospedale:
        query += " AND r.CodOspedale LIKE %s"
        params.append(f"%{filtro_cod_ospedale}%")
    if filtro_cod_ricovero:
        query += " AND r.CodRicovero LIKE %s"
        params.append(f"%{filtro_cod_ricovero}%")
    if filtro_paziente:
        query += " AND r.Paziente LIKE %s"
        params.append(f"%{filtro_paziente}%")
    if filtro_data_ricovero:
        query += " AND r.Data = %s"
        params.append(filtro_data_ricovero)
    if filtro_durata:
        query += " AND r.Durata LIKE %s"
        params.append(f"%{filtro_durata}%")
    if filtro_motivo:
        query += " AND r.Motivo LIKE %s"
        params.append(f"%{filtro_motivo}%")
    if filtro_costo:
        query += " AND r.Costo LIKE %s"
        params.append(f"%{filtro_costo}%")
    
    with connection.cursor() as cursor:
        cursor.execute(query, params)
        results = cursor.fetchall()
    
    return results

def select_from_patologie_croniche(filtro_codice='', filtro_nome='', filtro_criticita=''):
    query = "SELECT Codice, Nome, Criticità FROM Patologia WHERE Codice IN (SELECT CodPatologia FROM PatologiaCronica)"
    
    params = []
    
    if filtro_codice:
        query += " AND Codice LIKE %s"
        params.append(f"%{filtro_codice}%")
    if filtro_nome:
        query += " AND Nome LIKE %s"
        params.append(f"%{filtro_nome}%")
    if filtro_criticita:
        query += " AND Criticità LIKE %s"
        params.append(f"%{filtro_criticita}%")
    
    with connection.cursor() as cursor:
        cursor.execute(query, params)
        results = cursor.fetchall()
    
    return results

def select_from_patologie_mortali(filtro_codice='', filtro_nome='', filtro_criticita=''):
    query = "SELECT Codice, Nome, Criticità FROM Patologia WHERE Codice IN (SELECT CodPatologia FROM PatologiaMortale)"
    
    params = []
    
    if filtro_codice:
        query += " AND Codice LIKE %s"
        params.append(f"%{filtro_codice}%")
    if filtro_nome:
        query += " AND Nome LIKE %s"
        params.append(f"%{filtro_nome}%")
    if filtro_criticita:
        query += " AND Criticità LIKE %s"
        params.append(f"%{filtro_criticita}%")
    
    with connection.cursor() as cursor:
        cursor.execute(query, params)
        results = cursor.fetchall()
    
    return results

def select_from_ospedale(filtro_codice='', filtro_nome='', filtro_citta='', filtro_indirizzo='', filtro_direttore_sanitario=''):
    query = "SELECT * FROM Ospedale WHERE 1=1"
    
    params = []
    
    if filtro_codice:
        query += " AND Codice LIKE %s"
        params.append(f"%{filtro_codice}%")
    if filtro_nome:
        query += " AND Nome LIKE %s"
        params.append(f"%{filtro_nome}%")
    if filtro_citta:
        query += " AND Citta LIKE %s"
        params.append(f"%{filtro_citta}%")
    if filtro_indirizzo:
        query += " AND Indirizzo LIKE %s"
        params.append(f"%{filtro_indirizzo}%")
    if filtro_direttore_sanitario:
        query += " AND DirettoreSanitario = %s"
        params.append(filtro_direttore_sanitario)
    
    with connection.cursor() as cursor:
        cursor.execute(query, params)
        results = cursor.fetchall()
    
    return results

def insert_into_ospedale(nome, citta, indirizzo, direttore_sanitario):
    query_check_direttore = "SELECT * FROM Ospedale WHERE DirettoreSanitario = %s"
    
    with connection.cursor() as cursor:
        cursor.execute(query_check_direttore, [direttore_sanitario])
        if cursor.fetchone():
            return "Errore: Il direttore sanitario è già direttore di un altro ospedale!"
        
        query_max_codice = "SELECT MAX(Codice) AS max_codice FROM Ospedale"
        cursor.execute(query_max_codice)
        max_codice = cursor.fetchone()[0]
        
        numero = int(max_codice[3:]) + 1 if max_codice else 1
        numero_formattato = f'{numero:02d}'
        codice = f"OSP{numero_formattato}"
        
        query_insert = "INSERT INTO Ospedale (Codice, Nome, Citta, Indirizzo, DirettoreSanitario) VALUES (%s, %s, %s, %s, %s)"
        cursor.execute(query_insert, [codice, nome, citta, indirizzo, direttore_sanitario])
    
    return "Ospedale inserito correttamente!"

def update_ospedale(codice_ospedale, nome='', citta='', indirizzo='', codice_direttore_sanitario=''):
    query_check_ospedale = "SELECT * FROM Ospedale WHERE Codice = %s"
    query_check_cittadino = "SELECT * FROM Cittadino WHERE CSSN = %s"
    query_check_libero = "SELECT * FROM Ospedale WHERE DirettoreSanitario = %s"
    
    with connection.cursor() as cursor:
        cursor.execute(query_check_ospedale, [codice_ospedale])
        if not cursor.fetchone():
            return "L'ospedale non è presente nel database!"
        
        if codice_direttore_sanitario:
            cursor.execute(query_check_cittadino, [codice_direttore_sanitario])
            if not cursor.fetchone():
                return "Il cittadino che hai selezionato non è presente nel database!"
            
            cursor.execute(query_check_libero, [codice_direttore_sanitario])
            if cursor.fetchone():
                return "Il direttore che hai selezionato è già direttore di un altro ospedale!"
        
        set_values = []
        params = []
        
        if nome:
            set_values.append("Nome = %s")
            params.append(nome)
        if citta:
            set_values.append("Citta = %s")
            params.append(citta)
        if indirizzo:
            set_values.append("Indirizzo = %s")
            params.append(indirizzo)
        if codice_direttore_sanitario:
            set_values.append("DirettoreSanitario = %s")
            params.append(codice_direttore_sanitario)
        
        if set_values:
            query_update = f"UPDATE Ospedale SET {', '.join(set_values)} WHERE Codice = %s"
            params.append(codice_ospedale)
            cursor.execute(query_update, params)
    
    return "Ospedale aggiornato correttamente!"

def delete_from_ospedale(codice):
    query_check_ospedale = "SELECT Codice FROM Ospedale WHERE Codice = %s"
    query_delete_ospedale = "DELETE FROM Ospedale WHERE Codice = %s"
    query_delete_ricovero = "DELETE FROM Ricovero WHERE CodOspedale = %s"
    query_delete_patologia_ricovero = "DELETE FROM PatologiaRicovero WHERE CodOspedale = %s"
    
    with connection.cursor() as cursor:
        cursor.execute(query_check_ospedale, [codice])
        if not cursor.fetchone():
            return "Il codice che hai selezionato non appartiene a nessun ospedale!"
        
        cursor.execute(query_delete_ricovero, [codice])
        cursor.execute(query_delete_patologia_ricovero, [codice])
        cursor.execute(query_delete_ospedale, [codice])
    
    return "Ospedale eliminato correttamente!"


def select_from_ricovero(filtro_cod_ospedale, filtro_cod_ricovero, filtro_paziente, filtro_data_ricovero, filtro_durata, filtro_motivo, filtro_costo):
    sql = """
        SELECT r.*, pr.CodPatologia, p.Nome AS PatologiaNome
        FROM Ricovero r
        INNER JOIN PatologiaRicovero pr ON r.CodOspedale = pr.CodOspedale AND r.CodRicovero = pr.CodRicovero
        INNER JOIN Patologia p ON pr.CodPatologia = p.Codice
        WHERE 1=1
    """
    filters = []
    if filtro_cod_ospedale:
        sql += " AND r.CodOspedale LIKE %s"
        filters.append('%' + filtro_cod_ospedale + '%')
    if filtro_cod_ricovero:
        sql += " AND r.CodRicovero LIKE %s"
        filters.append('%' + filtro_cod_ricovero + '%')
    if filtro_paziente:
        sql += " AND r.Paziente LIKE %s"
        filters.append('%' + filtro_paziente + '%')
    if filtro_data_ricovero:
        sql += " AND r.Data = %s"
        filters.append(filtro_data_ricovero)
    if filtro_durata:
        sql += " AND r.Durata LIKE %s"
        filters.append('%' + filtro_durata + '%')
    if filtro_motivo:
        sql += " AND r.Motivo LIKE %s"
        filters.append('%' + filtro_motivo + '%')
    if filtro_costo:
        sql += " AND r.Costo LIKE %s"
        filters.append('%' + filtro_costo + '%')

    with connection.cursor() as cursor:
        cursor.execute(sql, filters)
        results = cursor.fetchall()

    return results


def select_direttori_liberi():
    sql = """SELECT CSSN, Nome, Cognome FROM Cittadino WHERE CSSN NOT IN (SELECT DirettoreSanitario FROM Ospedale)"""
    
    with connection.cursor() as cursor:
        cursor.execute(sql)
        results = cursor.fetchall()

    return results
      
def select_tutti_direttori():
    sql = """SELECT CSSN, Nome, Cognome FROM Cittadino WHERE 1"""

    with connection.cursor() as cursor:
        cursor.execute(sql)
        results = cursor.fetchall()

    return results
    

def select_from_patologia(filtro_codice=''):
    query = "SELECT Nome FROM Patologia WHERE Codice = %s"
    
    params = [filtro_codice]
    
    with connection.cursor() as cursor:
        cursor.execute(query, params)
        result = cursor.fetchone()  # Assuming Codice is unique and only one result will be returned
    
    return result
