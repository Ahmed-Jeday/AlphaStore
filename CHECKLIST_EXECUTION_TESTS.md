# 📋 CHECKLIST D'EXÉCUTION DES TESTS — Alpha Store

**Document:** Checklist d'Exécution et Suivi de Tests  
**Projet:** Alpha Store  
**Date:** Mai 2026  

---

## 🚀 AVANT TOUT TEST

### Configuration environnement
- [ ] XAMPP démarré (Apache + MySQL)
- [ ] `http://localhost/AlphaStore/` accessible
- [ ] Base de données `alphastore` restaurée (données propres)
- [ ] Services Flask démarrés (`python services/ai/app.py` sur port 5001)
- [ ] Variables d'environnement configurées (Gemini API Key, Stripe Key, etc.)
- [ ] Postman/JMeter installés
- [ ] Chrome/Firefox dernière version
- [ ] DevTools activé pour debugging

### Données test créées
- [ ] Utilisateur test 1 : `test@email.com` / `Test@1234`
- [ ] Utilisateur test 2 : `admin@email.com` / `AdminPass456`
- [ ] Au moins 5 produits Tech avec stock
- [ ] Au moins 5 produits Fashion avec stock
- [ ] Produits avec stock = 0 pour tester
- [ ] Adresses test pour livraison

### Documentations
- [ ] Ce plan de tests imprimé/accès
- [ ] Credentials securisées en fichier séparé
- [ ] Template rapport test préparé
- [ ] Fichier logs test créé

---

## 🔐 DOMAINE : AUTHENTIFICATION (8 tests)

**Testeur:** ___________  
**Date début:** ___________  
**Date fin:** ___________  

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-AUTH-001** Inscription valide | 1. Accéder signup 2. Entrer données 3. Confirmer OTP | ✅ Compte créé, connecté | ☐ PASS ☐ FAIL | |
| 2 | **TC-AUTH-002** Email doublon | 1. S'inscrire avec existant | ❌ Erreur "Email utilisé" | ☐ PASS ☐ FAIL | |
| 3 | **TC-AUTH-003** Password faible | 1. Password < 8 char | ❌ Erreur validation | ☐ PASS ☐ FAIL | |
| 4 | **TC-AUTH-004** Login valide | 1. Login 2. Vérifier dashboard | ✅ Connecté | ☐ PASS ☐ FAIL | |
| 5 | **TC-AUTH-005** Login incorrect | 1. Mauvais password | ❌ Erreur auth | ☐ PASS ☐ FAIL | |
| 6 | **TC-AUTH-006** Reset password | 1. Oublié? 2. Email reçu 3. Changer | ✅ Password changé | ☐ PASS ☐ FAIL | |
| 7 | **TC-AUTH-007** Session persistante | 1. Login 2. F5 rafraîchir | ✅ Toujours connecté | ☐ PASS ☐ FAIL | |
| 8 | **TC-AUTH-008** Logout | 1. Déconnexion 2. Accès zone protégée | ✅ Redirection login | ☐ PASS ☐ FAIL | |

**Résumé auth:** ___/8 réussis | Bugs trouvés: ___

---

## 📦 DOMAINE : CATALOGUE & PRODUITS (6 tests)

**Testeur:** ___________  
**Date:** ___________  

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-CAT-001** Accueil charge | Accéder accueil | ✅ < 3s, produits affichés | ☐ PASS ☐ FAIL | Temps: ___ms |
| 2 | **TC-CAT-002** Navigation catégories | Tech → Fashion | ✅ Bons produits affichés | ☐ PASS ☐ FAIL | |
| 3 | **TC-CAT-003** Détails produit | Cliquer produit | ✅ Image, desc, prix, stock, avis | ☐ PASS ☐ FAIL | |
| 4 | **TC-CAT-004** Pagination | Page 1 → 2 → 1 | ✅ Contenu change | ☐ PASS ☐ FAIL | |
| 5 | **TC-CAT-005** Tri prix | Croissant → Décroissant | ✅ Ordre correct | ☐ PASS ☐ FAIL | |
| 6 | **TC-CAT-006** Stock 0 | Produit stock=0 | ✅ Bouton désactivé | ☐ PASS ☐ FAIL | |

**Résumé catalogue:** ___/6 réussis | Bugs: ___

---

