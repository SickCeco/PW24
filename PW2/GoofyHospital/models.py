# models.py

from django.db import models

class CittadinoManager(models.Manager):
    def select_from_cittadino(self, filtro_cssn='', filtro_nome='', filtro_cognome='', filtro_data_nascita='', filtro_luogo_nascita='', filtro_indirizzo=''):
        query = self.get_queryset()

        if filtro_cssn:
            query = query.filter(cssn=filtro_cssn)
        if filtro_nome:
            query = query.filter(nome__icontains=filtro_nome)
        if filtro_cognome:
            query = query.filter(cognome__icontains=filtro_cognome)
        if filtro_data_nascita:
            query = query.filter(data_nascita=filtro_data_nascita)
        if filtro_luogo_nascita:
            query = query.filter(luogo_nascita__icontains=filtro_luogo_nascita)
        if filtro_indirizzo:
            query = query.filter(indirizzo__icontains=filtro_indirizzo)

        return query

class Cittadino(models.Model):
    cssn = models.CharField(max_length=20, primary_key=True)
    nome = models.CharField(max_length=100)
    cognome = models.CharField(max_length=100)
    DataNascita = models.DateField()
    LuogoNascita = models.CharField(max_length=100)
    indirizzo = models.CharField(max_length=200)

    objects = CittadinoManager()  # Collegamento del manager personalizzato

    def __str__(self):
        return f"{self.nome} {self.cognome}"
    class Meta:
        # Specifica il nome della tabella nel database
        db_table = 'Cittadino'


#PATOLOGIE
class Patologia(models.Model):
    codice = models.CharField(max_length=10, primary_key=True)
    nome = models.CharField(max_length=100)
    criticita = models.CharField(max_length=50)

    def __str__(self):
        return self.nome

class PatologiaCronica(models.Model):
    cod_patologia = models.ForeignKey(Patologia, on_delete=models.CASCADE)

class PatologiaMortale(models.Model):
    cod_patologia = models.ForeignKey(Patologia, on_delete=models.CASCADE)

class Ricovero(models.Model):
    data = models.DateField()
    cod_ospedale = models.CharField(max_length=10)
    cod_ricovero = models.CharField(max_length=10, primary_key=True)
    paziente = models.CharField(max_length=100)
    motivo = models.TextField()
    costo = models.DecimalField(max_digits=10, decimal_places=2)

class PatologiaRicovero(models.Model):
    cod_ricovero = models.ForeignKey(Ricovero, on_delete=models.CASCADE)
    cod_patologia = models.ForeignKey(Patologia, on_delete=models.CASCADE)


#RICOVERO 

from django.db import models

class Ricovero(models.Model):
    cod_ospedale = models.CharField(max_length=5)  # Suppongo lunghezza massima 5 per il codice ospedale
    cod_ricovero = models.CharField(max_length=6)  # Suppongo lunghezza massima 6 per il codice ricovero
    paziente = models.CharField(max_length=20)     # Suppongo lunghezza massima 20 per il codice paziente
    data_ricovero = models.DateField()
    durata = models.CharField(max_length=20)       # Suppongo lunghezza massima 20 per la durata
    motivo = models.CharField(max_length=255)      # Suppongo lunghezza massima 255 per il motivo
    costo = models.DecimalField(max_digits=10, decimal_places=2)  # Campo per il costo

    def __str__(self):
        return f"{self.cod_ricovero} - {self.paziente}"


class PatologiaRicovero(models.Model):
    ricovero = models.ForeignKey(Ricovero, on_delete=models.CASCADE)
    patologia = models.ForeignKey(Patologia, on_delete=models.CASCADE)

    def __str__(self):
        return f"{self.ricoveri} - {self.patologia}"
