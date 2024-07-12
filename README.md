
Processo per importare il progetto e avviare il server:

1)	scarica da GitHub la cartella contenente i file
2)	apri il progetto in visual studio e quindi il terminale
3)	crea il tuo ambiente virtuale, digitando:
  
		python3.12 -m venv venv
		source venv/bin/activate

		pip install -r requirements.txt

4)	verifica della corretta installazione di django:
  
		python -m django --version

5)	avvia il server:

		python manage.py runserver 
