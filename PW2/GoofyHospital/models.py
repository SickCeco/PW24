# models.py
from django.db import models

#CITTADINO
class Cittadino(models.Model):
    cssn = models.CharField(max_length=255, primary_key=True)
    nome = models.CharField(max_length=255)
    cognome = models.CharField(max_length=255)
    DataNascita = models.DateField()
    LuogoNascita = models.CharField(max_length=255)
    indirizzo = models.CharField(max_length=255)

    def __str__(self):
        return f'{self.nome} {self.cognome}'
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


#OSPEDALE

class Ospedale(models.Model):
    Codice = models.CharField(max_length=5, primary_key=True)
    Nome = models.CharField(max_length=100)
    Citta = models.CharField(max_length=100)
    Indirizzo = models.CharField(max_length=100)
    DirettoreSanitario = models.CharField(max_length=16)

    def __str__(self):
        return self.nome


class DirettoreSanitario(models.Model):
    nome = models.CharField(max_length=100)
    cognome = models.CharField(max_length=100)
    cssn = models.CharField(max_length=20)

    def __str__(self):
        return f"{self.nome} {self.cognome}"