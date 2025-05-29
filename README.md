Team Members
ΑΛΕΞΑΝΤΕΡ ΜΙΣΚΑ inf2021140
ΑΛΕΞΑΝΔΡΟΣ ΓΕΩΡΓΑΚΟΠΟΥΛΟΣ inf2022032


VOICE OVER URL
https://ioniogr0-my.sharepoint.com/personal/inf2021140_ionio_gr/_layouts/15/stream.aspx?id=%2Fpersonal%2Finf2021140%5Fionio%5Fgr%2FDocuments%2F%CE%95%CE%B3%CE%B3%CF%81%CE%B1%CF%86%CE%AE%2D20250529%5F222601%2Ewebm&nav=eyJyZWZlcnJhbEluZm8iOnsicmVmZXJyYWxBcHAiOiJTdHJlYW1XZWJBcHAiLCJyZWZlcnJhbFZpZXciOiJTaGFyZURpYWxvZy1MaW5rIiwicmVmZXJyYWxBcHBQbGF0Zm9ybSI6IldlYiIsInJlZmVycmFsTW9kZSI6InZpZXcifX0&ga=1&referrer=StreamWebApp%2EWeb&referrerScenario=AddressBarCopied%2Eview%2E19a1a0d6%2D2486%2D4e38%2D83e3%2Dadef59740f7b


Ορίστε ένα απλό README με οδηγίες για να ανοίξει κάποιος την ιστοσελίδα σου (με Docker):

⸻

PlaylistApp

Οδηγίες εκκίνησης με Docker
	1.	Άνοιξε τερματικό και πήγαινε στον φάκελο του project:

cd /path/to/playlistapp


	2.	Εκκίνηση των containers με Docker Compose:

docker compose up --build


	3.	Περίμενε μέχρι να τρέξει η βάση και το site.
	•	Η πρώτη φορά μπορεί να πάρει 1-2 λεπτά.
	4.	Άνοιξε τον browser και μπες στη διεύθυνση:

http://localhost:8080


	5.	Χρήσιμες λειτουργίες:
	•	Δημιούργησε λογαριασμό (Register)
	•	Κάνε Login (ή με Google αν έχει ενεργοποιηθεί)
	•	Πρόσθεσε/επεξεργάσου playlist και βίντεο
	6.	Τερματισμός containers (όταν τελειώσεις):

docker compose down



⸻

Tip:
Για αλλαγές στη βάση, χρησιμοποίησε το Adminer (αν υπάρχει):

http://localhost:8081

(username: user, password: userpass, database: mydatabase)

