# Video Platform

## Projectomschrijving

Dit project is een eenvoudig video platform waarmee gebruikers video’s kunnen bekijken per categorie.  
Admins kunnen categorieën en video’s beheren (toevoegen, aanpassen en verwijderen).  

Het platform is bedoeld voor kleine projecten of schoolopdrachten waar je overzichtelijk video’s wilt organiseren.  
Het lost het probleem op van het bijhouden en structureren van video’s in categorieën met een simpele admin-omgeving.

---

## Functionaliteiten

- Gebruikers kunnen inloggen
- Overzicht van categorieën bekijken
- Video’s per categorie bekijken
- Admin kan:
  - Categorieën toevoegen, bewerken en verwijderen
  - Video’s toevoegen, bewerken en verwijderen
- Beveiligde database queries met prepared statements

---

## Installatie-instructies

1. **Bestanden plaatsen**
   - Plaats alle projectbestanden in je webserver map (bijv. `htdocs` bij XAMPP of `www` bij WAMP).

2. **Database importeren**
   - Open phpMyAdmin
   - Maak een nieuwe database aan, bijvoorbeeld: `video_platform`
   - Importeer het bestand `db.sql` in deze database

3. **Database-instellingen aanpassen**
   - Open `config.php`
   - Pas deze regels aan naar jouw omgeving:
     ```php
     $dsn = 'mysql:host=localhost;dbname=video_platform;charset=utf8mb4';
     $user = 'jouw_db_gebruiker';
     $pass = 'jouw_db_wachtwoord';
     ```

4. **Project starten**
   - Ga in je browser naar:
     ```
     http://localhost/jouw-projectmap/
     ```
   - Log in met een gebruiker die admin-rechten heeft (of maak er één aan in de database).

---

## Gebruikte technieken

- **PHP** (backend)
- **MySQL** (database)
- **HTML & CSS** (frontend)
- **PDO** voor veilige database connecties en queries