## 🔍 DOMAINE : RECHERCHE & FILTRAGE (5 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-SEARCH-001** Recherche mot-clé | "laptop" | ✅ Résultats pertinents | ☐ PASS ☐ FAIL | |
| 2 | **TC-SEARCH-002** Pas de résultat | "xyz123xyz" | ✅ "Aucun produit" | ☐ PASS ☐ FAIL | |
| 3 | **TC-SEARCH-003** Filtre catégorie | Filter "Phones" | ✅ Phones seulement | ☐ PASS ☐ FAIL | |
| 4 | **TC-SEARCH-004** Filtre prix | 100-500 DZD | ✅ Produits filtrés | ☐ PASS ☐ FAIL | |
| 5 | **TC-SEARCH-005** Filtre note | Min 4 étoiles | ✅ Produits >= 4 stars | ☐ PASS ☐ FAIL | |

**Résumé recherche:** ___/5 réussis | Bugs: ___

---

## 🛒 DOMAINE : PANIER (8 tests)

**Testeur:** ___________  
**Date:** ___________  

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-CART-001** Ajouter produit | Ajouter quantité=1 | ✅ +1 compteur, notification | ☐ PASS ☐ FAIL | Produit: ___ |
| 2 | **TC-CART-002** Quantité > 1 | Ajouter quantité=3 | ✅ 3 unités dans panier | ☐ PASS ☐ FAIL | |
| 3 | **TC-CART-003** Modifier quantité | Panier: 2→5 | ✅ Total recalculé | ☐ PASS ☐ FAIL | Ancien total: ___, Nouveau: ___ |
| 4 | **TC-CART-004** Supprimer produit | Cliquer "Supprimer" | ✅ Produit enlevé, total à jour | ☐ PASS ☐ FAIL | |
| 5 | **TC-CART-005** Vider panier | "Vider le panier" | ✅ Panier vide | ☐ PASS ☐ FAIL | |
| 6 | **TC-CART-006** Persistance | Logout/Login | ✅ Panier conservé | ☐ PASS ☐ FAIL | |
| 7 | **TC-CART-007** Quantité > stock | Stock=5, ajouter 10 | ✅ Ajusté à 5 ou erreur | ☐ PASS ☐ FAIL | |
| 8 | **TC-CART-008** Code promo | Entrer code valide | ✅ Réduction appliquée | ☐ PASS ☐ FAIL | Code: ___, Réduction: ___ |

**Résumé panier:** ___/8 réussis | Bugs: ___

---

## 📋 DOMAINE : COMMANDES (7 tests)

**Testeur:** ___________  
**Date:** ___________  

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-ORDER-001** Créer commande | Panier → Checkout → Valider | ✅ Numéro commande généré | ☐ PASS ☐ FAIL | Numéro: ___ |
| 2 | **TC-ORDER-002** Historique | Profil → Mes commandes | ✅ Liste toutes commandes | ☐ PASS ☐ FAIL | Nb commandes: ___ |
| 3 | **TC-ORDER-003** Détails | Cliquer commande | ✅ Articles, adresse, total | ☐ PASS ☐ FAIL | |
| 4 | **TC-ORDER-004** Statut progression | Créer → (Admin) change → Vérifier | ✅ Statut mis à jour | ☐ PASS ☐ FAIL | Ancien: ___, Nouveau: ___ |
| 5 | **TC-ORDER-005** Email confirmation | Créer → Vérifier email | ✅ Email reçu | ☐ PASS ☐ FAIL | |
| 6 | **TC-ORDER-006** Facture PDF | Cliquer "Télécharger" | ✅ PDF généré | ☐ PASS ☐ FAIL | |
| 7 | **TC-ORDER-007** Annulation | Cliquer "Annuler" | ✅ Statut "Annulée", stock restauré | ☐ PASS ☐ FAIL | |

**Résumé commandes:** ___/7 réussis | Bugs: ___

---

## 💳 DOMAINE : PAIEMENTS (5 tests)

