<?php

require_once __DIR__ . '/../model/Produit.php';
require_once __DIR__ . '/../model/ProduitTech.php';

function handleChat()
{
    $input = json_decode(file_get_contents('php://input'), true);
    $userMessage = $input['message'] ?? '';

    if (empty($userMessage)) {
        echo json_encode(['response' => "Désolé, je n'ai pas compris votre message."]);
        return;
    }

    // 1. Récupérer les produits pour le contexte
    $produitModel = new Produit();
    $techModel = new ProduitTech();
    
    $products = $produitModel->getAllProduits();
    $techProducts = $techModel->getAllTechProduits();
    
    $allProducts = array_merge($products, $techProducts);

    // 2. Construire le contexte
    $context = "Voici les produits disponibles chez Alpha Store :\n";
    foreach ($allProducts as $p) {
        $cat = $p['category'] ?? "Technologie";
        $context .= "- {$p['name']} ({$cat}): {$p['price']}$. Description: {$p['description']}\n";
    }

    // 3. Appeler Gemini API
    $apiKey = $_ENV['API_GEMINI'] ?? '';
    if (empty($apiKey)) {
        echo json_encode(['response' => "Erreur : Clé API Gemini non configurée."]);
        return;
    }

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . $apiKey;

    $systemInstruction = "Tu es l'assistant de vente Alpha Store. Voici notre catalogue actuel :\n" . $context . "\n\nRègles :\n- Réponds poliment en français.\n- Utilise uniquement les produits listés ci-dessus.\n- Si un produit n'est pas là, sois honnête et propose une alternative si possible.";

    $data = [
        "contents" => [
            [
                "parts" => [
                    ["text" => $systemInstruction . "\n\nMessage Utilisateur : " . $userMessage]
                ]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        echo json_encode(['response' => "Erreur de connexion à l'IA : " . $err]);
    } else {
        $result = json_decode($response, true);
        
        if (isset($result['error'])) {
            $errorMsg = $result['error']['message'] ?? 'Erreur inconnue de l\'API';
            echo json_encode(['response' => "Erreur API : " . $errorMsg]);
            return;
        }

        if (isset($result['candidates'][0]['finishReason']) && $result['candidates'][0]['finishReason'] === 'SAFETY') {
            echo json_encode(['response' => "Désolé, ma réponse a été bloquée par les filtres de sécurité."]);
            return;
        }

        $aiText = $result['candidates'][0]['content']['parts'][0]['text'] ?? null;
        
        if ($aiText) {
            echo json_encode(['response' => $aiText]);
        } else {
            // Log the response for debugging
            file_put_contents(__DIR__ . '/chatbot_error.log', $response);
            echo json_encode(['response' => "Désolé, je ne peux pas répondre pour le moment. (Réponse vide de l'IA)"]);
        }
    }
}
