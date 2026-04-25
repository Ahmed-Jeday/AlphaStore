# 🛒 ShopAdmin — Dashboard E-Commerce (PFA)

Panneau d'administration complet pour une application e-commerce.
**PHP natif + PDO + MySQL + Bootstrap 5 + Chart.js**

---

## 📁 Structure du projet

```
/admin
├── config/
│   ├── database.php         ← Connexion PDO (Singleton)
│   └── schema.sql           ← Schéma + données de démo
│
├── includes/
│   ├── header.php           ← HTML head + CSS global
│   ├── sidebar.php          ← Menu latéral fixe
│   ├── navbar.php           ← Barre navigation + flash messages
│   └── footer.php           ← Fermeture HTML + Bootstrap JS
│
├── models/
│   ├── Product.php          ← Requêtes PDO produits
│   ├── User.php             ← Requêtes PDO utilisateurs
│   └── Order.php            ← Requêtes PDO commandes
│
├── controllers/
│   ├── ProductController.php ← Logique CRUD produits + upload image
│   ├── UserController.php    ← Activation/désactivation utilisateurs
│   ├── OrderController.php   ← Suivi et mise à jour statuts
│   └── StatsController.php   ← Agrégation données statistiques
│
├── views/
│   ├── dashboard.php         ← KPIs + graphiques Chart.js
│   ├── favorites.php         ← Podium + classement favoris
│   ├── products/
│   │   ├── index.php         ← Liste + recherche + suppression
│   │   ├── create.php        ← Formulaire ajout + upload image
│   │   └── edit.php          ← Formulaire modification
│   ├── users/
│   │   └── index.php         ← Liste + toggle actif/inactif
│   ├── orders/
│   │   ├── index.php         ← Liste + filtre par statut
│   │   └── show.php          ← Détail + timeline + changement statut
│   └── stats/
│       └── index.php         ← 3 graphiques + tables analytiques
│
├── uploads/products/         ← Images uploadées
├── index.php                 ← Routeur principal (Front Controller)
└── login.php                 ← Page authentification admin
```

---

## 🚀 Installation rapide

### 1. Copier les fichiers
```bash
cp -r admin/ /var/www/html/  # XAMPP/LAMP
# ou dans htdocs/ pour WAMP
```

### 2. Créer la base de données
```bash
mysql -u root -p < config/schema.sql
```
Ou importer `schema.sql` via **phpMyAdmin**.

### 3. Configurer la connexion
Éditer `config/database.php` :
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'ecommerce_db');
define('DB_USER', 'root');       // ton user MySQL
define('DB_PASS', '');           // ton mot de passe
```

### 4. Droits d'écriture pour les uploads
```bash
chmod 755 uploads/products/
```

### 5. Accéder à l'application
```
http://localhost/admin/login.php
```

**Identifiants de démo :**
- Email : `admin@shop.com`
- Mot de passe : `password`

---

## ✅ Fonctionnalités implémentées

| Module          | Fonctionnalité                                      |
|-----------------|-----------------------------------------------------|
| **Dashboard**   | KPIs (CA, commandes, users, produits)               |
|                 | Graphique ventes 7 jours (Chart.js line)            |
|                 | Graphique revenus mensuels (Chart.js bar)           |
|                 | Commandes récentes + top produits                   |
|                 | Alertes stock faible                                |
| **Produits**    | CRUD complet                                        |
|                 | Upload image (JPG/PNG/WebP, 5 Mo max)               |
|                 | Validation MIME côté serveur                        |
|                 | Recherche côté client                               |
|                 | Badge stock (OK / faible / vide)                    |
| **Utilisateurs**| Liste avec stats (commandes + dépenses)             |
|                 | Activation / désactivation (toggle)                 |
|                 | Suppression avec confirmation modale                |
| **Commandes**   | Liste avec filtres par statut                       |
|                 | Page détail avec articles + sous-totaux             |
|                 | Timeline visuelle des statuts                       |
|                 | Mise à jour statut (pending→processing→shipped→delivered) |
| **Favoris**     | Podium top 3 + classement avec barres de progression |
| **Statistiques**| Graphique revenus 30j (double axe)                  |
|                 | Donut top 5 produits                               |
|                 | Barres revenus mensuels                            |
|                 | Table top produits avec barre visuelle             |

---

## 🔐 Sécurité

- Sessions PHP (protection toutes les pages admin)
- Requêtes PDO préparées (protection injection SQL)
- `htmlspecialchars()` sur toutes les sorties (protection XSS)
- Validation MIME côté serveur pour les uploads
- Hashage bcrypt des mots de passe

---

## 🛠️ Technologies

- **Backend** : PHP 8.x (PDO, sessions, upload)
- **Base de données** : MySQL 5.7+ / MariaDB 10+
- **Frontend** : Bootstrap 5.3 + Bootstrap Icons 1.11
- **Graphiques** : Chart.js 4.4
- **Fonts** : Plus Jakarta Sans (Google Fonts)

---

## 📝 Notes PFA

Ce projet respecte l'architecture **MVC simplifié** :
- **Model** → `models/` : accès base de données uniquement
- **View** → `views/` : affichage HTML uniquement
- **Controller** → `controllers/` : logique métier uniquement
- **Router** → `index.php` : dispatch des requêtes

Chaque classe utilise l'injection de dépendance via `Database::getInstance()` (pattern Singleton).