**Testeur:** ___________  
**Date:** ___________  

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-PAY-001** Affichage page | Checkout | ✅ Formulaire visible | ☐ PASS ☐ FAIL | |
| 2 | **TC-PAY-002** Paiement valide | Carte test 4242 | ✅ Paiement accepté | ☐ PASS ☐ FAIL | Temps: ___ms |
| 3 | **TC-PAY-003** Carte invalide | Carte 1111 | ❌ Rejeté | ☐ PASS ☐ FAIL | |
| 4 | **TC-PAY-004** Réponse rapide | Payer, mesurer temps | ✅ < 5s | ☐ PASS ☐ FAIL | Temps réel: ___ms |
| 5 | **TC-PAY-005** Double paiement | Double-clic "Payer" | ✅ Une seule transaction | ☐ PASS ☐ FAIL | |

**Résumé paiements:** ___/5 réussis | Bugs: ___

---

## ❤️ DOMAINE : FAVORIS (5 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-FAV-001** Ajouter favori | Cœur blanc → rouge | ✅ Cœur rouge | ☐ PASS ☐ FAIL | |
| 2 | **TC-FAV-002** Retirer favori | Cœur rouge → blanc | ✅ Cœur blanc | ☐ PASS ☐ FAIL | |
| 3 | **TC-FAV-003** Liste favoris | "Mes favoris" | ✅ Tous favoris affichés | ☐ PASS ☐ FAIL | Nb: ___ |
| 4 | **TC-FAV-004** Ajouter au panier | "Ajouter panier" depuis favoris | ✅ Dans panier | ☐ PASS ☐ FAIL | |
| 5 | **TC-FAV-005** Non connecté | Non connecté, cliquer cœur | ❌ Redirection login | ☐ PASS ☐ FAIL | |

**Résumé favoris:** ___/5 réussis | Bugs: ___

---

## ⭐ DOMAINE : AVIS PRODUITS (6 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-REVIEW-001** Ajouter avis | Notes + texte → Soumettre | ✅ Avis affiché | ☐ PASS ☐ FAIL | |
| 2 | **TC-REVIEW-002** Affichage avis | Section avis | ✅ Avis listés | ☐ PASS ☐ FAIL | Nb avis: ___ |
| 3 | **TC-REVIEW-003** Tri avis | "Plus récents" | ✅ Ordre chronologique | ☐ PASS ☐ FAIL | |
| 4 | **TC-REVIEW-004** Filtrer par note | 5 étoiles | ✅ Seulement 5 stars | ☐ PASS ☐ FAIL | |
| 5 | **TC-REVIEW-005** Vote utilité | 👍 Utile | ✅ Compteur +1 | ☐ PASS ☐ FAIL | |
| 6 | **TC-REVIEW-006** Moyenne mise à jour | Ajouter 5 stars | ✅ Moyenne recalculée | ☐ PASS ☐ FAIL | Ancienne: ___, Nouvelle: ___ |

**Résumé avis:** ___/6 réussis | Bugs: ___

---

## 👤 DOMAINE : PROFIL UTILISATEUR (6 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-PROFILE-001** Affichage profil | "Mon profil" | ✅ Infos affichées | ☐ PASS ☐ FAIL | |
| 2 | **TC-PROFILE-002** Modifier info | Changer nom → Enregistrer | ✅ Changement sauvegardé | ☐ PASS ☐ FAIL | |
| 3 | **TC-PROFILE-003** Upload avatar | Sélectionner image | ✅ Avatar changé | ☐ PASS ☐ FAIL | |
| 4 | **TC-PROFILE-004** Changer password | Ancien → Nouveau | ✅ Password changé | ☐ PASS ☐ FAIL | |
| 5 | **TC-PROFILE-005** Historique | "Mes commandes" | ✅ Toutes commandes | ☐ PASS ☐ FAIL | |
| 6 | **TC-PROFILE-006** Adresses multiples | Ajouter/Supprimer | ✅ Adresses gérées | ☐ PASS ☐ FAIL | |

**Résumé profil:** ___/6 réussis | Bugs: ___

---

## 🤖 DOMAINE : RECOMMANDATIONS IA (5 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-REC-001** Produits similaires (BFS) | Fiche produit | ✅ Produits similaires | ☐ PASS ☐ FAIL | Nb: ___ |
| 2 | **TC-REC-002** Produits complémentaires (DFS) | Fiche produit | ✅ Accessoires affichés | ☐ PASS ☐ FAIL | |
| 3 | **TC-REC-003** Budget Optimizer (GA) | Budget + Profil | ✅ Meilleure config | ☐ PASS ☐ FAIL | Temps: ___ms |
| 4 | **TC-REC-004** Panier recommandations | Panier → "Vous aimerez aussi" | ✅ Suggestions pertinentes | ☐ PASS ☐ FAIL | |
| 5 | **TC-REC-005** Recommendations non connecté | Non connecté, consulter | ✅ Basées session | ☐ PASS ☐ FAIL | |

