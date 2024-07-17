from django.db import connection

def select_from_patologie_croniche(filtro_codice, filtro_nome, filtro_criticita):
    sql = """
        SELECT Codice, Nome, Criticità 
        FROM Patologia 
        WHERE Codice IN (SELECT CodPatologia FROM PatologiaCronica)
    """
    filters = []
    if filtro_codice:
        sql += " AND Codice LIKE %s"
        filters.append('%' + filtro_codice + '%')
    if filtro_nome:
        sql += " AND Nome LIKE %s"
        filters.append('%' + filtro_nome + '%')
    if filtro_criticita:
        sql += " AND Criticità LIKE %s"
        filters.append('%' + filtro_criticita + '%')

    with connection.cursor() as cursor:
        cursor.execute(sql, filters)
        return cursor.fetchall()

def select_from_patologie_mortali(filtro_codice, filtro_nome, filtro_criticita):
    sql = """
        SELECT Codice, Nome, Criticità 
        FROM Patologia 
        WHERE Codice IN (SELECT CodPatologia FROM PatologiaMortale)
    """
    filters = []
    if filtro_codice:
        sql += " AND Codice LIKE %s"
        filters.append('%' + filtro_codice + '%')
    if filtro_nome:
        sql += " AND Nome LIKE %s"
        filters.append('%' + filtro_nome + '%')
    if filtro_criticita:
        sql += " AND Criticità LIKE %s"
        filters.append('%' + filtro_criticita + '%')

    with connection.cursor() as cursor:
        cursor.execute(sql, filters)
        return cursor.fetchall()



from django.db import connection

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
