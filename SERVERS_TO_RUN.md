# Serveurs à démarrer pour AlphaStore

Ce document récapitule les serveurs Python et NPM détectés dans le projet.

## 1) Serveur PHP principal

Le projet principal est une application PHP hébergée sous XAMPP/Apache.

- Dossier racine du projet : `c:\xampp\htdocs\AlphaStore`
- Démarrer Apache et MySQL dans XAMPP
- Accéder ensuite au site via : `http://localhost/AlphaStore`

> Ce fichier se concentre sur les serveurs Python et NPM. Le front-end PHP fonctionne via Apache/XAMPP.

## 2) Serveurs Python Flask

### 2.1 `services/ai/app.py`

- Dossier : `c:\xampp\htdocs\AlphaStore\services\ai`
- Commande :
  ```powershell
  cd c:\xampp\htdocs\AlphaStore\services\ai
  python app.py
  ```
- Port : `5001`
- Points d'accès utilisés :
  - `/api/optimize`
  - `/api/mix-match`
  - `/api/pc-components`
  - `/api/pc-filter`
  - `/api/pc-recommend`
  - `/api/health`
- Requis pour : `View\javaScript\smart-suggestion.js`, `View\javaScript\smart-budget.js`, `View\javaScript\mix-match.js`, `Controller\PCBuildController.php`, etc.

### 2.2 `TryOn_py/files/app.py`

- Dossier : `c:\xampp\htdocs\AlphaStore\TryOn_py\files`
- Commande :
  ```powershell
  cd c:\xampp\htdocs\AlphaStore\TryOn_py\files
  python app.py
  ```
- Port : `5000`
- Point d'accès utilisé :
  - `/tryon`
- Requis pour : `View\javaScript\TryOn.js`

### 2.3 Optionnel : `services/chatBot_flask/app.py`

- Dossier : `c:\xampp\htdocs\AlphaStore\services\chatBot_flask`
- Commande :
  ```powershell
  cd c:\xampp\htdocs\AlphaStore\services\chatBot_flask
  python app.py
  ```
- Port : `5001`
- Point d'accès utilisé :
  - `/chat`
- Note : Ce service utilise le même port `5001` que `services/ai/app.py`. Ne lancez pas les deux en même temps sans modifier le port d'un des deux.

## 3) Modules npm

### 3.1 `View/run` (Next.js)

Ce dossier contient un projet Node / Next.js.

- Dossier : `c:\xampp\htdocs\AlphaStore\View\run`
- Commandes :
  ```powershell
  cd c:\xampp\htdocs\AlphaStore\View\run
  npm install
  npm run dev
  ```
- URL de développement attendue : `http://localhost:3000` (par défaut)
- Ce service n'est pas directement référencé par le site PHP principal, mais il existe dans le dépôt.

### 3.2 `package.json` racine

Le fichier racine contient des dépendances :
- `@google/generative-ai`
- `dotenv`

Il n'y a pas de script de démarrage NPM défini dans le `package.json` racine.

- Si vous voulez installer ces dépendances :
  ```powershell
  cd c:\xampp\htdocs\AlphaStore
  npm install
  ```
- Il n'y a pas de serveur Node principal à lancer automatiquement depuis ce fichier.

## 4) Remarque importante

- Le site principal fonctionne sous Apache/PHP.
- Les services Python Flask sont les seuls serveurs Python actifs identifiés.
- Le seul serveur NPM identifié est le projet `View/run`.

## 5) Résumé des serveurs à démarrer

1. Apache/XAMPP
2. `services/ai/app.py` → `http://localhost:5001`
3. `TryOn_py/files/app.py` → `http://localhost:5000`
4. Optionnel : `View/run` → `npm run dev`
5. Optionnel : `services/chatBot_flask/app.py` si vous utilisez le chatbot Flask local, en ajustant le port si nécessaire.
