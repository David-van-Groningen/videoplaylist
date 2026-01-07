# 🎬 Video Platform Dashboard

Een modern video admin dashboard met glassmorphism design en paars/geel kleurenschema.

## ✨ Kenmerken

### Design
- **Paars & Geel Theme** - Modern kleurenpalet met primair paars (#8b5cf6) en accent geel (#fbbf24)
- **Glassmorphism** - Semi-transparante cards met blur effecten
- **Gradient Knoppen** - Vloeiende overgangen en hover effecten
- **Responsive Design** - Werkt perfect op mobiel, tablet en desktop
- **Micro-animaties** - Subtiele animaties voor betere UX

### Functionaliteit
- **Gebruikersbeheer** - Registratie, login en admin rechten
- **Categorieën** - Maak, bewerk en verwijder video categorieën
- **Videos** - Beheer YouTube video's per categorie
- **Kleurpreview** - Live kleurvoorvertoning bij bewerken
- **Veiligheid** - Password hashing, CSRF bescherming, XSS preventie

## 🔧 Installatie

### Vereisten
- PHP 7.4 of hoger
- MySQL 5.7 of hoger
- Apache/Nginx webserver

### Stappen

1. **Clone/download het project**
   ```bash
   git clone [repo-url]
   cd video-platform
   ```

2. **Database importeren**
   ```bash
   mysql -u root -p < db.sql
   ```

3. **Config aanpassen**
   Bewerk `config.php`:
   ```php
   $dsn = 'mysql:host=localhost;dbname=video_platform;charset=utf8mb4';
   $user = 'jouw_gebruikersnaam';
   $pass = 'jouw_wachtwoord';
   ```

4. **Bestandsrechten instellen**
   ```bash
   chmod 755 assets/
   ```

5. **Inloggen**
   - URL: `http://localhost/video-platform/login.php`
   - Gebruikersnaam: `admin`
   - Wachtwoord: `admin123`

## 📁 Bestandsstructuur

```
video-platform/
├── assets/
│   ├── style.css         # Hoofdstijlen (paars/geel theme)
│   ├── app.js           # JavaScript functionaliteit
│   └── *.jpg            # Categorie afbeeldingen
├── config.php           # Database configuratie & helpers
├── index.php            # Dashboard overzicht
├── login.php            # Login pagina
├── register.php         # Registratie pagina
├── logout.php           # Uitlog handler
├── add_category.php     # Nieuwe categorie toevoegen
├── edit_category.php    # Categorie bewerken
├── delete_category.php  # Categorie verwijderen
├── add_video.php        # Nieuwe video toevoegen
├── edit_video.php       # Video bewerken
├── delete_video.php     # Video verwijderen
├── db.sql              # Database schema
└── README.md           # Deze documentatie
```

## 🎨 Kleurenschema

```css
--primary: #8b5cf6       /* Paars */
--primary-dark: #7c3aed
--primary-light: #a78bfa
--accent: #fbbf24        /* Geel */
--accent-dark: #f59e0b
--accent-light: #fcd34d
--success: #10b981       /* Groen */
--danger: #ef4444        /* Rood */
```

## 🔐 Beveiliging

- **Password Hashing** - Gebruik van `password_hash()` en `password_verify()`
- **Prepared Statements** - Alle database queries gebruikt PDO prepared statements
- **XSS Preventie** - Output escaping via `htmlspecialchars()`
- **CSRF Bescherming** - Session-based security
- **Admin Controle** - Functie checks voor admin acties

## 🚀 Gebruik

### Admin Functionaliteit
1. **Categorieën beheren**
   - Klik op "➕ Nieuwe Categorie" om een categorie toe te voegen
   - Gebruik "✏️ Bewerken" om naam, slug, afbeelding en kleur aan te passen
   - Gebruik "🗑️ Verwijderen" om categorie + alle videos te verwijderen

2. **Videos beheren**
   - Voeg videos toe via "🎬 Nieuwe Video"
   - Link YouTube URLs aan categorieën
   - Bewerk of verwijder videos per categorie

### Gebruiker Registratie
Nieuwe gebruikers kunnen zich registreren, maar hebben standaard geen admin rechten.
Om iemand admin te maken:
```sql
UPDATE users SET is_admin = 1 WHERE username = 'gebruikersnaam';
```

## 🤝 Bijdragen

Vragen, bugs of suggesties? Open een issue of stuur een pull request!

## 📄 Licentie

MIT License - Vrij te gebruiken voor persoonlijke en commerciële projecten.