**Résumé recommandations:** ___/5 réussis | Bugs: ___

---

## 🖥️ DOMAINE : PC BUILDER (10 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-PCB-001** Accès | "PC Builder" | ✅ 7 catégories affichées | ☐ PASS ☐ FAIL | |
| 2 | **TC-PCB-002** Sélection CPU | Choisir CPU | ✅ Spec affichées | ☐ PASS ☐ FAIL | CPU: ___ |
| 3 | **TC-PCB-003** CSP Motherboard | CPU Ryzen → MB AM5 | ✅ Intel masquées | ☐ PASS ☐ FAIL | |
| 4 | **TC-PCB-004** CSP RAM DDR | MB DDR5 → RAM | ✅ DDR4 masquée | ☐ PASS ☐ FAIL | |
| 5 | **TC-PCB-005** CSP Case | MB ATX → Case | ✅ mATX/ITX masquées | ☐ PASS ☐ FAIL | |
| 6 | **TC-PCB-006** CSP PSU Wattage | CPU+GPU → PSU | ✅ PSU insuffisantes grisées | ☐ PASS ☐ FAIL | TDP: ___W |
| 7 | **TC-PCB-007** GA Recommandation | Budget + Profil | ✅ Suggestions GPU/RAM/PSU | ☐ PASS ☐ FAIL | Temps: ___ms |
| 8 | **TC-PCB-008** Calcul TDP | Composants sélectionnés | ✅ TDP Total affichée | ☐ PASS ☐ FAIL | TDP: ___W |
| 9 | **TC-PCB-009** Ajouter au panier | "Ajouter au panier" | ✅ Tous composants dans panier | ☐ PASS ☐ FAIL | Nb: ___ |
| 10 | **TC-PCB-010** Sauvegarder config | "Sauvegarder" | ✅ Config sauvegardée | ☐ PASS ☐ FAIL | |

**Résumé PC Builder:** ___/10 réussis | Bugs: ___

---

## 🎡 DOMAINE : SPIN WHEEL (5 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-SPIN-001** Accès | "Spin Wheel" | ✅ Roue affichée | ☐ PASS ☐ FAIL | |
| 2 | **TC-SPIN-002** Animation | "Tourner!" | ✅ Roue tourne et s'arrête | ☐ PASS ☐ FAIL | Durée: ___ms |
| 3 | **TC-SPIN-003** Récompense | Après spin | ✅ Récompense affichée + sauvegardée | ☐ PASS ☐ FAIL | Récompense: ___ |
| 4 | **TC-SPIN-004** Limite spins | 3+ spins | ❌ "Limite atteinte" | ☐ PASS ☐ FAIL | |
| 5 | **TC-SPIN-005** Historique | "Historique" | ✅ Tous spins listés | ☐ PASS ☐ FAIL | Nb: ___ |

**Résumé Spin Wheel:** ___/5 réussis | Bugs: ___

---

## 💬 DOMAINE : CHATBOT IA (7 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-CHAT-001** Accès | Cliquer chat icon | ✅ Chat ouvert | ☐ PASS ☐ FAIL | |
| 2 | **TC-CHAT-002** Envoyer message | "Bonjour" → Envoyer | ✅ Réponse Gemini reçue | ☐ PASS ☐ FAIL | Temps: ___ms |
| 3 | **TC-CHAT-003** Question produit | "Quel laptop?" | ✅ Suggestion avec détails | ☐ PASS ☐ FAIL | |
| 4 | **TC-CHAT-004** Contexte conversation | Question 1 → Question 2 | ✅ Contexte compris | ☐ PASS ☐ FAIL | |
| 5 | **TC-CHAT-005** Temps réponse | Mesurer latence | ✅ < 3s | ☐ PASS ☐ FAIL | Temps réel: ___ms |
| 6 | **TC-CHAT-006** Non-connecté | Chat sans login | ✅ Chatbot fonctionne | ☐ PASS ☐ FAIL | |
| 7 | **TC-CHAT-007** Fermer | "Fermer" | ✅ Chat ferme | ☐ PASS ☐ FAIL | |

