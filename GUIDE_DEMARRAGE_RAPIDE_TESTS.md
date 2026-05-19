# 🚀 GUIDE DE DÉMARRAGE RAPIDE — Tests Alpha Store

**Document:** Guide de démarrage pour exécuter les tests  
**Projet:** Alpha Store  
**Date:** Mai 2026  

---

## 📋 Documents créés

| Document | Description | Usage |
|----------|---|---|
| **PLAN_TESTS_FONCTIONNELS.md** | Plan complet avec 87 cas de test | Référence principale, détail des tests |
| **CHECKLIST_EXECUTION_TESTS.md** | Checklist avec cases à cocher | Suivi quotidien pendant exécution |
| **GUIDE_PRATIQUE_EXECUTION_TESTS.md** | Guides étape-par-étape avec outils | Comment exécuter tests avec Postman, JMeter, etc. |
| **RESSOURCES_COMPLEMENTAIRES_TESTS.md** | SQL, JSON, XML, templates | Données test et ressources |

---

## ⏱️ Quick Start (15 minutes)

### 1️⃣ Setup initial (5 min)

```bash
# Démarrer XAMPP
cd C:\xampp && xampp_start.exe

# Vérifier accès
http://localhost/AlphaStore
# Doit voir: Page d'accueil Alpha Store

# Charger données test
mysql -u root -p alphastore < test_fixtures.sql
```

### 2️⃣ Première exécution test (5 min)

```bash
# Terminal: Démarrer services Flask
cd services && python ai/app.py
# Attend: "Running on http://localhost:5001"

# Ouvrir Postman
# Importer: AlphaStore.postman_collection.json
# Exécuter: Collection Runner
```

### 3️⃣ Premier rapport (5 min)

```bash
# Ouvrir CHECKLIST_EXECUTION_TESTS.md
# Remplir domaine "Authentification" (8 tests = 10 min)
# Cocher ✅ tests réussis
# Noter bugs trouvés
```

---

## 🎯 Roadmap de test suggérée

### **Jour 1-2: CRITIQUE (Blocker release)**

```
Priority: 🔴 CRITIQUE

☐ TC-AUTH-001 à TC-AUTH-008 (Authentification) .............. 1h
☐ TC-CAT-001 à TC-CAT-006 (Catalogue) ....................... 1h  
☐ TC-CART-001 à TC-CART-008 (Panier) ....................... 1.5h
☐ TC-ORDER-001 à TC-ORDER-007 (Commandes) ................. 1.5h
☐ TC-PAY-001 à TC-PAY-005 (Paiements) ....................... 1h
☐ TC-SEC-001 à TC-SEC-010 (Sécurité) ....................... 2h

Total J1-J2: ~8.5h pour 48 tests critiques
```

### **Jour 3: HAUTE (Important)**

```
Priority: 🟠 HAUTE

☐ TC-REC-001 à TC-REC-005 (Recommandations IA) .............. 1.5h
☐ TC-CHAT-001 à TC-CHAT-007 (Chatbot) ....................... 1.5h
☐ TC-FAV-001 à TC-FAV-005 (Favoris) ......................... 1h
☐ TC-REVIEW-001 à TC-REVIEW-006 (Avis) ...................... 1.5h
☐ TC-PERF-001 à TC-PERF-008 (Performance) ................... 2h
☐ TC-INT-001 à TC-INT-005 (Intégration) ..................... 1h

Total J3: ~8.5h pour 36 tests haute priorité
```

### **Jour 4-5: MOYENNE & RÉGRESSION**

```
Priority: 🟡 MOYENNE + Smoke tests

☐ TC-PCB-001 à TC-PCB-010 (PC Builder) ....................... 2h
☐ TC-SPIN-001 à TC-SPIN-005 (Spin Wheel) ..................... 1h
☐ TC-PROFILE-001 à TC-PROFILE-006 (Profil) ................... 1.5h
☐ TC-SEARCH-001 à TC-SEARCH-005 (Recherche) .................. 1h
☐ Smoke tests (sélection tests clés) ......................... 1h

Total J4-J5: ~6.5h pour 33 tests moyenne + régression
```

**Durée totale: 3-4 jours pour 1 testeur**  
**Pour 3 testeurs en parallèle: 1 jour complet**

---

## 📊 Vue d'ensemble des tests

```
87 Total tests

├── 🔴 CRITIQUE (48) ..................... Jour 1-2
│   ├── Authentification (8)
│   ├── Catalogue (6)
│   ├── Panier (8)
│   ├── Commandes (7)
│   ├── Paiements (5)
│   └── Sécurité (10) + 4 Intégration
│
├── 🟠 HAUTE (36) ........................ Jour 3
│   ├── Recommandations IA (5)
│   ├── Chatbot (7)
│   ├── Favoris (5)
│   ├── Avis (6)
│   ├── Performance (8)
│   └── Intégration (5)
│
├── 🟡 MOYENNE (23) ..................... Jour 4-5
│   ├── PC Builder (10)
│   ├── Spin Wheel (5)
│   ├── Profil (6)
│   └── Recherche (2)
│
└── Tests Régression ..................... Continu
    └── Smoke tests (sélection clés)
```

