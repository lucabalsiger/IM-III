Kurzbeschreibung

Berner Sonne ist ein kleines datenjournalistisches Webprojekt, das den täglichen Wasserbedarf von Pflanzen einschätzt. Dafür werden aktuelle UV- und Temperaturdaten über eine Wetter-API geholt, in einer Datenbank gespeichert und mit PHP verarbeitet. Nutzer:innen geben ihre Topfgröße und die Tage seit dem letzten Gießen ein – daraus berechnet das Tool eine grobe Bewässerungsempfehlung in Millilitern.

⸻
Learnings
	•	Wie man mit PHP Daten automatisiert aus einer API holt und speichert (ETL-Prozess).
	•	Aufbau einer eigenen Datenbank und Anbindung ans Frontend.
	•	Umgang mit zeitabhängigen Daten (z. B. UV-Index, Temperatur).
	•	Dynamische DOM-Manipulation mit JavaScript.
	•	Besseres Verständnis, wie Datenjournalismus technisch funktioniert – von der API bis zur Visualisierung.

⸻

Schwierigkeiten
	•	API Datenbank ABfrage war anfangs fälschlicherweise auf einmal täglich eingestellt, wurde nach 2 Tagen auf stündlich korrigiert.
	•	Berechnungslogik (wie viel Wasser braucht die Pflanze wirklich?) ist nicht wirklich wissenschaftlich und nachwievor total unlogisch ;-)
	•	Verbindung zwischen Frontend und Backend korrekt hinzubekommen war tricky.

⸻

Benutzte Ressourcen
	•	Technik: PHP, MySQL, HTML, CSS, JavaScript
	•	Tools: VS Code, GitHub, SFTP-Upload auf Infomaniak-Server
	•	API: Wetter- und UV-Daten (z. B. Open-Meteo / OpenWeather)
	•	Kursmaterial: IM3 – Datenjournalismus & Backend-Integration (HS 25)
	•	Weitere Hilfen: ChatGPT für Code-Erklärungen & Fehlersuche, Dokumentationen der API
  • Menschliche Ressourcen (Coaches, Dozenten) Danke ;-)
