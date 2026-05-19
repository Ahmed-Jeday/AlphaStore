# 📚 RESSOURCES COMPLÉMENTAIRES — Alpha Store Testing

**Document:** Ressources d'appui, données test, et templates  
**Projet:** Alpha Store  
**Date:** Mai 2026  

---

## 📋 Table des matières

1. [SQL Fixtures](#sql-fixtures)
2. [Postman Collection](#postman-collection-json)
3. [JMeter Test Plan](#jmeter-test-plan-xml)
4. [Template de rapport](#template-de-rapport)
5. [Variables d'environnement](#variables-denvironnement)
6. [Matrice de traçabilité](#matrice-de-traçabilité)

---

## SQL Fixtures

**Fichier:** `test_fixtures.sql`

```sql
-- ═══════════════════════════════════════════════════════════
-- FIXTURES DE TEST — Alpha Store
-- ═══════════════════════════════════════════════════════════

-- Nettoyer données anciennes (OPTIONNEL - utiliser avec caution)
-- DELETE FROM orders;
-- DELETE FROM order_items;
-- DELETE FROM cart;
-- DELETE FROM reviews;
-- DELETE FROM favorites;
-- DELETE FROM users;
-- DELETE FROM produits;

-- ───────────────────────────────────────────────────────────
-- 1. UTILISATEURS TEST
-- ───────────────────────────────────────────────────────────

-- Mot de passe: Test@1234 (bcrypt)
INSERT INTO users (id, email, password, nom, prenom, telephone, adresse, role, is_verified, created_at) VALUES
(1, 'test@email.com', '$2y$10$G2yP7f4K9L3T2M1V5Q8Z9X0Y1N2C3B4A5D6E7F8G9H0I1J2K3L4M5', 'Test', 'User', '0123456789', '123 Rue Test, Alger, Algérie', 'customer', 1, NOW()),
(2, 'admin@email.com', '$2y$10$H3zQ8g5L0M4U3N2W6R9A0Y1Z2O3D4C5B6E7F8G9I1J2K3L4M5N6', 'Admin', 'User', '0987654321', '456 Rue Admin, Alger, Algérie', 'admin', 1, NOW()),
(3, 'user2@email.com', '$2y$10$I4aR9h6M1N5V4O3X7S0B1Z2A3P4E5D6C7F8G9H0J3K4L5M6N7O8', 'Autre', 'User', '0555555555', '789 Rue User, Oran, Algérie', 'customer', 1, NOW());

-- ───────────────────────────────────────────────────────────
-- 2. PRODUITS TECH
-- ───────────────────────────────────────────────────────────

INSERT INTO produits (id, nom, description, prix, stock, categorie, image, created_at) VALUES
(1, 'Laptop Dell XPS 13', 'Ultraportable performant avec écran OLED', 1500, 10, 'Tech', 'laptop_dell_xps.jpg', NOW()),
(2, 'Smartphone iPhone 15', 'Dernier modèle Apple haute performance', 2500, 5, 'Tech', 'iphone_15.jpg', NOW()),
(3, 'Souris Logitech MX Master', 'Souris sans fil professionnelle', 300, 20, 'Tech', 'mouse_logitech.jpg', NOW()),
(4, 'Clavier mécanique Corsair', 'Clavier gaming RGB', 400, 15, 'Tech', 'keyboard_corsair.jpg', NOW()),
(5, 'Écran Dell 4K 27"', 'Moniteur haute résolution pour pro', 800, 8, 'Tech', 'monitor_dell.jpg', NOW()),
(6, 'Casque Sony WH-1000XM5', 'Casque avec ANC premium', 600, 12, 'Tech', 'headphones_sony.jpg', NOW()),
(7, 'SSD Samsung 1TB', 'Disque dur ultra-rapide NVMe', 150, 25, 'Tech', 'ssd_samsung.jpg', NOW()),
(8, 'Webcam Logitech 4K', 'Caméra pour streaming professionnel', 250, 0, 'Tech', 'webcam_logitech.jpg', NOW());

-- ───────────────────────────────────────────────────────────
-- 3. PRODUITS FASHION
-- ───────────────────────────────────────────────────────────

INSERT INTO produits (id, nom, description, prix, stock, categorie, image, created_at) VALUES
(9, 'T-Shirt Coton Bio', 'Tshirt 100% coton biologique', 500, 30, 'Fashion', 'tshirt_cotton.jpg', NOW()),
(10, 'Jeans Slim Fit', 'Jeans bleu confortable', 1200, 20, 'Fashion', 'jeans_slim.jpg', NOW()),
(11, 'Robe Été Fleurs', 'Robe légère motif floral', 2000, 15, 'Fashion', 'dress_floral.jpg', NOW()),
(12, 'Veste Blazer Noir', 'Blazer élégant pour bureau', 3500, 10, 'Fashion', 'blazer_black.jpg', NOW()),
(13, 'Chaussures Running Nike', 'Sneakers de course confortables', 1500, 18, 'Fashion', 'shoes_nike.jpg', NOW()),
(14, 'Sac à Main Cuir', 'Handbag classique en cuir véritable', 4000, 12, 'Fashion', 'bag_leather.jpg', NOW()),
(15, 'Ceinture Tissée', 'Ceinture élastique multicolore', 300, 40, 'Fashion', 'belt_woven.jpg', NOW()),
(16, 'Montre Analogique', 'Montre classique cuir et acier', 2500, 8, 'Fashion', 'watch_analog.jpg', NOW());

-- ───────────────────────────────────────────────────────────
-- 4. AVIS PRODUITS
-- ───────────────────────────────────────────────────────────

INSERT INTO reviews (id, product_id, user_id, rating, title, comment, helpful_count, created_at) VALUES
(1, 1, 3, 5, 'Excellent laptop!', 'Très satisfait de ce produit, performant et léger', 12, NOW()),
(2, 1, 2, 4, 'Bon rapport qualité-prix', 'Bon écran, batterie un peu faible', 8, NOW()),
(3, 2, 1, 5, 'iPhone au top', 'Caméra exceptionnelle et design magnifique', 25, NOW()),
(4, 3, 2, 4, 'Souris excellente', 'Ergonomique mais prise en main un peu longue', 6, NOW()),
(5, 13, 3, 5, 'Chaussures confortables', 'Idéales pour la course, très bien aérées', 15, NOW());

-- ───────────────────────────────────────────────────────────
-- 5. PANIER TEST
-- ───────────────────────────────────────────────────────────

INSERT INTO cart (id, user_id, product_id, quantity, created_at) VALUES
(1, 1, 1, 1, NOW()),
(2, 1, 3, 2, NOW());

-- ───────────────────────────────────────────────────────────
-- 6. COMMANDES TEST
-- ───────────────────────────────────────────────────────────

INSERT INTO orders (id, user_id, total, status, address, city, postal_code, payment_method, created_at) VALUES
(1, 1, 2100, 'En attente', '123 Rue Test', 'Alger', '16000', 'card', NOW()),
(2, 1, 2500, 'Expédiée', '123 Rue Test', 'Alger', '16000', 'card', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(3, 3, 1500, 'Livrée', '789 Rue User', 'Oran', '31000', 'bank_transfer', DATE_SUB(NOW(), INTERVAL 15 DAY));

-- ───────────────────────────────────────────────────────────
-- 7. ARTICLES COMMANDE
-- ───────────────────────────────────────────────────────────

INSERT INTO order_items (id, order_id, product_id, quantity, price, subtotal) VALUES
(1, 1, 1, 1, 1500, 1500),
(2, 1, 3, 2, 300, 600),
(3, 2, 2, 1, 2500, 2500),
(4, 3, 13, 1, 1500, 1500);

-- ───────────────────────────────────────────────────────────
-- 8. FAVORIS TEST
-- ───────────────────────────────────────────────────────────

INSERT INTO favorites (id, user_id, product_id, created_at) VALUES
(1, 1, 1, NOW()),
(2, 1, 13, NOW()),
(3, 3, 2, NOW());

-- ───────────────────────────────────────────────────────────
-- 9. VÉRIFIER DONNÉES INSÉRÉES
-- ───────────────────────────────────────────────────────────

-- SELECT COUNT(*) as total_users FROM users;
-- SELECT COUNT(*) as total_products FROM produits;
-- SELECT COUNT(*) as total_orders FROM orders;
-- SELECT * FROM users WHERE email = 'test@email.com';
-- SELECT * FROM produits WHERE categorie = 'Tech' LIMIT 5;

-- ═══════════════════════════════════════════════════════════
-- FIN DES FIXTURES
-- ═══════════════════════════════════════════════════════════
```

**Utilisation:**

```bash
# Charger les données test
mysql -u root -p alphastore < C:\xampp\htdocs\AlphaStore\test_fixtures.sql

# Vérifier
mysql -u root -p -e "USE alphastore; SELECT COUNT(*) FROM users; SELECT COUNT(*) FROM produits;"
```

---

## Postman Collection JSON

**Fichier:** `AlphaStore.postman_collection.json`

```json
{
  "info": {
    "name": "Alpha Store API Tests",
    "description": "Complete API test collection for Alpha Store e-commerce",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "Authentication",
      "item": [
        {
          "name": "Signup",
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "pm.test('Status 200', function() { pm.response.to.have.status(200); });",
                  "pm.test('Has token', function() { var json = pm.response.json(); pm.expect(json.token).to.exist; pm.environment.set('USER_TOKEN', json.token); });"
                ]
              }
            }
          ],
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"email\": \"newuser_{{$timestamp}}@test.com\",\n  \"password\": \"Test@1234\",\n  \"nom\": \"Test User\",\n  \"prenom\": \"Test\"\n}"
            },
            "url": {
              "raw": "{{BASE_URL}}/Controller/AuthController.php?action=signup",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "AuthController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "signup"
                }
              ]
            }
          }
        },
        {
          "name": "Login",
          "event": [
            {
              "listen": "test",
              "script": {
                "exec": [
                  "pm.test('Status 200', function() { pm.response.to.have.status(200); });",
                  "var json = pm.response.json(); pm.environment.set('USER_TOKEN', json.token);"
                ]
              }
            }
          ],
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"email\": \"test@email.com\",\n  \"password\": \"Test@1234\"\n}"
            },
            "url": {
              "raw": "{{BASE_URL}}/Controller/AuthController.php?action=login",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "AuthController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "login"
                }
              ]
            }
          }
        },
        {
          "name": "Logout",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{USER_TOKEN}}"
              }
            ],
            "url": {
              "raw": "{{BASE_URL}}/Controller/AuthController.php?action=logout",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "AuthController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "logout"
                }
              ]
            }
          }
        }
      ]
    },
    {
      "name": "Products",
      "item": [
        {
          "name": "Get All Products",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{BASE_URL}}/Controller/ProduitController.php?action=getProducts&page=1&limit=10",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "ProduitController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "getProducts"
                },
                {
                  "key": "page",
                  "value": "1"
                },
                {
                  "key": "limit",
                  "value": "10"
                }
              ]
            }
          }
        },
        {
          "name": "Get Product Details",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{BASE_URL}}/Controller/ProduitController.php?action=getProduct&id=1",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "ProduitController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "getProduct"
                },
                {
                  "key": "id",
                  "value": "1"
                }
              ]
            }
          }
        },
        {
          "name": "Search Products",
          "request": {
            "method": "GET",
            "header": [],
            "url": {
              "raw": "{{BASE_URL}}/Controller/ProduitController.php?action=search&q=laptop",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "ProduitController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "search"
                },
                {
                  "key": "q",
                  "value": "laptop"
                }
              ]
            }
          }
        }
      ]
    },
    {
      "name": "Cart",
      "item": [
        {
          "name": "Add to Cart",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              },
              {
                "key": "Authorization",
                "value": "Bearer {{USER_TOKEN}}"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"product_id\": 1,\n  \"quantity\": 2\n}"
            },
            "url": {
              "raw": "{{BASE_URL}}/Controller/CartController.php?action=add",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "CartController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "add"
                }
              ]
            }
          }
        },
        {
          "name": "Get Cart",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{USER_TOKEN}}"
              }
            ],
            "url": {
              "raw": "{{BASE_URL}}/Controller/CartController.php?action=get",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "CartController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "get"
                }
              ]
            }
          }
        },
        {
          "name": "Remove from Cart",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              },
              {
                "key": "Authorization",
                "value": "Bearer {{USER_TOKEN}}"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"product_id\": 1\n}"
            },
            "url": {
              "raw": "{{BASE_URL}}/Controller/CartController.php?action=remove",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "CartController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "remove"
                }
              ]
            }
          }
        }
      ]
    },
    {
      "name": "Orders",
      "item": [
        {
          "name": "Create Order",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              },
              {
                "key": "Authorization",
                "value": "Bearer {{USER_TOKEN}}"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"address\": \"123 Rue Test\",\n  \"city\": \"Alger\",\n  \"postal_code\": \"16000\",\n  \"payment_method\": \"card\"\n}"
            },
            "url": {
              "raw": "{{BASE_URL}}/Controller/OrderController.php?action=create",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "OrderController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "create"
                }
              ]
            }
          }
        },
        {
          "name": "Get Order History",
          "request": {
            "method": "GET",
            "header": [
              {
                "key": "Authorization",
                "value": "Bearer {{USER_TOKEN}}"
              }
            ],
            "url": {
              "raw": "{{BASE_URL}}/Controller/OrderController.php?action=getHistory",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "OrderController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "getHistory"
                }
              ]
            }
          }
        }
      ]
    },
    {
      "name": "Chatbot",
      "item": [
        {
          "name": "Send Message",
          "request": {
            "method": "POST",
            "header": [
              {
                "key": "Content-Type",
                "value": "application/json"
              }
            ],
            "body": {
              "mode": "raw",
              "raw": "{\n  \"message\": \"Quel laptop recommandez-vous?\"\n}"
            },
            "url": {
              "raw": "{{BASE_URL}}/Controller/ChatbotController.php?action=message",
              "host": ["{{BASE_URL}}"],
              "path": ["Controller", "ChatbotController.php"],
              "query": [
                {
                  "key": "action",
                  "value": "message"
                }
              ]
            }
          }
        }
      ]
    }
  ],
  "variable": [
    {
      "key": "BASE_URL",
      "value": "http://localhost/AlphaStore",
      "type": "string"
    },
    {
      "key": "USER_TOKEN",
      "value": "",
      "type": "string"
    }
  ]
}
```

**Importer dans Postman:**

```
1. Postman → Import
2. Sélectionner ce fichier JSON
3. Configurer variable BASE_URL si nécessaire
4. Exécuter les requests dans l'ordre
```

---

## JMeter Test Plan XML

**Fichier:** `AlphaStore_load_test.jmx`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<jmeterTestPlan version="1.2" properties="5.0" jmeter="5.5">
  <hashTree>
    <TestPlan guiclass="TestPlanGui" testclass="TestPlan" testname="Alpha Store Load Test" enabled="true">
      <elementProp name="TestPlan.user_defined_variables" elementType="Arguments" guiclass="ArgumentsPanel" testclass="Arguments" testname="Variables" enabled="true">
        <collectionProp name="Arguments.arguments"/>
      </elementProp>
      <stringProp name="TestPlan.user_define_variables"></stringProp>
      <boolProp name="TestPlan.functional_mode">false</boolProp>
      <boolProp name="TestPlan.serialize_threadgroups">false</boolProp>
      <elementProp name="TestPlan.cookie_manager" elementType="CookieManager" guiclass="CookiePanel" testclass="CookieManager" testname="HTTP Cookie Manager" enabled="true">
        <collectionProp name="CookieManager.cookies"/>
        <boolProp name="CookieManager.clearEachIteration">false</boolProp>
        <boolProp name="CookieManager.cookiePolicy.secure.cookie">false</boolProp>
      </elementProp>
    </TestPlan>
    <hashTree>
      <ThreadGroup guiclass="ThreadGroupGui" testclass="ThreadGroup" testname="50 Concurrent Users" enabled="true">
        <elementProp name="ThreadGroup.main_controller" elementType="LoopController" guiclass="LoopControlPanel" testclass="LoopController" testname="Loop Controller" enabled="true">
          <boolProp name="LoopController.continue_forever">false</boolProp>
          <stringProp name="LoopController.loops">1</stringProp>
        </elementProp>
        <stringProp name="ThreadGroup.num_threads">50</stringProp>
        <stringProp name="ThreadGroup.ramp_time">10</stringProp>
        <elementProp name="ThreadGroup.duration_assertion" elementType="DurationAssertion" guiclass="DurationAssertionGui" testclass="DurationAssertion" testname="Duration Assertion" enabled="false">
          <stringProp name="DurationAssertion.assumedDuration">0</stringProp>
        </elementProp>
        <longProp name="ThreadGroup.start_time">1654000000000</longProp>
        <longProp name="ThreadGroup.end_time">1654000000000</longProp>
        <boolProp name="ThreadGroup.same_user_objects">true</boolProp>
        <boolProp name="ThreadGroup.scheduler">false</boolProp>
        <stringProp name="ThreadGroup.on_sample_error">continue</stringProp>
        <elementProp name="ThreadGroup.main_controller" elementType="LoopController" guiclass="LoopControlPanel" testclass="LoopController" testname="Loop Controller" enabled="true">
          <boolProp name="LoopController.continue_forever">false</boolProp>
          <stringProp name="LoopController.loops">1</stringProp>
        </elementProp>
      </ThreadGroup>
      <hashTree>
        <ConfigTestElement guiclass="HttpDefaultsGui" testclass="ConfigTestElement" testname="HTTP Request Defaults" enabled="true">
          <elementProp name="HTTPsampler.Arguments" elementType="Arguments" guiclass="HTTPArgumentsPanel" testclass="Arguments" testname="User Defined Variables" enabled="true">
            <collectionProp name="Arguments.arguments"/>
          </elementProp>
          <stringProp name="HTTPSampler.domain">localhost</stringProp>
          <stringProp name="HTTPSampler.port">80</stringProp>
          <stringProp name="HTTPSampler.protocol">http</stringProp>
          <stringProp name="HTTPSampler.path">/AlphaStore</stringProp>
          <stringProp name="HTTPSampler.image.parser.classname">com.jmeter.plugins.parsers.html.RegexExtractor</stringProp>
          <boolProp name="HTTPSampler.image.parser.enabled">false</boolProp>
        </ConfigTestElement>
        <hashTree/>
        <HTTPSamplerProxy guiclass="HttpTestSampleGui" testclass="HTTPSamplerProxy" testname="Homepage" enabled="true">
          <elementProp name="HTTPsampler.Arguments" elementType="Arguments" guiclass="HTTPArgumentsPanel" testclass="Arguments" testname="User Defined Variables" enabled="true">
            <collectionProp name="Arguments.arguments"/>
          </elementProp>
          <stringProp name="HTTPSampler.domain"></stringProp>
          <stringProp name="HTTPSampler.port"></stringProp>
          <stringProp name="HTTPSampler.protocol"></stringProp>
          <stringProp name="HTTPSampler.contentEncoding"></stringProp>
          <stringProp name="HTTPSampler.path">/View/html/index.html</stringProp>
          <stringProp name="HTTPSampler.method">GET</stringProp>
          <boolProp name="HTTPSampler.follow_redirects">true</boolProp>
          <boolProp name="HTTPSampler.auto_redirects">false</boolProp>
          <boolProp name="HTTPSampler.use_keepalive">true</boolProp>
          <boolProp name="HTTPSampler.DO_MULTIPART_POST">false</boolProp>
          <stringProp name="HTTPSampler.embedded_url_re"></stringProp>
          <stringProp name="HTTPSampler.connect_timeout"></stringProp>
          <stringProp name="HTTPSampler.response_timeout"></stringProp>
        </HTTPSamplerProxy>
        <hashTree>
          <ResponseAssertion guiclass="AssertionGui" testclass="ResponseAssertion" testname="Assert Response Code 200" enabled="true">
            <elementProp name="TestElements.assertions" elementType="AssertionTestElement">
              <stringProp name="Assertion.test_type">1</stringProp>
              <stringProp name="Assertion.test_strings">200</stringProp>
            </elementProp>
            <stringProp name="Assertion.test_type">1</stringProp>
            <boolProp name="Assertion.assume_success">false</boolProp>
            <intProp name="Assertion.test_type">1</intProp>
          </ResponseAssertion>
          <hashTree/>
        </hashTree>
        <ResultCollector guiclass="ViewResultsFullVisualizer" testclass="ResultCollector" testname="View Results Tree" enabled="true">
          <elementProp name="ResultCollector.sample_filter_prop" elementType="ResultFilter"/>
          <stringProp name="ResultCollector.filename"></stringProp>
          <stringProp name="ResultCollector.success_filter.value"></stringProp>
        </ResultCollector>
        <hashTree/>
        <ResultCollector guiclass="StatVisualizer" testclass="ResultCollector" testname="Summary Report" enabled="true">
          <elementProp name="ResultCollector.sample_filter_prop" elementType="ResultFilter"/>
          <stringProp name="ResultCollector.filename"></stringProp>
        </ResultCollector>
        <hashTree/>
      </hashTree>
    </hashTree>
  </hashTree>
</jmeterTestPlan>
```

**Utiliser dans JMeter:**

```
1. JMeter → Open → AlphaStore_load_test.jmx
2. Configurer Thread Group selon besoin
3. Run → Start (Ctrl+Enter)
4. Observer résultats
```

---

## Template de Rapport

**Fichier:** `TEST_REPORT_TEMPLATE.md`

```markdown
# 📊 RAPPORT DE TEST FONCTIONNEL
**Projet:** Alpha Store  
**Version Testée:** 1.0  
**Date Test:** [DATE_TEST]  
**Testeur Lead:** [NOM_TESTEUR]  
**Environnement:** DEV/STAGING/PROD  

---

## 📈 RÉSUMÉ EXÉCUTIF

| Métrique | Valeur |
|----------|--------|
| **Durée totale** | X jours, Y heures |
| **Total tests planifiés** | 87 |
| **Tests exécutés** | XX |
| **Tests réussis** | XX (XX%) |
| **Tests échoués** | XX (XX%) |
| **Tests bloqués** | XX (XX%) |
| **Bugs trouvés** | XX |
| **- Critiques** | XX |
| **- Majeurs** | XX |
| **- Mineurs** | XX |
| **Recommandation** | ☐ Approuvé | ☐ Approuvé avec réserves | ☐ Rejeté |

---

## 📊 RÉSULTATS PAR DOMAINE

### ✅ Domaines avec 100% réussite
- Authentification (8/8)
- Catalogue (6/6)
- Panier (7/8) → 1 bug mineur

### ⚠️ Domaines avec des issues
- Paiements (4/5) → 1 timeout API
- PC Builder (8/10) → 2 bugs CSP

### ❌ Domaines non testés
- [Lister si applicable]

---

## 🐛 BUGS TROUVÉS

### Critique 🔴

| ID | Titre | Module | Sévérité | Status | Assigné |
|----|-------|--------|----------|--------|---------|
| BUG-001 | Cart doesn't update on product change | Panier | 🔴 | New | Dev-X |
| BUG-002 | SQL Injection vulnerable in search | Sécurité | 🔴 | New | Dev-Y |

### Majeur 🟠

| ID | Titre | Module | Sévérité | Status |
|----|-------|--------|----------|--------|
| BUG-003 | Chatbot API timeout > 5s | AI | 🟠 | Assigned |

### Mineur 🟡

| ID | Titre | Module | Sévérité | Status |
|----|-------|--------|----------|--------|
| BUG-004 | Button text misaligned on mobile | UI | 🟡 | New |

---

## ⚡ PERFORMANCE

| Test | Résultat | Seuil | Status |
|------|----------|-------|--------|
| FCP | 1.2s | < 2s | ✅ PASS |
| Page Load | 2.8s | < 3s | ✅ PASS |
| API Latency | 342ms | < 500ms | ✅ PASS |
| 50 concurrent | Stable | No errors | ✅ PASS |
| 1000 concurrent | Rupture @ 950 users | >= 1000 | ⚠️ NEAR |

---

## 🔒 SÉCURITÉ

| Test | Résultat | Status |
|------|----------|--------|
| SQL Injection | ❌ Vulnérable (BUG-002) | 🔴 FAIL |
| XSS Protection | ✅ Échappé correctement | ✅ PASS |
| CSRF Token | ✅ Présent sur tous forms | ✅ PASS |
| Force Brute | ✅ Bloqué après 5 tentatives | ✅ PASS |
| HTTPS Redirect | ✅ Fonctionne | ✅ PASS |

---

## 📋 ACTIONS REQUISES

### Avant la release (Bloquer release)
- [ ] FIX BUG-001 (Cart update)
- [ ] FIX BUG-002 (SQL Injection)
- [ ] Re-test domaines affectés

### Avant go-live (Can be deferred)
- [ ] FIX BUG-003 (Chatbot latency)
- [ ] Optimiser performance (target 100% stable)

### Nice-to-have (Prochaine release)
- [ ] FIX BUG-004 (UI alignment)
- [ ] Améliorer code coverage tests

---

## ✅ SIGN OFF

| Rôle | Nom | Signature | Date |
|------|-----|-----------|------|
| QA Lead | [Nom] | [Signature] | [Date] |
| Tech Lead | [Nom] | [Signature] | [Date] |
| Product Owner | [Nom] | [Signature] | [Date] |

---

## 📎 ANNEXES

- Checklist complète exécution: [LIEN]
- Screenshots bugs: /outputs/screenshots/
- JMeter results: test_results_${DATE}.jtl
- Lighthouse report: lighthouse_${DATE}.html
- Logs serveur: /xampp/apache/logs/error.log
```

---

## Variables d'environnement

**Fichier:** `.env.test`

```bash
# ═══════════════════════════════════════════════════════════
# CONFIGURATION ENVIRONNEMENT TEST — Alpha Store
# ═══════════════════════════════════════════════════════════

# BASE CONFIGURATION
APP_NAME=Alpha Store
APP_ENV=testing
APP_DEBUG=true
BASE_URL=http://localhost/AlphaStore

# DATABASE
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=alphastore
DB_USERNAME=root
DB_PASSWORD=
DB_PREFIX=

# SERVICES
FLASK_URL=http://localhost:5001
GEMINI_API_KEY=your_gemini_key_here
STRIPE_API_KEY=sk_test_...
STRIPE_PUBLIC_KEY=pk_test_...
PAYPAL_CLIENT_ID=your_paypal_id
PAYPAL_SECRET=your_paypal_secret

# EMAIL (for testing)
MAIL_DRIVER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=test@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_FROM=test@alphastore.com

# SECURITY
SESSION_DRIVER=file
CSRF_ENABLED=true
BCRYPT_ROUNDS=10

# TEST DATA
TEST_USER_EMAIL=test@email.com
TEST_USER_PASSWORD=Test@1234
ADMIN_EMAIL=admin@email.com
ADMIN_PASSWORD=AdminPass456

# LOGGING
LOG_LEVEL=debug
LOG_PATH=/outputs/logs/test_${DATE}.log

# CACHE
CACHE_DRIVER=file
CACHE_TTL=3600
```

---

## Matrice de traçabilité

**Fichier:** `TRACEABILITY_MATRIX.xlsx` (Format simplifié)

| Test ID | Description | Module | Priorité | Dépendances | Status | Bug ID |
|---------|---|---|---|---|---|---|
| TC-AUTH-001 | Inscription | Auth | 🔴 | Aucune | PASS | - |
| TC-AUTH-004 | Login | Auth | 🔴 | Aucune | PASS | - |
| TC-CAT-001 | Accueil | Catalog | 🔴 | TC-AUTH-004 | PASS | - |
| TC-CART-001 | Ajouter panier | Cart | 🔴 | TC-AUTH-004, TC-CAT-001 | FAIL | BUG-001 |
| TC-ORDER-001 | Créer commande | Orders | 🔴 | TC-CART-001 | PASS | - |
| TC-PAY-001 | Paiement | Payment | 🔴 | TC-ORDER-001 | PASS | - |
| TC-REC-001 | Recommandations | IA | 🟠 | TC-CAT-001 | PASS | - |
| TC-PCB-001 | PC Builder | IA | 🟠 | Aucune | FAIL | BUG-005, BUG-006 |
| TC-CHAT-001 | Chatbot | AI | 🟠 | Aucune | PASS | - |
| TC-SEC-001 | SQL Injection | Security | 🔴 | Aucune | FAIL | BUG-002 |

---

## Checklist Rapide Déploiement

```bash
✅ PRÉ-TEST
- [ ] Environnement test configuré
- [ ] BD restaurée et données test chargées
- [ ] Services Flask démarrés
- [ ] APIs externes accessibles
- [ ] Outils test installés

✅ EXÉCUTION
- [ ] Tests critiques d'authentification (TC-AUTH-*)
- [ ] Tests critiques du panier/commandes (TC-CART-*, TC-ORDER-*)
- [ ] Tests critiques paiements (TC-PAY-*)
- [ ] Tests sécurité (TC-SEC-*)
- [ ] Tests performance (TC-PERF-*)
- [ ] Smoke tests régression

✅ POST-TEST
- [ ] Rapport généré
- [ ] Bugs documentés et assignés
- [ ] Sign-off obtenu
- [ ] Données test nettoyées
- [ ] Logs archivés
- [ ] Environnement test reset
```

---

**Créé:** Mai 2026  
**Version:** 1.0  
**Équipe:** QA Alpha Store