---

## 🛠️ Outils recommandés & setup

### Minimal setup (pour commencer)

```
✅ Browser (Chrome/Firefox)          - gratuit
✅ Postman Desktop                   - gratuit
✅ Bloc-notes ou Excel               - gratuit
✅ XAMPP déjà installé              - ✅
✅ MySQL client                      - ✅

Temps setup: 15 min
```

### Setup complet (recommandé)

```
✅ Postman                           - gratuit
✅ JMeter                            - gratuit
✅ VS Code + PHP Debug               - gratuit
✅ Selenium Python                   - gratuit
✅ OWASP ZAP                         - gratuit
✅ Google Lighthouse                 - gratuit (Chrome)

Temps setup: 45 min
```

---

## 📝 Exécution manuelle simple

### Format: Manual Testing

**Aucun outil requis, juste le navigateur!**

```
Étape 1: Ouvrir CHECKLIST_EXECUTION_TESTS.md
Étape 2: Pour chaque test:
  a) Lire étapes dans le tableau
  b) Exécuter manuellement dans navigateur
  c) Vérifier résultat attendu
  d) Cocher ☐ PASS ou ☐ FAIL
  e) Noter tout problème

Exemple test TC-AUTH-001:
  ✅ Accéder /View/html/signUp.php
  ✅ Remplir: nom="Test", email="new@test.com", password="Test@1234"
  ✅ Cliquer "Créer compte"
  ✅ Vérifier: Redirection login ET email OTP reçu
  ☑ PASS ou ☐ FAIL → Noter résultat
```

---

## 🤖 Exécution automatisée (optionnel)

### Avec Postman Collection Runner

```
1. Importer: AlphaStore.postman_collection.json
2. Menu: Run → Collection Runner
3. Sélectionner "AlphaStore API Tests"
4. Configurer:
   - Iterations: 1
   - Delay between requests: 100ms
   - Stop on error: Non
5. Cliquer "Run AlphaStore API Tests"
6. Observer résultats en temps réel
7. Exporter résultats JSON
```

**Durée:** ~5 min pour 30+ API tests

---

## 🏃‍♂️ Exécution rapide (1 jour)

### Smoke tests seulement

Si délai court, exécuter seulement **ces tests critiques**:

```
TC-AUTH-004    ✅ Login
TC-CAT-001     ✅ Accueil charge
TC-CART-001    ✅ Ajouter panier
TC-ORDER-001   ✅ Créer commande
TC-PAY-001     ✅ Paiement valide
TC-SEC-001     ❌ SQL Injection (sécurité)
TC-CHAT-001    ✅ Chatbot accessible
TC-PCB-001     ✅ PC Builder charge
TC-PERF-001    ⚡ Load time accueil
TC-REC-001     ✅ Recommandations

Total: 10 tests = 30-45 min d'exécution
Coverage: 80% des fonctionnalités critiques
```

---

## 📊 Template de rapport simple

**Minimal reporting (Excel/Google Sheets):**

```
| Date | Test | Status | Notes | Bugs |
|------|------|--------|-------|------|
| 2026-05-18 | TC-AUTH-001 | ✅ PASS | Inscription OK | - |
| 2026-05-18 | TC-CAT-001 | ✅ PASS | < 3s | - |
| 2026-05-18 | TC-CART-001 | ❌ FAIL | Panier pas à jour | BUG-001 |
| ... | ... | ... | ... | ... |

RÉSUMÉ:
Total: 10 tests, PASS: 9 (90%), FAIL: 1 (10%)
Bugs: 1 critique, 0 majeurs
Recommandation: ⚠️ Approuvé avec réserves
```

---

## 🐛 Quand on trouve un bug

### Format bug minimaliste

```
Bug ID: AUTO (ex: BUG-001)
Titre: [Titre court]
Module: [Authentification/Panier/etc]
Sévérité: 🔴 Critique / 🟠 Majeur / 🟡 Mineur
Étapes repro:
  1. [Étape 1]
  2. [Étape 2]
  3. [Étape 3]
Résultat attendu: [description]
Résultat réel: [description]
Logs/Screenshot: [si applicable]

Exemple:
Bug ID: BUG-001
Titre: Panier pas mis à jour après modifier quantité
Module: Panier
Sévérité: 🔴 Critique
Étapes:
  1. Ajouter produit au panier (qty=1, prix=1500)
  2. Modifier quantité → 3
  3. Total panier: reste 1500 (devrait être 4500)
Résultat attendu: Total = 4500
Résultat réel: Total = 1500
```

---

## 📞 Troubleshooting rapide

| Problème | Solution |
|----------|----------|
| "Connection refused" | `xampp_start.exe` |
| "Database error" | `mysql -u root -p alphastore < test_fixtures.sql` |
| "Page not loading" | Vérifier: `http://localhost/AlphaStore` accessible? |
| "API timeout" | Démarrer Flask: `python services/ai/app.py` |
| "Test fails randomly" | Ajouter délai entre tests dans Postman |
| "Besoin de password test?" | `Email: test@email.com, Password: Test@1234` |
| "Postman import échoue" | Vérifier format JSON de collection |

