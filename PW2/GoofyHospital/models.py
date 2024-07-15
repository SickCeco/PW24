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