# 📋 PLAN COMPLET DE TESTS FONCTIONNELS — Alpha Store

**Document:** Plan Complet de Tests Fonctionnels  
**Projet:** Alpha Store (E-commerce Multi-secteurs IA)  
**Version:** 1.0  
**Date:** Mai 2026  
**Statut:** À Exécuter

---

## 📑 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Stratégie de test](#stratégie-de-test)
3. [Tests d'authentification](#tests-dauthentification)
4. [Tests du catalogue & produits](#tests-du-catalogue--produits)
5. [Tests des recommandations IA](#tests-des-recommandations-ia)
6. [Tests du panier](#tests-du-panier)
7. [Tests des commandes](#tests-des-commandes)
8. [Tests des favoris](#tests-des-favoris)
9. [Tests des avis produits](#tests-des-avis-produits)
10. [Tests du chatbot IA](#tests-du-chatbot-ia)
11. [Tests du PC Builder](#tests-du-pc-builder)
12. [Tests du Spin Wheel](#tests-du-spin-wheel)
13. [Tests du profil utilisateur](#tests-du-profil-utilisateur)
14. [Tests de recherche & filtrage](#tests-de-recherche--filtrage)
15. [Tests des paiements](#tests-des-paiements)
16. [Tests de performance](#tests-de-performance)
17. [Tests de sécurité](#tests-de-sécurité)
18. [Matrice de traçabilité](#matrice-de-traçabilité)

---

## Vue d'ensemble

### Domaines à tester

| Domaine | Modules | Priorité |
|---------|---------|----------|
| **Authentification** | Login, Signup, Reset Password, OTP | 🔴 Critique |
| **Catalogue** | Browse Products, Filter, Search | 🔴 Critique |
| **Recommandations** | BFS/DFS, GA, Budget Optimizer | 🟠 Haute |
| **Panier** | Add, Remove, Update Qty, Checkout | 🔴 Critique |
| **Commandes** | Create, Status, History, Invoice | 🔴 Critique |
| **Favoris** | Add, Remove, View List | 🟠 Haute |
| **Avis** | Create, Display, Rating, Filter | 🟠 Haute |
| **Chatbot IA** | Message, Response, Context | 🟠 Haute |
| **PC Builder** | CSP Filtering, GA Optimization | 🟡 Moyenne |
| **Spin Wheel** | Spin, Reward, Animation | 🟡 Moyenne |
| **Profil** | Edit Info, Upload Avatar, History | 🟠 Haute |
| **Sécurité** | XSS, SQL Injection, CSRF, Auth | 🔴 Critique |
| **Performance** | Load Time, Response Time, Stress | 🟠 Haute |

---

## Stratégie de test

### Approche

- ✅ **Tests Unitaires** : Algorithmes IA (CSP, GA, BFS)
- ✅ **Tests d'Intégration** : API PHP/Flask, BD MySQL
- ✅ **Tests Fonctionnels** : Parcours utilisateur complet
- ✅ **Tests E2E** : Scénarios end-to-end avec Selenium (optionnel)
- ✅ **Tests de Performance** : JMeter, Apache Bench
- ✅ **Tests de Sécurité** : Vérification failles courantes

### Outils recommandés

```
┌─────────────────────────────────────────────┐
│ OUTILS DE TEST — Alpha Store                │
├─────────────────────────────────────────────┤
│ • Postman         → Tests API REST           │
│ • JMeter          → Tests de charge          │
│ • PHPUnit         → Tests unitaires PHP      │
│ • Selenium        → Tests E2E navigateur     │
│ • OWASP ZAP       → Tests de sécurité        │
│ • MySQL Workbench → Vérification BD          │
│ • VS Code Debugger→ Debugging JS/PHP         │
│ • Chrome DevTools → Tests front-end          │
└─────────────────────────────────────────────┘
```

### Environnement de test

```
BASE URL    : http://localhost/AlphaStore
BD          : alphastore (MySQL/MariaDB)
USER TEST 1 : test@email.com / Password123
USER TEST 2 : admin@email.com / AdminPass456
ADMIN       : admin / adminpass
```

---

## Tests d'authentification

### 🔷 TC-AUTH-001 : Inscription utilisateur valide

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier qu'un nouvel utilisateur peut s'inscrire |
| **Prérequis** | Utilisateur non existant, email valide |
| **Étapes** | 1. Accéder à `/View/html/signUp.php` 2. Remplir formulaire (nom, email, password) 3. Cliquer "Créer compte" 4. Vérifier réception OTP 5. Confirmer OTP |
| **Résultat attendu** | ✅ Compte créé, redirection vers login ou tableau de bord |
| **Données test** | Email: `newuser_${timestamp}@test.com`, Password: `Test@1234` |
| **Priorité** | 🔴 Critique |

### 🔷 TC-AUTH-002 : Inscription avec email existant

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier rejet d'inscription avec email dupliqué |
| **Étapes** | 1. Tenter inscription avec `test@email.com` existant |
| **Résultat attendu** | ❌ Message d'erreur : "Email déjà utilisé" |
| **Priorité** | 🔴 Critique |

### 🔷 TC-AUTH-003 : Inscription avec mot de passe faible

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier validation force du mot de passe |
| **Étapes** | 1. Tenter inscription avec password: `abc` |
| **Résultat attendu** | ❌ Erreur : "Le mot de passe doit avoir min 8 caractères..." |
| **Priorité** | 🔴 Critique |

### 🔷 TC-AUTH-004 : Login avec identifiants valides

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier authentification réussie |
| **Étapes** | 1. Accéder `/View/html/login.php` 2. Entrer email/password 3. Cliquer "Se connecter" |
| **Résultat attendu** | ✅ Redirection vers `/View/html/index.html` (dashboard ou accueil) |
| **Priorité** | 🔴 Critique |

### 🔷 TC-AUTH-005 : Login avec mot de passe incorrect

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier rejet d'authentification échouée |
| **Étapes** | 1. Entrer email: `test@email.com`, password: `WrongPassword` |
| **Résultat attendu** | ❌ Message : "Email ou mot de passe incorrect" |
| **Priorité** | 🔴 Critique |

### 🔷 TC-AUTH-006 : Oubli de mot de passe

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier réinitialisation de mot de passe |
| **Étapes** | 1. Cliquer "Mot de passe oublié?" 2. Entrer email 3. Cliquer "Réinitialiser" 4. Vérifier email de réinitialisation |
| **Résultat attendu** | ✅ Email reçu, lien valide pour changer le mot de passe |
| **Priorité** | 🔴 Critique |

### 🔷 TC-AUTH-007 : Session persistante après login

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier la session utilisateur persiste |
| **Étapes** | 1. Se connecter 2. Rafraîchir la page 3. Vérifier si toujours connecté |
| **Résultat attendu** | ✅ L'utilisateur reste connecté, les données de session sont conservées |
| **Priorité** | 🔴 Critique |

### 🔷 TC-AUTH-008 : Logout

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier déconnexion de l'utilisateur |
| **Étapes** | 1. Être connecté 2. Cliquer "Déconnexion" 3. Tenter accès zone protégée |
| **Résultat attendu** | ✅ Redirection vers page login, session détruite |
| **Priorité** | 🔴 Critique |

---

## Tests du catalogue & produits

### 🔷 TC-CAT-001 : Affichage page d'accueil

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier chargement complet de la page d'accueil |
| **Étapes** | 1. Accéder `http://localhost/AlphaStore/` |
| **Résultat attendu** | ✅ Page charge < 3s, produits affichés (Fashion + Tech) |
| **Données test** | Aucune (public) |
| **Priorité** | 🔴 Critique |

### 🔷 TC-CAT-002 : Navigation entre catégories

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier navigation entre Tech et Fashion |
| **Étapes** | 1. Depuis accueil, cliquer "Tech" 2. Vérifier affichage produits Tech 3. Cliquer "Fashion" 4. Vérifier affichage produits Fashion |
| **Résultat attendu** | ✅ Chaque catégorie affiche les bons produits |
| **Priorité** | 🔴 Critique |

### 🔷 TC-CAT-003 : Affichage détails produit

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage complet d'une fiche produit |
| **Étapes** | 1. Cliquer sur un produit 2. Vérifier présence : image, description, prix, stock, avis |
| **Résultat attendu** | ✅ Tous les champs sont affichés correctement |
| **Priorité** | 🔴 Critique |

### 🔷 TC-CAT-004 : Pagination des produits

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier fonctionnement pagination |
| **Étapes** | 1. Afficher liste produits 2. Cliquer "Page 2" 3. Vérifier nouveaux produits 4. Cliquer "Précédent" |
| **Résultat attendu** | ✅ Pagination fonctionne, contenu change |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CAT-005 : Tri des produits (Prix croissant/décroissant)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier le tri par prix |
| **Étapes** | 1. Afficher liste produits 2. Sélectionner tri "Prix croissant" 3. Vérifier ordre 4. Sélectionner "Décroissant" |
| **Résultat attendu** | ✅ Produits triés correctement par prix |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CAT-006 : Stock insuffisant

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage stock insuffisant |
| **Étapes** | 1. Trouver produit avec stock = 0 2. Vérifier bouton "Ajouter au panier" désactivé |
| **Résultat attendu** | ✅ Bouton désactivé/grisé, message "En rupture de stock" |
| **Priorité** | 🟠 Haute |

---

## Tests de recherche & filtrage

### 🔷 TC-SEARCH-001 : Recherche par mot-clé

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier recherche produit par nom |
| **Étapes** | 1. Utiliser barre recherche 2. Entrer "laptop" 3. Appuyer Entrée |
| **Résultat attendu** | ✅ Résultats affichés contiennent "laptop" |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEARCH-002 : Recherche avec pas de résultat

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage quand pas de résultat |
| **Étapes** | 1. Rechercher "xyzabc123xyz" |
| **Résultat attendu** | ✅ Message "Aucun produit trouvé" |
| **Priorité** | 🟠 Haute |

### 🔷 TC-SEARCH-003 : Filtrage par catégorie

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier filtrage par catégorie |
| **Étapes** | 1. Appliquer filtre "Catégorie = Phones" 2. Vérifier résultats |
| **Résultat attendu** | ✅ Seulement produits de la catégorie affichés |
| **Priorité** | 🟠 Haute |

### 🔷 TC-SEARCH-004 : Filtrage par plage de prix

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier filtrage par prix min/max |
| **Étapes** | 1. Définir prix min = 100 DZD, max = 500 DZD 2. Appliquer filtre |
| **Résultat attendu** | ✅ Produits affichés entre 100-500 DZD seulement |
| **Priorité** | 🟠 Haute |

### 🔷 TC-SEARCH-005 : Filtrage par note/évaluation

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier filtrage par évaluation minimale |
| **Étapes** | 1. Filtrer "Min 4 étoiles" 2. Vérifier tous produits >= 4 étoiles |
| **Résultat attendu** | ✅ Produits filtrés correctement |
| **Priorité** | 🟡 Moyenne |

---

## Tests du panier

### 🔷 TC-CART-001 : Ajouter produit au panier

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier ajout d'un produit au panier |
| **Prérequis** | Utilisateur connecté |
| **Étapes** | 1. Accéder fiche produit 2. Cliquer "Ajouter au panier" 3. Vérifier notification |
| **Résultat attendu** | ✅ Produit ajouté, compteur panier +1, notification affichée |
| **Priorité** | 🔴 Critique |

### 🔷 TC-CART-002 : Ajouter quantité > 1

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier ajout avec quantité > 1 |
| **Étapes** | 1. Sur fiche produit, augmenter quantité à 3 2. Cliquer "Ajouter au panier" |
| **Résultat attendu** | ✅ 3 unités ajoutées au panier |
| **Priorité** | 🔴 Critique |

### 🔷 TC-CART-003 : Modifier quantité dans le panier

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier modification quantité produit du panier |
| **Étapes** | 1. Accéder panier 2. Modifier quantité d'un produit (ex: 2 → 5) 3. Vérifier prix total recalculé |
| **Résultat attendu** | ✅ Total panier mis à jour |
| **Priorité** | 🔴 Critique |

### 🔷 TC-CART-004 : Supprimer produit du panier

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier suppression d'un produit |
| **Étapes** | 1. Accéder panier 2. Cliquer "Supprimer" sur un article 3. Vérifier affichage |
| **Résultat attendu** | ✅ Produit supprimé, total recalculé |
| **Priorité** | 🔴 Critique |

### 🔷 TC-CART-005 : Vider le panier

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier vider complètement le panier |
| **Étapes** | 1. Accéder panier 2. Cliquer "Vider le panier" |
| **Résultat attendu** | ✅ Panier vide, message "Votre panier est vide" |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CART-006 : Persistance panier (après déconnexion)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier persistance panier après logout/login |
| **Étapes** | 1. Ajouter produit au panier 2. Se déconnecter 3. Se reconnecter 4. Vérifier panier |
| **Résultat attendu** | ✅ Panier conservé après reconnexion |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CART-007 : Quantité > stock disponible

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier limitation quantité au stock disponible |
| **Étapes** | 1. Produit avec stock = 5 2. Tenter ajouter quantité = 10 |
| **Résultat attendu** | ❌ Erreur ou ajustement auto à 5 |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CART-008 : Code promo

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier application code promo/coupon |
| **Étapes** | 1. Accéder panier 2. Entrer code promo valide 3. Vérifier réduction |
| **Résultat attendu** | ✅ Réduction appliquée, total recalculé |
| **Priorité** | 🟡 Moyenne |

---

## Tests des commandes

### 🔷 TC-ORDER-001 : Créer commande valide

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier création d'une commande |
| **Prérequis** | Panier avec ≥1 produit, utilisateur connecté |
| **Étapes** | 1. Accéder panier 2. Cliquer "Procéder au paiement" 3. Vérifier formulaire adresse 4. Valider commande |
| **Résultat attendu** | ✅ Commande créée, numéro de commande généré, confirmation affichée |
| **Priorité** | 🔴 Critique |

### 🔷 TC-ORDER-002 : Historique commandes

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage historique des commandes |
| **Étapes** | 1. Accéder profil utilisateur 2. Cliquer "Mes commandes" |
| **Résultat attendu** | ✅ Liste toutes commandes précédentes avec statut |
| **Priorité** | 🔴 Critique |

### 🔷 TC-ORDER-003 : Détails d'une commande

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage détails d'une commande |
| **Étapes** | 1. Depuis historique, cliquer sur une commande |
| **Résultat attendu** | ✅ Affiche articles, adresse, total, date, statut |
| **Priorité** | 🔴 Critique |

### 🔷 TC-ORDER-004 : Statut commande (progression)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier mise à jour statut commande |
| **Étapes** | 1. Créer commande 2. Vérifier statut "En attente" 3. (Admin) Changer statut → "Expédiée" 4. Vérifier changement côté utilisateur |
| **Résultat attendu** | ✅ Statut mis à jour en temps réel |
| **Priorité** | 🟠 Haute |

### 🔷 TC-ORDER-005 : Confirmation email commande

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier envoi email de confirmation |
| **Étapes** | 1. Créer commande 2. Vérifier réception email |
| **Résultat attendu** | ✅ Email reçu avec détails commande |
| **Priorité** | 🟠 Haute |

### 🔷 TC-ORDER-006 : Télécharger facture

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier téléchargement facture PDF |
| **Étapes** | 1. Depuis détail commande, cliquer "Télécharger facture" |
| **Résultat attendu** | ✅ PDF généré et téléchargé |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-ORDER-007 : Annulation commande

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier annulation commande (si autorisation) |
| **Étapes** | 1. Créer commande 2. Cliquer "Annuler cette commande" 3. Vérifier statut changé en "Annulée" |
| **Résultat attendu** | ✅ Commande annulée, stock restauré |
| **Priorité** | 🟡 Moyenne |

---

## Tests des paiements

### 🔷 TC-PAY-001 : Affichage page paiement

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage formulaire paiement |
| **Étapes** | 1. Depuis checkout, afficher options paiement |
| **Résultat attendu** | ✅ Affiche options (carte, virement, etc.) |
| **Priorité** | 🔴 Critique |

### 🔷 TC-PAY-002 : Paiement par carte valide

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier paiement par carte bancaire |
| **Étapes** | 1. Sélectionner paiement carte 2. Entrer données carte test 3. Cliquer "Payer" |
| **Résultat attendu** | ✅ Paiement traité, commande confirmée |
| **Données test** | Utiliser cartes de test Stripe/PayPal (4242 4242...) |
| **Priorité** | 🔴 Critique |

### 🔷 TC-PAY-003 : Paiement avec carte invalide

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier rejet carte invalide |
| **Étapes** | 1. Entrer numéro carte invalide (ex: 1111) 2. Cliquer "Payer" |
| **Résultat attendu** | ❌ Erreur affichée, paiement rejeté |
| **Priorité** | 🔴 Critique |

### 🔷 TC-PAY-004 : Confirmation paiement instantanée

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier réponse immédiate du paiement |
| **Étapes** | 1. Initier paiement 2. Vérifier réponse < 5s |
| **Résultat attendu** | ✅ Réponse rapide (succès ou erreur) |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PAY-005 : Double paiement prevention

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier prévention double débit |
| **Étapes** | 1. Effectuer paiement 2. Double-cliquer "Payer" |
| **Résultat attendu** | ✅ Une seule transaction, bouton désactivé |
| **Priorité** | 🟠 Haute |

---

## Tests des favoris

### 🔷 TC-FAV-001 : Ajouter produit aux favoris

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier ajout aux favoris |
| **Prérequis** | Utilisateur connecté |
| **Étapes** | 1. Accéder fiche produit 2. Cliquer icône "♥ Ajouter aux favoris" |
| **Résultat attendu** | ✅ Cœur devient rouge/plein, notification "Ajouté aux favoris" |
| **Priorité** | 🟠 Haute |

### 🔷 TC-FAV-002 : Retirer des favoris

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier suppression d'un favori |
| **Étapes** | 1. Depuis un favori, cliquer cœur rouge 2. Vérifier suppression |
| **Résultat attendu** | ✅ Cœur devient blanc/vide, notification affichée |
| **Priorité** | 🟠 Haute |

### 🔷 TC-FAV-003 : Afficher liste favoris

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage page "Mes favoris" |
| **Étapes** | 1. Accéder profil → "Mes favoris" 2. Vérifier liste complète |
| **Résultat attendu** | ✅ Affiche tous produits ajoutés aux favoris |
| **Priorité** | 🟠 Haute |

### 🔷 TC-FAV-004 : Ajouter favori au panier

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier ajout favori au panier directement |
| **Étapes** | 1. Depuis liste favoris, cliquer "Ajouter au panier" |
| **Résultat attendu** | ✅ Produit ajouté au panier |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-FAV-005 : Favoris non visibles pour utilisateurs non connectés

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier que favoris ne fonctionnent que connecté |
| **Étapes** | 1. Se déconnecter 2. Essayer cliquer cœur |
| **Résultat attendu** | ❌ Invite à se connecter, redirection login |
| **Priorité** | 🟡 Moyenne |

---

## Tests des avis produits

### 🔷 TC-REVIEW-001 : Ajouter un avis

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier création d'un avis produit |
| **Prérequis** | Utilisateur connecté, a acheté le produit (optionnel) |
| **Étapes** | 1. Accéder fiche produit 2. Cliquer "Écrire un avis" 3. Entrer note (1-5 étoiles) et commentaire 4. Cliquer "Soumettre" |
| **Résultat attendu** | ✅ Avis affiché (modéré selon config) |
| **Priorité** | 🟠 Haute |

### 🔷 TC-REVIEW-002 : Affichage avis produit

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage liste avis |
| **Étapes** | 1. Accéder fiche produit 2. Scroller section avis 3. Vérifier affichage |
| **Résultat attendu** | ✅ Avis affichés avec note, pseudo, date, texte |
| **Priorité** | 🟠 Haute |

### 🔷 TC-REVIEW-003 : Tri avis (récents/utiles)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier tri des avis |
| **Étapes** | 1. Appliquer tri "Les plus récents" 2. Vérifier ordre |
| **Résultat attendu** | ✅ Avis triés correctement |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-REVIEW-004 : Filtrer avis par note

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier filtrage avis par étoiles |
| **Étapes** | 1. Filtrer "5 étoiles seulement" 2. Vérifier résultats |
| **Résultat attendu** | ✅ Seulement avis 5 étoiles affichés |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-REVIEW-005 : Vote utilité avis (utile/pas utile)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier vote utilité avis |
| **Étapes** | 1. Cliquer "👍 Utile" sur un avis |
| **Résultat attendu** | ✅ Compteur augmente |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-REVIEW-006 : Notation moyenne mise à jour

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier mise à jour moyenne après nouvel avis |
| **Étapes** | 1. Note initiale produit: 4.0 2. Ajouter avis 5 étoiles 3. Vérifier nouvelle moyenne |
| **Résultat attendu** | ✅ Moyenne recalculée correctement |
| **Priorité** | 🟠 Haute |

---

## Tests du profil utilisateur

### 🔷 TC-PROFILE-001 : Affichage profil

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage page profil |
| **Prérequis** | Utilisateur connecté |
| **Étapes** | 1. Accéder "Mon profil" 2. Vérifier affichage infos |
| **Résultat attendu** | ✅ Affiche nom, email, adresse, téléphone, avatar |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PROFILE-002 : Modifier informations profil

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier modification infos utilisateur |
| **Étapes** | 1. Cliquer "Modifier" 2. Changer nom/adresse 3. Cliquer "Enregistrer" |
| **Résultat attendu** | ✅ Modifications sauvegardées, message de succès |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PROFILE-003 : Télécharger avatar

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier upload avatar utilisateur |
| **Étapes** | 1. Cliquer sur avatar 2. Sélectionner image 3. Vérifier affichage |
| **Résultat attendu** | ✅ Avatar changé, image affichée partout |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-PROFILE-004 : Changer mot de passe

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier changement mot de passe |
| **Étapes** | 1. Cliquer "Changer mot de passe" 2. Entrer ancien/nouveau 3. Valider |
| **Résultat attendu** | ✅ Mot de passe changé, nouvelle session demandée |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PROFILE-005 : Historique commandes depuis profil

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier affichage historique depuis profil |
| **Étapes** | 1. Cliquer "Mes commandes" depuis profil |
| **Résultat attendu** | ✅ Liste toutes commandes |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PROFILE-006 : Adresses multiples

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier gestion adresses multiples |
| **Étapes** | 1. Ajouter nouvelle adresse 2. Vérifier affichage 3. Supprimer adresse |
| **Résultat attendu** | ✅ Adresses gérées correctement |
| **Priorité** | 🟡 Moyenne |

---

## Tests des recommandations IA

### 🔷 TC-REC-001 : Recommandations BFS (produits similaires)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier algorithme BFS pour produits similaires |
| **Étapes** | 1. Accéder fiche produit "Laptop Dell" 2. Vérifier section "Produits similaires" |
| **Résultat attendu** | ✅ Affiche laptops de même marque/catégorie |
| **Priorité** | 🟠 Haute |

### 🔷 TC-REC-002 : Recommandations DFS (produits complémentaires)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier algorithme DFS pour produits complémentaires |
| **Étapes** | 1. Afficher produit "Laptop" 2. Vérifier section "Produits complémentaires" |
| **Résultat attendu** | ✅ Affiche accessoires (souris, sacoche, etc.) |
| **Priorité** | 🟠 Haute |

### 🔷 TC-REC-003 : Budget Optimizer (Genetic Algorithm)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier optimiseur budgétaire GA |
| **Étapes** | 1. Cliquer "Budget Optimizer" 2. Entrer budget max (ex: 10000 DZD) 3. Sélectionner profil (Gaming/Work) 4. Cliquer "Optimiser" |
| **Résultat attendu** | ✅ Affiche meilleure combinaison produits < budget |
| **Priorité** | 🟠 Haute |

### 🔷 TC-REC-004 : Panier recommandations

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier recommandations dans panier |
| **Étapes** | 1. Accéder panier 2. Vérifier section "Vous aimerez aussi" |
| **Résultat attendu** | ✅ Produits pertinents suggérés |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-REC-005 : Recommandations pour utilisateur non connecté

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier recommandations basées historique de session |
| **Étapes** | 1. Non connecté, consulter produits 2. Vérifier recommandations personnalisées |
| **Résultat attendu** | ✅ Recommandations basées sur navigation actuelle |
| **Priorité** | 🟡 Moyenne |

---

## Tests du PC Builder

### 🔷 TC-PCB-001 : Accès PC Builder

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier accès page PC Builder |
| **Étapes** | 1. Accéder "PC Builder" depuis menu 2. Vérifier chargement |
| **Résultat attendu** | ✅ Page charge, 7 catégories affichées (CPU, Motherboard, RAM, GPU, PSU, Storage, Case) |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-002 : Sélection CPU

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier sélection CPU |
| **Étapes** | 1. Sélectionner CPU "AMD Ryzen 7 7800X3D" |
| **Résultat attendu** | ✅ CPU sélectionné, affichage spec (cores, TDP, prix) |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-003 : CSP Filtering (Motherboard compatible)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier filtrage CSP motherboards compatibles |
| **Étapes** | 1. Sélectionner CPU Ryzen 2. Vérifier liste motherboards : seulement AM5 affichées |
| **Résultat attendu** | ✅ Motherboards Intel masquées, AM5 seulement |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-004 : CSP Filtering (RAM DDR version)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier filtrage RAM selon motherboard DDR type |
| **Étapes** | 1. Sélectionner CPU (AM5) 2. Sélectionner MB (DDR5) 3. Vérifier RAM : DDR5 seulement |
| **Résultat attendu** | ✅ DDR4 masquée, DDR5 affichée |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-005 : CSP Filtering (Case form factor)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier filtrage boîtier selon motherboard |
| **Étapes** | 1. Sélectionner MB ATX 2. Vérifier boîtiers : ATX seulement |
| **Résultat attendu** | ✅ Boîtiers mATX/ITX masqués |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-006 : CSP Filtering (PSU Wattage)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier PSU suffisante pour composants |
| **Étapes** | 1. Sélectionner CPU (130W) + GPU (250W) 2. Vérifier PSU : min 500W affichées |
| **Résultat attendu** | ✅ PSU insuffisantes grisées |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-007 : Algorithme Génétique (Recommandation)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier GA pour recommandation composants |
| **Étapes** | 1. Sélectionner CPU + MB 2. Cliquer "🧠 Recommandation" 3. Entrer budget (5000 DZD) + profil (Gaming) 4. Voir suggestions GPU/RAM/PSU |
| **Résultat attendu** | ✅ GA suggère meilleur GPU/RAM/PSU pour Gaming < budget |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-008 : Calcul TDP total

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier calcul TDP total des composants |
| **Étapes** | 1. Sélectionner composants 2. Vérifier affichage "TDP Total: XXXw" |
| **Résultat attendu** | ✅ Calcul correct, alerte si PSU insuffisante |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-PCB-009 : Ajouter config PC au panier

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier ajout config complète au panier |
| **Étapes** | 1. Compléter configuration 2. Cliquer "Ajouter au panier" |
| **Résultat attendu** | ✅ Tous composants ajoutés, total correct |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PCB-010 : Sauvegarder configuration

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier sauvegarde config pour utilisation future |
| **Étapes** | 1. Compléter config 2. Cliquer "Sauvegarder cette config" 3. Donner nom |
| **Résultat attendu** | ✅ Config sauvegardée, accessible ultérieurement |
| **Priorité** | 🟡 Moyenne |

---

## Tests du Spin Wheel

### 🔷 TC-SPIN-001 : Accès Spin Wheel

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier accès à la roue des promotions |
| **Prérequis** | Utilisateur connecté |
| **Étapes** | 1. Accéder "Spin Wheel" depuis menu 2. Vérifier chargement roue |
| **Résultat attendu** | ✅ Roue affichée avec sections récompenses |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-SPIN-002 : Tourner la roue

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier animation spin |
| **Étapes** | 1. Cliquer "Tourner!" 2. Observer animation |
| **Résultat attendu** | ✅ Roue tourne, s'arrête sur une section |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-SPIN-003 : Récupération récompense

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier récompense après spin |
| **Étapes** | 1. Tourner roue 2. Vérifier affichage récompense (réduction, points, etc.) |
| **Résultat attendu** | ✅ Récompense affichée et sauvegardée sur compte |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-SPIN-004 : Limite spins par jour

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier limite spins quotidiens |
| **Étapes** | 1. Tourner roue 3 fois (limite = 3) 2. Tenter 4ème spin |
| **Résultat attendu** | ❌ Message "Vous avez atteint votre limite quotidienne" |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-SPIN-005 : Historique spins

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier historique des spins |
| **Étapes** | 1. Cliquer "Historique" 2. Vérifier liste spins précédents |
| **Résultat attendu** | ✅ Affiche date, heure, récompense pour chaque spin |
| **Priorité** | 🟡 Moyenne |

---

## Tests du chatbot IA

### 🔷 TC-CHAT-001 : Accès Chatbot

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier accès au chatbot |
| **Étapes** | 1. Cliquer icône chat en bas à droite (ou depuis menu) |
| **Résultat attendu** | ✅ Fenêtre chat ouvre, message de bienvenue affichée |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CHAT-002 : Envoyer message

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier envoi message à chatbot |
| **Étapes** | 1. Entrer "Bonjour" 2. Cliquer Envoyer |
| **Résultat attendu** | ✅ Message envoyé, réponse Gemini affichée |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CHAT-003 : Question sur produit

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier réponses sur produits |
| **Étapes** | 1. Demander "Quel laptop recommandez-vous?" 2. Vérifier réponse |
| **Résultat attendu** | ✅ Chatbot suggère produits avec détails |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CHAT-004 : Contexte historique

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier conservation contexte conversation |
| **Étapes** | 1. Demander "Quel est le meilleur laptop?" 2. Demander "Et le prix?" 3. Vérifier compréhension contexte |
| **Résultat attendu** | ✅ Chatbot comprend "prix du laptop" sans répéter |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CHAT-005 : Temps réponse chatbot

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier latence réponse API Gemini |
| **Étapes** | 1. Envoyer message 2. Mesurer temps jusqu'à réponse |
| **Résultat attendu** | ✅ Réponse < 3 secondes |
| **Priorité** | 🟠 Haute |

### 🔷 TC-CHAT-006 : Chatbot non-connecté

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier chatbot accessible sans login |
| **Étapes** | 1. Non connecté, ouvrir chatbot 2. Envoyer message |
| **Résultat attendu** | ✅ Chatbot répond (données publiques seulement) |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-CHAT-007 : Fermer conversation

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier fermeture chatbot |
| **Étapes** | 1. Cliquer X ou "Fermer" |
| **Résultat attendu** | ✅ Chat ferme, historique sauvegardé |
| **Priorité** | 🟡 Moyenne |

---

## Tests de sécurité

### 🔷 TC-SEC-001 : SQL Injection (Login)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier protection contre SQL injection |
| **Étapes** | 1. Login email: `' OR '1'='1` 2. Entrer password: `anything` |
| **Résultat attendu** | ❌ Erreur de connexion (injection bloquée) |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-002 : XSS (Avis produit)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier protection XSS dans avis |
| **Étapes** | 1. Écrire avis avec: `<script>alert('XSS')</script>` 2. Soumettre |
| **Résultat attendu** | ✅ Script échappé/rejeté, texte affiché sans exécution |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-003 : CSRF (Form Modification)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier token CSRF sur formulaires |
| **Étapes** | 1. Vérifier token CSRF présent sur tous forms POST/PUT |
| **Résultat attendu** | ✅ Tous formulaires contiennent token CSRF |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-004 : Force Brute (Login)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier protection contre force brute |
| **Étapes** | 1. Tenter 10 logins échoués rapides 2. Vérifier blocage |
| **Résultat attendu** | ✅ Compte bloqué après N essais (ex: 5) ou CAPTCHA obligatoire |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-005 : HTTPS obligatoire

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier HTTPS en production |
| **Étapes** | 1. Accéder HTTP (non-prod) 2. Vérifier redirect HTTPS |
| **Résultat attendu** | ✅ Redirection automatique HTTPS |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-006 : Authentification session

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier pages protégées nécessitent login |
| **Étapes** | 1. Non connecté, accéder `/View/user_Dashboard/` |
| **Résultat attendu** | ❌ Redirection login |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-007 : Données sensibles (Password hashing)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier hash des mots de passe en BD |
| **Étapes** | 1. (Admin) Vérifier BD : mots de passe hashés (bcrypt) |
| **Résultat attendu** | ✅ Tous passwords hashés, jamais stockés en clair |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-008 : Injection fichier (Avatar)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier validation upload fichiers |
| **Étapes** | 1. Upload avatar: fichier.php au lieu .jpg 2. Vérifier rejet |
| **Résultat attendu** | ❌ Fichier rejeté, message erreur |
| **Priorité** | 🔴 Critique |

### 🔷 TC-SEC-009 : CORS Protection

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier CORS configuration |
| **Étapes** | 1. Appel API depuis domaine différent 2. Vérifier headers CORS |
| **Résultat attendu** | ✅ CORS configuré correctement (headers Access-Control-*) |
| **Priorité** | 🟠 Haute |

### 🔷 TC-SEC-010 : Rate Limiting (API)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier limite requêtes API |
| **Étapes** | 1. Envoyer 100 requêtes API en 1s 2. Vérifier throttling |
| **Résultat attendu** | ✅ Requêtes limitées après X par période |
| **Priorité** | 🟠 Haute |

---

## Tests de performance

### 🔷 TC-PERF-001 : Temps chargement page d'accueil

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier temps chargement < 3s |
| **Outil** | Google Lighthouse / WebPageTest |
| **Étapes** | 1. Mesurer temps chargement accueil |
| **Résultat attendu** | ✅ First Contentful Paint < 2s, Load < 3s |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PERF-002 : Temps réponse API

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier latence API < 500ms |
| **Outil** | Postman / JMeter |
| **Étapes** | 1. Appel API GET /products 2. Mesurer temps réponse |
| **Résultat attendu** | ✅ Réponse < 500ms |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PERF-003 : Test charge (50 utilisateurs)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier stabilité avec 50 utilisateurs simultanés |
| **Outil** | JMeter |
| **Étapes** | 1. Configurer 50 threads 2. Lancer test pendant 5min 3. Vérifier stabilité |
| **Résultat attendu** | ✅ Pas d'erreurs, temps réponse constant |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PERF-004 : Test stress (1000 utilisateurs)

| Élément | Détail |
|---------|--------|
| **Objectif** | Trouver point rupture application |
| **Outil** | JMeter |
| **Étapes** | 1. Augmenter progressivement threads (100→500→1000) 2. Noter point rupture |
| **Résultat attendu** | ✅ Application stable jusqu'à ~1000 users, dégradation acceptée ensuite |
| **Priorité** | 🟠 Haute |

### 🔷 TC-PERF-005 : Taille base de données

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier performance avec 100k produits |
| **Outil** | MySQL Workbench |
| **Étapes** | 1. Charger 100k produits 2. Mesurer temps requête SELECT |
| **Résultat attendu** | ✅ Requête < 500ms |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-PERF-006 : Cache efficacité

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier cache produits populaires |
| **Étapes** | 1. Accès produit populaire (accueil) 2. 1ère requête mesurée 3. 2ème requête mesurée 4. Vérifier cache |
| **Résultat attendu** | ✅ 2ème requête ~50% plus rapide que 1ère |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-PERF-007 : Mémoire serveur

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier fuite mémoire |
| **Étapes** | 1. Test charge 30min 2. Monitorer RAM serveur 3. Vérifier stabilité |
| **Résultat attendu** | ✅ RAM stable, pas d'augmentation continue |
| **Priorité** | 🟡 Moyenne |

### 🔷 TC-PERF-008 : Images optimisées

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier optimisation images produits |
| **Étapes** | 1. Vérifier taille images 2. Vérifier compression/format (WebP) |
| **Résultat attendu** | ✅ Images < 100KB, format WebP utilisé |
| **Priorité** | 🟡 Moyenne |

---

## Tests d'intégration

### 🔷 TC-INT-001 : Intégration API Gemini Chatbot

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier intégration API Gemini |
| **Prérequis** | Clé API Gemini configurée |
| **Étapes** | 1. Envoyer message chatbot 2. Vérifier appel API 3. Vérifier réponse |
| **Résultat attendu** | ✅ API appelée, réponse reçue |
| **Priorité** | 🟠 Haute |

### 🔷 TC-INT-002 : Intégration Gateway paiement

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier intégration Stripe/PayPal |
| **Étapes** | 1. Effectuer paiement test 2. Vérifier callback réceptionné |
| **Résultat attendu** | ✅ Paiement traité, commande créée |
| **Priorité** | 🔴 Critique |

### 🔷 TC-INT-003 : Intégration Flask AI Services

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier intégration services Python |
| **Étapes** | 1. Utiliser PC Builder (CSP) 2. Vérifier appel Flask |
| **Résultat attendu** | ✅ CSP retourne résultats en < 2s |
| **Priorité** | 🟠 Haute |

### 🔷 TC-INT-004 : Intégration base de données

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier connexion MySQL persistante |
| **Étapes** | 1. Test charge 30min 2. Vérifier pas de déconnexions BD |
| **Résultat attendu** | ✅ BD stable, connexions persistantes |
| **Priorité** | 🟠 Haute |

### 🔷 TC-INT-005 : Intégration Email (Mailer)

| Élément | Détail |
|---------|--------|
| **Objectif** | Vérifier envoi emails (confirmation commande) |
| **Étapes** | 1. Créer commande 2. Vérifier email reçu |
| **Résultat attendu** | ✅ Email reçu avec détails |
| **Priorité** | 🟠 Haute |

---

## Tests de régression

| Test | Domaine | Fréquence |
|------|---------|-----------|
| **TC-AUTH-001 à TC-AUTH-008** | Authentification | À chaque déploiement |
| **TC-CAT-001 à TC-CAT-006** | Catalogue | À chaque déploiement |
| **TC-CART-001 à TC-CART-008** | Panier | À chaque déploiement |
| **TC-ORDER-001 à TC-ORDER-007** | Commandes | À chaque déploiement |
| **TC-PAY-001 à TC-PAY-005** | Paiements | À chaque déploiement |
| **TC-SEC-001 à TC-SEC-010** | Sécurité | Après chaque hotfix sécurité |
| **TC-PERF-001 à TC-PERF-008** | Performance | Hebdomadaire |

---

## Matrice de traçabilité

| ID Test | Fonctionnalité | Priorité | Statut | Responsable | Notes |
|---------|---|---|---|---|---|
| TC-AUTH-001 | Inscription | 🔴 | À faire | QA1 | Préalable: email valide |
| TC-AUTH-004 | Login | 🔴 | À faire | QA1 | Clé pour tous autres tests |
| TC-CAT-001 | Accueil | 🔴 | À faire | QA1 | Dépend TC-AUTH-001 |
| TC-CART-001 | Ajouter panier | 🔴 | À faire | QA1 | Dépend TC-AUTH-004 |
| TC-ORDER-001 | Commande | 🔴 | À faire | QA2 | Dépend TC-CART-001 |
| TC-PAY-001 | Paiement | 🔴 | À faire | QA2 | Dépend TC-ORDER-001 |
| TC-REC-001 | Recommandations | 🟠 | À faire | QA3 | Après critiques |
| TC-PCB-001 | PC Builder | 🟠 | À faire | QA3 | Après recommandations |
| TC-CHAT-001 | Chatbot | 🟠 | À faire | QA3 | API Gemini requis |
| TC-SPIN-001 | Spin Wheel | 🟡 | À faire | QA3 | Nice-to-have |
| TC-SEC-001 | SQL Injection | 🔴 | À faire | Security | Critique sécurité |
| TC-PERF-001 | Load time | 🟠 | À faire | Perf | Mesures requises |

---

## Données de test (fixtures)

```sql
-- Utilisateur test
INSERT INTO users (email, password, nom, prenom, telephone, adresse) VALUES
('test@email.com', bcrypt('Test@1234'), 'Test', 'User', '0123456789', 'Rue Test, Algérie');

-- Produits test
INSERT INTO produits (nom, description, prix, stock, categorie) VALUES
('Laptop Test', 'Laptop pour tests', 1500, 10, 'Tech'),
('T-Shirt Test', 'Tshirt pour tests', 500, 20, 'Fashion');

-- Commandes test
INSERT INTO orders (user_id, status, total, date_creation) VALUES
(1, 'Pendante', 2500, NOW());
```

---

## Checklist de test

### Avant tests
- ☐ Environnement de test configuré
- ☐ BD de test restaurée (données propres)
- ☐ Serveur XAMPP démarré
- ☐ Services Flask démarrés (port 5001)
- ☐ APIs externes configurées (Gemini, Stripe)
- ☐ Outils de test installés (Postman, JMeter)

### Pendant tests
- ☐ Enregistrer résultats dans fichier trace
- ☐ Capturer screenshots d'erreurs
- ☐ Noter temps de réponse
- ☐ Vérifier logs serveur
- ☐ Sauvegarder fichiers de test

### Après tests
- ☐ Générer rapport de test
- ☐ Documenter bugs trouvés
- ☐ Valider correctifs
- ☐ Nettoyer données test
- ☐ Archiver résultats

---

## Rapport de test (template)

```
═══════════════════════════════════════════════════════════════
         RAPPORT DE TESTS FONCTIONNELS — Alpha Store
═══════════════════════════════════════════════════════════════

Date exécution    : [DATE]
Testeur           : [NOM QA]
Version testée    : [VERSION]
Environnement     : [DEV/STAGING]

RÉSUMÉ EXÉCUTION
───────────────────────────────────────────────────────────────
Total tests       : 87
Réussis           : [XX]
Échoués           : [XX]
Bloqués           : [XX]
Taux de réussite  : [XX%]

RÉSULTATS PAR DOMAINE
───────────────────────────────────────────────────────────────
✅ Authentification    : 8/8 réussis
✅ Catalogue           : 6/6 réussis
⚠️  Panier             : 7/8 réussis (1 bug trouvé)
✅ Commandes           : 7/7 réussis
...

BUGS MAJEURS
───────────────────────────────────────────────────────────────
1. [Bug #1] Cart not updated when product stock changes
   Sévérité: HAUTE | Priorité: P2 | Assigné: DEV-X

2. [Bug #2] Chatbot timeout > 5s sometimes
   Sévérité: MOYENNE | Priorité: P3 | Assigné: DEV-Y

RECOMMANDATIONS
───────────────────────────────────────────────────────────────
- Améliorer cache produits
- Optimiser appels API Gemini
- Augmenter pool connexions MySQL

SIGNATURE
─────────────────────────────────────────────────────────────── 
QA Lead: [SIGNATURE] | Date: [DATE]
```

---

## Fichier de trace d'exécution

Pour chaque test exécuté, enregistrer dans un fichier `.csv` :

```csv
Timestamp,Test_ID,Test_Name,Status,Duration_ms,Notes,Browser,OS
2026-05-18 10:15:30,TC-AUTH-001,Inscription valide,PASS,245,"Email confirmé avec OTP",Chrome 125,Windows 11
2026-05-18 10:16:45,TC-AUTH-002,Email doublon,PASS,180,"Message erreur correct",Chrome 125,Windows 11
2026-05-18 10:17:20,TC-CAT-001,Accueil chargement,PASS,2840,"< 3s OK",Chrome 125,Windows 11
```

---

## Recommandations d'outils

### Automatisation E2E (futur)
```
Cypress ou Playwright pour tests E2E
Framework : Mocha + Chai
Exécution CI/CD : GitHub Actions / GitLab CI
```

### Coverage de code
```
PHPUnit pour tests unitaires PHP
Coverage minimum : 80%
Outil : CodeClimate / Codecov
```

### Monitoring production
```
Datadog / New Relic pour monitoring
Alertes sur erreurs > 1%
APM pour analyse performance
```

---

## Conclusion

Ce plan couvre **87 cas de test** répartis sur **17 domaines** fonctionnels, représentant un **coverage complet** du projet Alpha Store. La priorité est donnée aux fonctionnalités critiques (authentification, panier, paiements) puis aux fonctionnalités IA uniques (recommandations, PC Builder, chatbot).

**Durée estimée d'exécution manuelle** : 3-4 jours pour une équipe QA de 3 personnes.

**Prochaines étapes** :
1. Automatiser tests E2E avec Cypress/Playwright
2. Intégrer tests dans CI/CD
3. Mise en place monitoring de couverture
4. Tests de performance continus

---

**Dernière mise à jour** : Mai 2026  
**Auteur** : QA Lead  
**Approbation** : Product Manager, Tech Lead
