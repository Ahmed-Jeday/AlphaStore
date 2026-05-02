<?php
/**
 * PCBuildController.php
 * Proxy between the frontend and the Flask AI service for the PC Builder feature.
 */

define('FLASK_BASE', 'http://localhost:5001');

function curlPost(string $url, array $body): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
        CURLOPT_POSTFIELDS     => json_encode($body),
        CURLOPT_TIMEOUT        => 30,
    ]);
    $raw    = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err    = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return ['error' => 'Flask connection error: ' . $err];
    }
    $decoded = json_decode($raw, true);
    if ($decoded === null) {
        return ['error' => 'Invalid JSON from Flask', 'raw' => $raw];
    }
    return $decoded;
}

function curlGet(string $url): array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 15,
    ]);
    $raw = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        return ['error' => 'Flask connection error: ' . $err];
    }
    $decoded = json_decode($raw, true);
    return $decoded ?? ['error' => 'Invalid JSON from Flask'];
}

/**
 * GET ?action=getPCComponents
 * Returns all PC components grouped by type.
 */
function getPCComponents(): void
{
    header('Content-Type: application/json');
    $data = curlGet(FLASK_BASE . '/api/pc-components');
    echo json_encode($data);
}

/**
 * POST ?action=filterPCComponents
 * Body: { selected: {...}, budget: N }
 * Calls the CSP and returns annotated compatibility domains.
 */
function filterPCComponents(): void
{
    header('Content-Type: application/json');
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true) ?? [];

    $budget   = isset($body['budget']) ? max(100, min(50000, (float)$body['budget'])) : 1500;
    $selected = isset($body['selected']) && is_array($body['selected']) ? $body['selected'] : [];

    $result = curlPost(FLASK_BASE . '/api/pc-filter', [
        'budget'   => $budget,
        'selected' => $selected,
    ]);
    echo json_encode($result);
}

/**
 * POST ?action=getPCRecommendation
 * Body: { selected: {...}, budget: N, usage_profile: "gaming" }
 * Runs the GA and returns recommended components.
 */
function getPCRecommendation(): void
{
    header('Content-Type: application/json');
    $raw  = file_get_contents('php://input');
    $body = json_decode($raw, true) ?? [];

    $budget        = isset($body['budget']) ? max(100, min(50000, (float)$body['budget'])) : 1500;
    $selected      = isset($body['selected']) && is_array($body['selected']) ? $body['selected'] : [];
    $valid_profiles = ['gaming', 'workstation', 'budget', 'streaming'];
    $usage_profile  = in_array($body['usage_profile'] ?? '', $valid_profiles)
                        ? $body['usage_profile']
                        : 'gaming';

    $result = curlPost(FLASK_BASE . '/api/pc-recommend', [
        'budget'        => $budget,
        'selected'      => $selected,
        'usage_profile' => $usage_profile,
    ]);
    echo json_encode($result);
}
