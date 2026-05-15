# Guide de Test de Performance pour AlphaStore avec JMeter

Ce guide vous explique comment configurer et exécuter des tests de charge et de performance sur votre application **AlphaStore**.

## 1. Installation de JMeter

1. **Téléchargement** : Allez sur le site officiel d'Apache JMeter : [jmeter.apache.org](https://jmeter.apache.org/download_jmeter.cgi).
2. **Extraction** : Téléchargez le fichier `.zip` (pour Windows) et extrayez-le.
3. **Lancement** : Allez dans le dossier `bin` et double-cliquez sur `jmeter.bat`.

---

## 2. Configuration du Plan de Test (Test Plan)

### Étape A : Créer un groupe d'utilisateurs (Thread Group)
1. Faites un clic droit sur **Test Plan** > **Add** > **Threads (Users)** > **Thread Group**.
2. Configurez les paramètres :
   - **Number of Threads (users)** : Nombre d'utilisateurs simultanés (ex: 50).
   - **Ramp-up period** : Temps pour lancer tous les utilisateurs (ex: 10 secondes).
   - **Loop Count** : Combien de fois répéter le test (ex: 1 ou Infinite).

### Étape B : Configurer l'URL de base (HTTP Request Defaults)
1. Clic droit sur **Thread Group** > **Add** > **Config Element** > **HTTP Request Defaults**.
2. **Server Name or IP** : `localhost`
3. **Port Number** : `80` (ou votre port XAMPP habituel).
4. **Path** : `/AlphaStore/`

---

## 3. Ajout des Scénarios de Test

### Test 1 : Accès à la Page d'Accueil
1. Clic droit sur **Thread Group** > **Add** > **Sampler** > **HTTP Request**.
2. Nommez-le : `Page Accueil`.
3. **Path** : `View/html/index.html`

### Test 2 : Accès à la Page Produit
1. Clic droit sur **Thread Group** > **Add** > **Sampler** > **HTTP Request**.
2. Nommez-le : `Détails Produit`.
3. **Path** : `View/html/product_details.php`

### Test 3 : Simulation de Connexion (POST Request)
1. Clic droit sur **Thread Group** > **Add** > **Sampler** > **HTTP Request**.
2. Nommez-le : `Tentative de Connexion`.
3. **Method** : `POST`
4. **Path** : `View/html/signUp.php`
5. Ajoutez les paramètres dans l'onglet **Parameters** (ex: `email`, `password`) selon les noms de champs dans votre code.

---

## 4. Visualisation des Résultats (Listeners)

Pour voir ce qui se passe, ajoutez des "Listeners" :
1. Clic droit sur **Thread Group** > **Add** > **Listener** > **View Results Tree** (pour voir chaque requête).
2. Clic droit sur **Thread Group** > **Add** > **Listener** > **Summary Report** (pour les statistiques globales).
3. Clic droit sur **Thread Group** > **Add** > **Listener** > **Graph Results**.

---

## 5. Exécution du Test

1. Cliquez sur l'icône **Flèche Verte (Start)** dans la barre d'outils.
2. JMeter vous demandera d'enregistrer votre plan de test (`.jmx`).
3. Observez les résultats en temps réel dans le **View Results Tree**.

---

## Conseils pour AlphaStore
- **Base de données** : Comme AlphaStore utilise une base de données MySQL, surveillez la consommation de ressources de XAMPP pendant le test.
- **Points de rupture** : Augmentez progressivement le nombre de Threads (10, 50, 100, 500) pour trouver à quel moment l'application commence à ralentir.