---

## 🎓 Étapes suivantes (Après exécution)

### ✅ Après 1 cycle de test

```
1. Générer rapport simple Excel
2. Résumer bugs majeurs pour dev team
3. Re-test bugs après fix (24-48h)
4. Documenter lessons learned
```

### ✅ Amélioration continue

```
1. Automatiser tests E2E avec Cypress/Selenium
2. Intégrer dans pipeline CI/CD (GitHub Actions)
3. Ajouter tests de performance continus
4. Créer dashboard métriques test
```

### ✅ Vers la production

```
1. Exécuter full regression avant release
2. Valider en environnement staging
3. Smoke tests en production
4. Monitoring + logs en continu
```

---

## 📞 Support et questions

| Besoin | Ressource |
|--------|-----------|
| Détail cas test | [PLAN_TESTS_FONCTIONNELS.md](PLAN_TESTS_FONCTIONNELS.md) |
| Comment exécuter | [GUIDE_PRATIQUE_EXECUTION_TESTS.md](GUIDE_PRATIQUE_EXECUTION_TESTS.md) |
| Données test SQL | [RESSOURCES_COMPLEMENTAIRES_TESTS.md](RESSOURCES_COMPLEMENTAIRES_TESTS.md) |
| Checklist suivi | [CHECKLIST_EXECUTION_TESTS.md](CHECKLIST_EXECUTION_TESTS.md) |
| Question?  | Vérifier section FAQ ci-dessous |

---

## ❓ FAQ

### Q: Faut-il exécuter TOUS les 87 tests?

**R:** Non. Priorités:
- **Jour 1:** Tests critiques seulement (48) = 90% couverture
- **Jour 2+:** Ajouter tests moyens/bas priorité au besoin
- **Minimum:** Au moins les 10 smoke tests

### Q: Combien de temps pour tout tester?

**R:** 
- **Manuel:** 3-4 jours / 1 testeur
- **Postman:** ~1 jour (automatisé) 
- **JMeter:** ~2 jours (perf + charge)
- **Total:** ~3-4 jours pour équipe de 3 QA

### Q: Est-ce que je dois utiliser Postman/JMeter?

**R:** Non, optionnel:
- ✅ Tests manuels suffisent pour coverage fonctionnel
- ✅ Postman + JMeter accelerent l'exécution
- ✅ Postman idéal pour API tests
- ✅ JMeter idéal pour tests de charge

### Q: Et si je trouve un bug?

**R:** 
1. Noter ID, titre, module, sévérité
2. Documenter étapes pour reproduire
3. Ajouter à liste bugs dans rapport
4. Notifier dev team
5. Re-tester après fix

### Q: Quand doit-on arrêter les tests?

**R:**
- ✅ Tous tests critiques (🔴) réussis
- ✅ Bugs bloquants (sécurité) fixés
- ✅ Taux réussite > 95%
- ⚠️ Bugs mineurs peuvent attendre prochaine release

### Q: Comment générer rapport final?

**R:** Utiliser template dans [RESSOURCES_COMPLEMENTAIRES_TESTS.md](RESSOURCES_COMPLEMENTAIRES_TESTS.md):
1. Copier TEST_REPORT_TEMPLATE.md
2. Remplir métriques réelles
3. Lister bugs trouvés
4. Donner recommandation (Approuvé/Rejeté)
5. Faire signer par QA Lead + Tech Lead

---

## 📚 Ressources externes utiles

```
Postman: https://learning.postman.com/
JMeter:  https://jmeter.apache.org/usermanual/
Selenium: https://www.selenium.dev/documentation/
OWASP Testing: https://owasp.org/www-project-web-security-testing-guide/
```

---

## ✅ Checklist avant de commencer

- [ ] Tous les documents lus (au moins overview)
- [ ] XAMPP + MySQL démarrés
- [ ] Base de données restaurée
- [ ] Utilisateurs test créés
- [ ] Accès à http://localhost/AlphaStore confirmé
- [ ] Postman installé (optionnel mais recommandé)
- [ ] Fichier exécution ouvert (CHECKLIST_EXECUTION_TESTS.md)
- [ ] Folder outputs/screenshots créé
- [ ] Développeurs notifiés que tests vont commencer

---

## 🚀 C'est parti!

**Première étape:**

```bash
1. Ouvrir CHECKLIST_EXECUTION_TESTS.md
2. Remplir domaine "Authentification" (TC-AUTH-001 à TC-AUTH-008)
3. Durée estimée: 15-20 minutes
4. Cocher ☑ pour chaque test réussi
```

**Bonne chance! 🎯**

---

**Document créé:** Mai 2026  
**Version:** 1.0  
**Révisé le:** [DATE]  

_Pour toute question, consultez les documents principaux ou contactez le QA Lead._
