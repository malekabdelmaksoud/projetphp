# 🚗 CoVoiturage - Application de Covoiturage

> Application web de covoiturage développée en PHP/MySQL

## 🌐 Site en ligne

**🔗 [http://covatirage.free.nf](http://covatirage.free.nf)**

## 📋 Fonctionnalités

- ✅ Inscription & Connexion sécurisée
- ✅ Publier un trajet (départ, destination, date, places, prix)
- ✅ Rechercher des trajets disponibles
- ✅ Réserver un trajet
- ✅ Voir mes trajets & réservations

## 🛠️ Technologies utilisées

| Technologie | Usage |
|-------------|-------|
| PHP 8 | Backend |
| MySQL / PDO | Base de données |
| HTML5 / CSS3 | Frontend |
| InfinityFree | Hébergement |

## 🗄️ Structure de la base de données

```sql
users     → id, nom, email, password
trips     → id, user_id, departure, destination, trip_date, seats, price
bookings  → id, user_id, trip_id, created_at
```

## 🚀 Installation locale (XAMPP)

```bash
# 1. Cloner le projet
git clone https://github.com/malekabdelmaksoud/projetphp.git

# 2. Copier dans htdocs
cp -r projetphp/ c:/xampp/htdocs/covatirageprojet/

# 3. Importer la base de données
# Ouvrir phpMyAdmin → Créer BDD "covoiturage" → Importer deploy/database.sql

# 4. Lancer XAMPP et ouvrir
# http://localhost/covatirageprojet/
```

## 👨‍💻 Auteur

**Malek Abdelmaksoud** — [@malekabdelmaksoud](https://github.com/malekabdelmaksoud)