**Résumé Chatbot:** ___/7 réussis | Bugs: ___

---

## 🔒 DOMAINE : SÉCURITÉ (10 tests CRITIQUE)

**Testeur SÉCURITÉ:** ___________  
**Date:** ___________  

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-SEC-001** SQL Injection | Login: `' OR '1'='1` | ❌ Connexion refusée | ☐ PASS ☐ FAIL | |
| 2 | **TC-SEC-002** XSS Avis | Avis: `<script>alert()</script>` | ✅ Script échappé | ☐ PASS ☐ FAIL | |
| 3 | **TC-SEC-003** CSRF Token | Vérifier token sur forms | ✅ Token présent | ☐ PASS ☐ FAIL | |
| 4 | **TC-SEC-004** Force Brute | 10 mauvais logins | ✅ Compte bloqué/CAPTCHA | ☐ PASS ☐ FAIL | |
| 5 | **TC-SEC-005** HTTPS | HTTP redirect | ✅ Redirect HTTPS | ☐ PASS ☐ FAIL | |
| 6 | **TC-SEC-006** Auth page | Non-connecté → /dashboard | ❌ Redirect login | ☐ PASS ☐ FAIL | |
| 7 | **TC-SEC-007** Password hash | Vérifier BD | ✅ Passwords hachés (bcrypt) | ☐ PASS ☐ FAIL | |
| 8 | **TC-SEC-008** Injection fichier | Upload .php | ❌ Rejeté | ☐ PASS ☐ FAIL | |
| 9 | **TC-SEC-009** CORS | API depuis autre domaine | ✅ CORS correctement config | ☐ PASS ☐ FAIL | |
| 10 | **TC-SEC-010** Rate Limiting | 100 requêtes/s | ✅ Throttling appliqué | ☐ PASS ☐ FAIL | |

🔴 **SÉCURITÉ CRITIQUE:** ___/10 réussis | Bugs SÉVÉRITÉ HAUTE: ___

---

## ⚡ DOMAINE : PERFORMANCE (8 tests)

**Testeur Performance:** ___________  
**Date:** ___________  

| # | Test Case | Objectif | Mesure | Résultat attendu | Status | Valeur réelle |
|---|---|---|---|---|---|---|
| 1 | **TC-PERF-001** Load time | Accueil | FCP, Load | < 2s FCP, < 3s Load | ☐ PASS ☐ FAIL | FCP: ___ms, Load: ___ms |
| 2 | **TC-PERF-002** API latency | GET /products | Temps réponse | < 500ms | ☐ PASS ☐ FAIL | ___ms |
| 3 | **TC-PERF-003** Charge 50 users | JMeter 50 threads | Stabilité 5min | Pas d'erreurs | ☐ PASS ☐ FAIL | Erreurs: ___ |
| 4 | **TC-PERF-004** Stress 1000 users | Trouvez point rupture | Point rupture | ≥ 1000 users stable | ☐ PASS ☐ FAIL | Rupture à: ___ users |
| 5 | **TC-PERF-005** BD 100k produits | Requête SELECT | Temps requête | < 500ms | ☐ PASS ☐ FAIL | ___ms |
| 6 | **TC-PERF-006** Cache efficacité | 1ère vs 2ème requête | Ratio | 2ème ≈ 50% plus rapide | ☐ PASS ☐ FAIL | 1ère: ___ms, 2ème: ___ms |
| 7 | **TC-PERF-007** Fuite mémoire | Test 30min | Stabilité RAM | RAM stable | ☐ PASS ☐ FAIL | RAM début: ___MB, fin: ___MB |
| 8 | **TC-PERF-008** Images | Taille et format | Optimisation | < 100KB, WebP | ☐ PASS ☐ FAIL | Taille max: ___KB |

📊 **Performance:** ___/8 réussis | Bottlenecks détectés: ___

---

## 🔗 DOMAINE : INTÉGRATION (5 tests)

