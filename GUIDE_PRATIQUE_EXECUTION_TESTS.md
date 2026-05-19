# 🛠️ GUIDES PRATIQUES D'EXÉCUTION — Alpha Store Testing

**Document:** Guides pratiques pour exécuter les tests  
**Projet:** Alpha Store  
**Date:** Mai 2026  

---

## 📋 Table des matières

1. [Setup initial](#setup-initial)
2. [Tests avec Postman](#tests-avec-postman)
3. [Tests de charge avec JMeter](#tests-de-charge-avec-jmeter)
4. [Tests de sécurité](#tests-de-sécurité)
5. [Performance testing](#performance-testing)
6. [Tests E2E (Selenium)](#tests-e2e-selenium)
7. [Debugging et logs](#debugging-et-logs)

---

## Setup initial

### 1️⃣ Préparer l'environnement

```bash
# 1. Démarrer XAMPP
cd C:\xampp
xampp_start.exe

# 2. Vérifier MySQL
mysql -u root -p
USE alphastore;
SHOW TABLES;

# 3. Démarrer services Flask
cd C:\xampp\htdocs\AlphaStore\services
python ai/app.py
# Attend: "Running on http://localhost:5001"

# 4. Vérifier accès web
http://localhost/AlphaStore
```

### 2️⃣ Restaurer base de données test

```bash
# Option 1: Utiliser backup
cd C:\xampp\mysql\bin
mysql -u root -p alphastore < C:\xampp\htdocs\AlphaStore\alphastore.sql

# Option 2: Via PhpMyAdmin
# http://localhost/phpmyadmin
# Importer fichier .sql
```

### 3️⃣ Créer données test

```sql
-- Créer utilisateur test
INSERT INTO users (email, password, nom, prenom, telephone, adresse, created_at) VALUES
('test@email.com', '$2y$10$...', 'Test', 'User', '0123456789', '123 Rue Test, Algérie', NOW());

INSERT INTO users (email, password, nom, prenom, telephone, adresse, created_at) VALUES
('admin@email.com', '$2y$10$...', 'Admin', 'User', '0987654321', '456 Rue Admin, Algérie', NOW());

-- Créer produits test
INSERT INTO produits (nom, description, prix, stock, categorie, image) VALUES
('Laptop Dell XPS 13', 'Laptop performant', 1500, 10, 'Tech', 'laptop_dell.jpg'),
('T-Shirt Coton', 'Tshirt confortable', 500, 20, 'Fashion', 'tshirt.jpg'),
('Souris Logitech', 'Souris sans fil', 300, 15, 'Tech', 'mouse.jpg');
```

### 4️⃣ Sauvegarder logs de base

```bash
# Terminal 1: Logs PHP
cd C:\xampp\apache\logs
tail -f error.log

# Terminal 2: Logs MySQL
cd C:\xampp\mysql\data
# Vérifier fichier .err
```

---

## Tests avec Postman

### Installation Postman

```bash
# Télécharger depuis https://www.postman.com/downloads/
# Installer et lancer Postman
```

### 1️⃣ Créer collection AlphaStore

```
1. Postman → New → Collection → "AlphaStore"
2. Add environment variable:
   - BASE_URL: http://localhost/AlphaStore
   - USER_TOKEN: (sera rempli après login)
   - ADMIN_TOKEN: (sera rempli après admin login)
```

### 2️⃣ Test: Authentification (LOGIN)

```http
POST http://localhost/AlphaStore/Controller/AuthController.php?action=login
Content-Type: application/json

{
    "email": "test@email.com",
    "password": "Test@1234"
}
```

**Vérifier réponse:**
```json
{
    "status": "success",
    "message": "Connecté avec succès",
    "user_id": 1,
    "token": "abc123..."
}
```

**Script Postman (Tests):**
```javascript
pm.test("Status 200", function() {
    pm.response.to.have.status(200);
});

pm.test("Response has token", function() {
    var jsonData = pm.response.json();
    pm.expect(jsonData.token).to.exist;
    pm.environment.set("USER_TOKEN", jsonData.token);
});
```

### 3️⃣ Test: Créer produit au panier

```http
POST http://localhost/AlphaStore/Controller/CartController.php?action=add
Content-Type: application/json
Authorization: Bearer {{USER_TOKEN}}

{
    "product_id": 1,
    "quantity": 2
}
```

### 4️⃣ Test: Récupérer panier

```http
GET http://localhost/AlphaStore/Controller/CartController.php?action=get
Authorization: Bearer {{USER_TOKEN}}
```

### 5️⃣ Test: Créer commande

```http
POST http://localhost/AlphaStore/Controller/OrderController.php?action=create
Content-Type: application/json
Authorization: Bearer {{USER_TOKEN}}

{
    "address": "123 Rue Test",
    "city": "Alger",
    "payment_method": "card"
}
```

### 6️⃣ Test: Chatbot IA

```http
POST http://localhost/AlphaStore/Controller/ChatbotController.php?action=message
Content-Type: application/json

{
    "message": "Quel laptop recommandez-vous?"
}
```

**Attendre réponse Gemini (~2-3s):**
```json
{
    "status": "success",
    "response": "Je recommande le Laptop Dell XPS 13...",
    "processing_time_ms": 2341
}
```

### 7️⃣ Exporter résultats Postman

```bash
# Run → Collection Runner
# Sélectionner "AlphaStore"
# Iterations: 1
# Delay: 100ms
# Run
# Export results as JSON
```

---

## Tests de charge avec JMeter

### Installation JMeter

```bash
# Télécharger: https://jmeter.apache.org/download_jmeter.cgi
# Extraire: C:\tools\jmeter
# Lancer: C:\tools\jmeter\bin\jmeter.bat
```

### 1️⃣ Créer Test Plan (Accueil)

```
Test Plan
├── Thread Group
│   ├── Number of Threads: 50
│   ├── Ramp-up period: 10s
│   └── Loop Count: 1
│
├── HTTP Request Defaults
│   ├── Server Name: localhost
│   ├── Port: 80
│   └── Path: /AlphaStore
│
└── HTTP Request
    ├── Name: "Accueil"
    ├── Method: GET
    └── Path: /View/html/index.html
```

### 2️⃣ Ajouter listeners (résultats)

```
Thread Group
├── View Results Tree
├── Summary Report
└── Graph Results
```

### 3️⃣ Test scénario complet (Panier + Checkout)

```
Thread Group (50 users, 10s ramp-up)
│
├── HTTP Request: GET /View/html/index.html (Page d'accueil)
│
├── HTTP Request: GET /Controller/ProduitController.php?action=getProducts
│   (Récupérer liste produits)
│
├── HTTP Request: POST /Controller/AuthController.php?action=login
│   Body: {"email":"test@email.com","password":"Test@1234"}
│   (Login)
│
├── HTTP Request: POST /Controller/CartController.php?action=add
│   Body: {"product_id":1,"quantity":1}
│   (Ajouter au panier)
│
├── HTTP Request: POST /Controller/OrderController.php?action=create
│   Body: {"address":"Test","payment_method":"card"}
│   (Créer commande)
│
└── HTTP Request: GET /Controller/OrderController.php?action=getHistory
    (Récupérer historique)
```

### 4️⃣ Configurer Assertions

```
HTTP Request
└── Assertions
    ├── Response Assertion
    │   ├── Text: "status"
    │   └── Contains: "success"
    │
    └── Response Time Assertion
        └── < 1000ms (1 secondes)
```

### 5️⃣ Exécuter test

```
1. Menu: Run → Start (ou Ctrl+Enter)
2. Observer View Results Tree (chaque requête)
3. Observer Summary Report (statistiques globales)
4. Observer Graph Results (courbes de charge)
```

### 6️⃣ Sauvegarder résultats

```
1. View Results Tree → Right-click → "Save All as..."
   Fichier: C:\tests\jmeter_results_${DATE}.jtl

2. Summary Report → Right-click → "Save Table Data..."
   Fichier: C:\tests\summary_${DATE}.csv
```

### 7️⃣ Analyser résultats

```
Métrique           Seuil acceptable  Résultat
─────────────────────────────────────────────────
Avg Response Time  < 500ms           ✅ 342ms
90th percentile    < 1000ms          ✅ 876ms
Error Rate         < 1%              ✅ 0.2%
Throughput         > 50 req/s        ✅ 74 req/s
```

---

## Tests de sécurité

### 1️⃣ SQL Injection Test

#### Cas 1: Login form

```
Email:    ' OR '1'='1
Password: anything
```

**Résultat attendu:** ❌ Erreur "Email ou mot de passe incorrect"

#### Cas 2: Search parameter

```
GET /search?q=' OR '1'='1
```

**Vérifier:**
```bash
# Logs MySQL
tail -f C:\xampp\mysql\data\alphastore_error.log
# NE PAS voir: SELECT * FROM produits WHERE nom = ' OR '1'='1
```

### 2️⃣ XSS Test

#### Cas 1: Avis produit

```html
<script>
    alert('XSS Vulnerability!');
    fetch('/api/user/credit');
</script>
```

**Résultat attendu:** ✅ Script affiché en texte brut (échappé), sans exécution

#### Cas 2: Vérifier source HTML

```bash
# F12 → Elements → Chercher <script>
# Doit voir: &lt;script&gt; (encodé)
```

### 3️⃣ CSRF Token Test

```bash
# Postman: POST sans token CSRF
POST /Controller/OrderController.php?action=create
{
    "address": "Test"
    # Pas de csrf_token
}

# Résultat: ❌ 403 Forbidden ou "Invalid CSRF token"
```

### 4️⃣ Force Brute Protection

```bash
# Script Python: 10 tentatives login échouées rapides

import requests
import time

url = "http://localhost/AlphaStore/Controller/AuthController.php?action=login"

for i in range(10):
    response = requests.post(url, json={
        "email": "test@email.com",
        "password": "wrongpassword"
    })
    print(f"Tentative {i+1}: {response.status_code}")
    time.sleep(0.1)
```

**Résultat attendu:** 
- Tentative 1-5: 401 Unauthorized
- Tentative 6+: 429 Too Many Requests (ou compte bloqué)

### 5️⃣ HTTPS en production

```bash
# Tester redirect HTTP → HTTPS
curl -I http://alphastore.com
# HTTP/1.1 301 Moved Permanently
# Location: https://alphastore.com
```

### 6️⃣ Vérifier Password Hashing

```bash
# MySQL
USE alphastore;
SELECT email, password FROM users LIMIT 1;

# Doit voir: $2y$10$aB3cD...xyz (bcrypt hash)
# NE PAS voir: password123 (plain text)
```

### 7️⃣ Utiliser OWASP ZAP

```bash
# Télécharger: https://www.zaproxy.org/
# Lancer: zaproxy.bat

# Configuration:
# 1. Manual Explore
# 2. URL: http://localhost/AlphaStore
# 3. Start browser
# 4. Naviguer dans l'app
# 5. Active Scan
# 6. Rapport: voir vulnérabilités détectées
```

---

## Performance Testing

### 1️⃣ Google Lighthouse

```bash
# Chrome DevTools (F12)
# Lighthouse tab
# Generate report
```

**Métriques importantes:**

| Métrique | Seuil | Résultat |
|----------|-------|---------|
| FCP (First Contentful Paint) | < 2s | ✅ 1.2s |
| LCP (Largest Contentful Paint) | < 2.5s | ✅ 1.8s |
| TTI (Time to Interactive) | < 3s | ✅ 2.1s |
| CLS (Cumulative Layout Shift) | < 0.1 | ✅ 0.05 |

### 2️⃣ Apache Bench (ab)

```bash
# Windows PowerShell

# Test 1000 requêtes, 100 simultanées
ab -n 1000 -c 100 http://localhost/AlphaStore/

# Résultat:
# This is ApacheBench, Version 2.3
# Concurrency Level:      100
# Time taken for tests:   23.456 seconds
# Requests per second:    42.64 [#/sec]
# Mean time per request:  2345 ms
# Failed requests:        0
```

### 3️⃣ Siege Load Testing

```bash
# Télécharger: https://www.joedog.org/siege-home/
# Extraire et installer

# Test 1000 requêtes, 50 concurrent
siege -c 50 -r 20 http://localhost/AlphaStore/

# Résultat:
# Transactions:              1000 hits
# Availability:             100.00 %
# Elapsed time:             34.25 secs
# Data transferred:         125.34 MB
# Response time:            1.71 secs
# Transaction rate:         29.20 trans/sec
```

### 4️⃣ WebPageTest

```bash
# Site: https://www.webpagetest.org/
# URL: http://localhost/AlphaStore/ (VPN si local)
# Location: Test location
# Browser: Chrome
# Connectivity: Cable
# Run Test
```

### 5️⃣ Vérifier cache

```bash
# Requête 1
curl -w "@curl-format.txt" -o /dev/null -s http://localhost/AlphaStore/

# Requête 2 (attendre cache)
curl -w "@curl-format.txt" -o /dev/null -s http://localhost/AlphaStore/

# Comparer temps: 2ème devrait être ~30-50% plus rapide
```

---

## Tests E2E (Selenium)

### Installation Python Selenium

```bash
pip install selenium
pip install webdriver-manager
```

### Test script: Inscription et Login

```python
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import time

driver = webdriver.Chrome(ChromeDriverManager().install())

try:
    # Test 1: Inscription
    driver.get("http://localhost/AlphaStore/View/html/signUp.php")
    
    # Remplir formulaire
    nom = driver.find_element(By.ID, "nom")
    nom.send_keys("Test User")
    
    email = driver.find_element(By.ID, "email")
    email.send_keys("newuser123@test.com")
    
    password = driver.find_element(By.ID, "password")
    password.send_keys("Test@1234")
    
    # Soumettre
    submit = driver.find_element(By.ID, "submit_btn")
    submit.click()
    
    # Attendre redirection
    WebDriverWait(driver, 10).until(
        EC.url_contains("login.php")
    )
    print("✅ Test 1 PASS: Inscription réussie")
    
    # Test 2: Login
    time.sleep(2)
    driver.get("http://localhost/AlphaStore/View/html/login.php")
    
    email_login = driver.find_element(By.ID, "email")
    email_login.send_keys("newuser123@test.com")
    
    password_login = driver.find_element(By.ID, "password")
    password_login.send_keys("Test@1234")
    
    login_btn = driver.find_element(By.ID, "login_btn")
    login_btn.click()
    
    # Attendre chargement dashboard
    WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "user_dashboard"))
    )
    print("✅ Test 2 PASS: Login réussi")
    
    # Test 3: Ajouter produit au panier
    driver.get("http://localhost/AlphaStore/View/html/products.php")
    
    add_to_cart = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.CLASS_NAME, "add-to-cart-btn"))
    )
    add_to_cart.click()
    
    # Vérifier notification
    notification = WebDriverWait(driver, 5).until(
        EC.presence_of_element_located((By.CLASS_NAME, "notification"))
    )
    assert "panier" in notification.text.lower()
    print("✅ Test 3 PASS: Produit ajouté au panier")
    
except Exception as e:
    print(f"❌ Test FAILED: {e}")
    driver.save_screenshot(f"error_{time.time()}.png")
    
finally:
    driver.quit()
```

### Exécuter tests E2E

```bash
python test_e2e.py
```

---

## Debugging et logs

### 1️⃣ Activer debug mode PHP

**fichier: config/Database.php**

```php
<?php
// Ajouter au début
define('DEBUG_MODE', true);
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
```

### 2️⃣ Logs personnalisés

```php
// Dans n'importe quel controller
function logTest($message, $data = null) {
    $timestamp = date('Y-m-d H:i:s');
    $logFile = __DIR__ . '/../outputs/test_debug.log';
    
    $logMessage = "[$timestamp] $message";
    if ($data) {
        $logMessage .= "\n" . json_encode($data, JSON_PRETTY_PRINT);
    }
    $logMessage .= "\n---\n";
    
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Utiliser
logTest("Test login attempt", [
    'email' => $email,
    'timestamp' => time()
]);
```

**Lire logs:**

```bash
# Windows PowerShell
Get-Content C:\xampp\htdocs\AlphaStore\outputs\test_debug.log -Tail 50
```

### 3️⃣ Chrome DevTools Network

```
F12 → Network tab
1. Recharger page
2. Voir chaque requête
3. Cliquer sur requête pour détails (status, headers, body, response)
4. Vérifier:
   - Status 200 pour réussi, 4xx/5xx pour erreur
   - Content-Type: application/json
   - Response time
```

### 4️⃣ Chrome DevTools Console

```javascript
// Tester dans console:

// Récupérer token
localStorage.getItem('user_token')

// Tester API
fetch('/AlphaStore/Controller/ProduitController.php?action=getProducts')
  .then(r => r.json())
  .then(d => console.log(d))

// Vérifier variable globale
window.currentUser
```

### 5️⃣ MySQL Profiling

```sql
-- Activer profiling
SET profiling = 1;

-- Exécuter requête
SELECT * FROM produits WHERE categorie = 'Tech';

-- Voir profiling
SHOW PROFILES;
SHOW PROFILE FOR QUERY 1;

-- Désactiver
SET profiling = 0;
```

### 6️⃣ PHP XDebug (optionnel)

```bash
# Installer XDebug
# VS Code: Extension "PHP Debug"
# Ajouter breakpoint
# Faire requête
# Débugger en pas à pas
```

---

## Rapport de test automatisé

### Générer rapport JSON

```python
import json
import time
from datetime import datetime

test_results = {
    "date": datetime.now().isoformat(),
    "environment": "DEV",
    "version": "1.0",
    "duration_seconds": 3456,
    "total_tests": 87,
    "passed": 85,
    "failed": 2,
    "pass_rate": "97.7%",
    "tests": [
        {
            "id": "TC-AUTH-001",
            "name": "Inscription valide",
            "status": "PASS",
            "duration_ms": 245,
            "notes": "Email confirmé avec OTP"
        },
        {
            "id": "TC-CAT-001",
            "name": "Accueil chargement",
            "status": "PASS",
            "duration_ms": 2840,
            "notes": "< 3s OK"
        },
        {
            "id": "TC-PERF-001",
            "name": "Load time",
            "status": "FAIL",
            "duration_ms": 3500,
            "notes": "FCP: 2.1s, Load: 3.5s (FAILED)"
        }
    ],
    "bugs": [
        {
            "id": "BUG-001",
            "title": "Cart not updated",
            "severity": "HIGH",
            "test_case": "TC-CART-003"
        }
    ]
}

# Sauvegarder
with open(f"test_results_{datetime.now().strftime('%Y%m%d_%H%M%S')}.json", "w") as f:
    json.dump(test_results, f, indent=2)
```

### Générer rapport HTML

```python
from jinja2 import Template

html_template = """
<!DOCTYPE html>
<html>
<head>
    <title>Test Report - Alpha Store</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .pass { color: green; }
        .fail { color: red; }
        table { border-collapse: collapse; width: 100%; }
        td, th { border: 1px solid #ddd; padding: 8px; }
    </style>
</head>
<body>
    <h1>Test Report - Alpha Store</h1>
    <p>Date: {{ date }}</p>
    <p>Pass Rate: <span class="{% if pass_rate >= 95 %}pass{% else %}fail{% endif %}">{{ pass_rate }}%</span></p>
    
    <table>
        <tr><th>ID</th><th>Test Name</th><th>Status</th><th>Duration</th></tr>
        {% for test in tests %}
        <tr>
            <td>{{ test.id }}</td>
            <td>{{ test.name }}</td>
            <td class="{{ test.status.lower() }}">{{ test.status }}</td>
            <td>{{ test.duration_ms }}ms</td>
        </tr>
        {% endfor %}
    </table>
</body>
</html>
"""

template = Template(html_template)
html = template.render(
    date=datetime.now().isoformat(),
    pass_rate=97.7,
    tests=test_results["tests"]
)

with open("test_report.html", "w") as f:
    f.write(html)
```

---

## Checklist rapide avant chaque cycle de test

```bash
# ☐ XAMPP démarré
# ☐ Services Flask démarrés (port 5001)
# ☐ Base de données restaurée
# ☐ Utilisateurs test créés
# ☐ Produits test présents
# ☐ Logs vidés
# ☐ Postman collection importer/mise à jour
# ☐ JMeter plan charger/réviser
# ☐ Selenium webdriver à jour
# ☐ Variables d'environnement configurées
# ☐ Outils de monitoring démarrés (DevTools, MySQL Workbench)
# ☐ Fichier d'exécution ouvert
# ☐ Screenshots/logs répertoire prêt
```

---

## Troubleshooting

| Problème | Solution |
|----------|----------|
| "Connection refused" | XAMPP/Services Flask pas démarrés |
| "Database connection error" | Vérifier credentials, BD restaurée? |
| "API timeout > 5s" | Services Flask lent? Vérifier logs Flask |
| "CSRF token invalid" | Récupérer token depuis formulaire HTML |
| "Rate limit 429" | Attendre quelques min, ou augmenter limite |
| "Screenshot directory not found" | Créer: `mkdir C:\xampp\htdocs\AlphaStore\outputs\screenshots` |
| "JMeter: Address already in use" | Tuer process précédent ou changer port |

---

**Document créé:** Mai 2026  
**Version:** 1.0  
**Auteur:** QA Team
