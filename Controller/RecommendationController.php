<?php

require_once __DIR__ . '/../model/Produit.php';
require_once __DIR__ . '/../model/ProduitTech.php';

function getRecommendations($productId, $productType = 'regular', $limit = 5)
{
    $productType = strtolower($productType);
    if ($productType === 'tech') {
        $model = new ProduitTech();
        $current = $model->getTechProduitById($productId);
        $products = $model->getAllTechProduits();
    } else {
        $model = new Produit();
        $current = $model->getProduitById($productId);
        $products = $model->getAllProduits();
    }

    if (!$current) {
        echo json_encode(['recommendations' => [], 'dfsChain' => []]);
        exit;
    }

    $graph = buildProductGraph($products);
    $bfsIds = bfsProductIds($graph, $current['id'], $limit);
    $dfsPath = dfsProductChain($graph, $current['id'], min($limit, 4));

    $recommendations = [];
    $scoreMap = [];
    foreach ($products as $product) {
        if ($product['id'] === $current['id']) {
            continue;
        }
        if (in_array($product['id'], $bfsIds, true)) {
            $recommendations[] = $product;
            $scoreMap[$product['id']] = getSimilarityScore($current, $product);
        }
    }

    usort($recommendations, function ($a, $b) use ($scoreMap) {
        return ($scoreMap[$b['id']] ?? 0) <=> ($scoreMap[$a['id']] ?? 0);
    });

    // Fallback: if not enough recommendations, fill with best-scoring remaining products
    if (count($recommendations) < $limit) {
        $remaining = array_filter($products, function ($product) use ($current, $recommendations) {
            return $product['id'] !== $current['id'] && !in_array($product['id'], array_column($recommendations, 'id'), true);
        });
        usort($remaining, function ($a, $b) use ($current) {
            return getSimilarityScore($current, $b) <=> getSimilarityScore($current, $a);
        });
        foreach ($remaining as $product) {
            if (count($recommendations) >= $limit) {
                break;
            }
            $recommendations[] = $product;
        }
    }

    echo json_encode([
        'recommendations' => array_slice($recommendations, 0, $limit),
        'dfsChain' => buildDfsChainDetails($dfsPath, $products),
    ]);
    exit;
}

function buildProductGraph(array $products)
{
    $graph = [];
    foreach ($products as $product) {
        $graph[$product['id']] = [];
    }

    foreach ($products as $productA) {
        foreach ($products as $productB) {
            if ($productA['id'] === $productB['id']) {
                continue;
            }
            $score = getSimilarityScore($productA, $productB);
            if ($score > 0) {
                $graph[$productA['id']][] = [
                    'id' => $productB['id'],
                    'score' => $score,
                ];
            }
        }
    }

    foreach ($graph as $id => &$neighbors) {
        usort($neighbors, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });
    }

    return $graph;
}

function getSimilarityScore(array $a, array $b)
{
    $score = 0;
    if (!empty($a['category']) && !empty($b['category']) && strcasecmp($a['category'], $b['category']) === 0) {
        $score += 30;
    }
    if (!empty($a['color']) && !empty($b['color']) && strcasecmp($a['color'], $b['color']) === 0) {
        $score += 25;
    }
    if (isset($a['price'], $b['price']) && is_numeric($a['price']) && is_numeric($b['price'])) {
        $diff = abs($a['price'] - $b['price']);
        if ($diff <= 10) {
            $score += 20;
        }
    }
    if (isset($a['stock'], $b['stock']) && $a['stock'] > 0 && $b['stock'] > 0) {
        $score += 5;
    }

    return $score;
}

function bfsProductIds(array $graph, $startId, $limit = 5)
{
    $visited = [$startId => true];
    $queue = [$startId];
    $results = [];

    while (!empty($queue) && count($results) < $limit) {
        $current = array_shift($queue);
        foreach ($graph[$current] as $neighbor) {
            if (!isset($visited[$neighbor['id']])) {
                $visited[$neighbor['id']] = true;
                $queue[] = $neighbor['id'];
                $results[] = $neighbor['id'];
                if (count($results) >= $limit) {
                    break 2;
                }
            }
        }
    }

    return $results;
}

function dfsProductChain(array $graph, $startId, $limit = 4)
{
    $visited = [$startId => true];
    $chain = [];
    $stack = [[$startId, 0]];

    while (!empty($stack) && count($chain) < $limit) {
        [$current, $index] = array_pop($stack);
        if ($index >= count($graph[$current])) {
            continue;
        }

        $neighbor = $graph[$current][$index];
        $stack[] = [$current, $index + 1];

        if (!isset($visited[$neighbor['id']])) {
            $visited[$neighbor['id']] = true;
            $chain[] = $neighbor['id'];
            if (count($chain) >= $limit) {
                break;
            }
            $stack[] = [$neighbor['id'], 0];
        }
    }

    return $chain;
}

function buildDfsChainDetails(array $chainIds, array $products)
{
    $map = [];
    foreach ($products as $product) {
        $map[$product['id']] = $product;
    }

    $chain = [];
    foreach ($chainIds as $id) {
        if (isset($map[$id])) {
            $chain[] = [
                'id' => $map[$id]['id'],
                'name' => $map[$id]['name'],
            ];
        }
    }

    return $chain;
}