| # | Test Case | Étapes | Résultat attendu | Status | Notes |
|---|---|---|---|---|---|
| 1 | **TC-INT-001** API Gemini | Chatbot → Gemini | ✅ Réponse reçue | ☐ PASS ☐ FAIL | Latence: ___ms |
| 2 | **TC-INT-002** Gateway paiement | Paiement test | ✅ Callback reçu | ☐ PASS ☐ FAIL | |
| 3 | **TC-INT-003** Flask AI | PC Builder CSP | ✅ Résultats en < 2s | ☐ PASS ☐ FAIL | Temps: ___ms |
| 4 | **TC-INT-004** Base de données | Test 30min | ✅ Connexions stables | ☐ PASS ☐ FAIL | |
| 5 | **TC-INT-005** Mailer | Commande → Email | ✅ Email reçu | ☐ PASS ☐ FAIL | Délai: ___s |

**Résumé intégration:** ___/5 réussis | Bugs: ___

---

## 📊 RÉSUMÉ GLOBAL

| Domaine | Résussis | Total | % | Status |
|---------|----------|-------|---|--------|
| **Authentification** | ___ | 8 | __% | ☐ OK ☐ NOK |
| **Catalogue** | ___ | 6 | __% | ☐ OK ☐ NOK |
| **Recherche** | ___ | 5 | __% | ☐ OK ☐ NOK |
| **Panier** | ___ | 8 | __% | ☐ OK ☐ NOK |
| **Commandes** | ___ | 7 | __% | ☐ OK ☐ NOK |
| **Paiements** | ___ | 5 | __% | ☐ OK ☐ NOK |
| **Favoris** | ___ | 5 | __% | ☐ OK ☐ NOK |
| **Avis** | ___ | 6 | __% | ☐ OK ☐ NOK |
| **Profil** | ___ | 6 | __% | ☐ OK ☐ NOK |
| **Recommandations IA** | ___ | 5 | __% | ☐ OK ☐ NOK |
| **PC Builder** | ___ | 10 | __% | ☐ OK ☐ NOK |
| **Spin Wheel** | ___ | 5 | __% | ☐ OK ☐ NOK |
| **Chatbot** | ___ | 7 | __% | ☐ OK ☐ NOK |
| **🔒 SÉCURITÉ** | ___ | 10 | __% | ☐ OK ☐ NOK |
| **⚡ PERFORMANCE** | ___ | 8 | __% | ☐ OK ☐ NOK |
| **Intégration** | ___ | 5 | __% | ☐ OK ☐ NOK |
| **RÉGRESSION (Smoke)** | ___ | 20 | __% | ☐ OK ☐ NOK |
| | | | | |
| **TOTAL** | **___** | **121** | **__%** | **☐ APPROUVÉ** |

---

## 🐛 BUGS TROUVÉS

| ID | Titre | Sévérité | Module | Étapes repro | Status | Assigné |
|----|----|---|---|---|---|---|
| BUG-001 | | 🔴 | | | ☐ New ☐ Assigned ☐ Fixed ☐ Verified | |
| BUG-002 | | 🟠 | | | ☐ New ☐ Assigned ☐ Fixed ☐ Verified | |
| BUG-003 | | 🟡 | | | ☐ New ☐ Assigned ☐ Fixed ☐ Verified | |
| | | | | | | |

**Total bugs:** ___ | Critiques: ___ | Majeurs: ___ | Mineurs: ___

---

## ⚠️ BLOQUANTS IDENTIFIÉS

- [ ] ___________________________________________
- [ ] ___________________________________________
- [ ] ___________________________________________

---

## 📝 NOTES GÉNÉRALES

```
[Espace pour notes supplémentaires, observations, recommandations]




```

---

## ✅ SIGN OFF

| Rôle | Nom | Date | Signature |
|-----|------|------|-----------|
| **QA Lead** | __________ | __________ | __________ |
| **Tech Lead** | __________ | __________ | __________ |
| **Product Manager** | __________ | __________ | __________ |

---

## 📎 ANNEXES

### Fichiers de log générés
- [ ] JMeter results: `test_results_${DATE}.jtl`
- [ ] Lighthouse report: `lighthouse_${DATE}.html`
- [ ] Screenshots errors: `/outputs/screenshots/`
- [ ] Database backup: `/outputs/backups/`

### Ressources
- Plan complet: `PLAN_TESTS_FONCTIONNELS.md`
- Données test SQL: `test_fixtures.sql`
- Credentials (fichier séparé): `.env.test`
- Postman collection: `AlphaStore.postman_collection.json`
- JMeter plan: `AlphaStore_load_test.jmx`

---

**Dernière mise à jour:** Mai 2026  
**Modèle version:** 1.0
