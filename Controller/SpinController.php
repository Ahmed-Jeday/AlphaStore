<?php
require_once __DIR__ . "/../model/SpinHistory.php";

function saveSpin() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        return;
    }

    $userId = $_SESSION['user_id'];
    $prizeLabel = $_POST['prize_label'] ?? '';
    $prizeNumber = $_POST['prize_number'] ?? 0;
    $isWin = $_POST['is_win'] ?? 0;

    $model = new SpinHistory();
    
    // Check if already spun today (optional, based on plan)
    if ($model->hasSpunToday($userId)) {
        echo json_encode(['success' => false, 'message' => 'Already spun today']);
        return;
    }

    $success = $model->saveSpin($userId, $prizeLabel, $prizeNumber, $isWin);
    echo json_encode(['success' => $success]);
}

function getSpinHistory() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        return;
    }

    $userId = $_SESSION['user_id'];
    $model = new SpinHistory();
    $history = $model->getHistoryByUser($userId);
    echo json_encode(['success' => true, 'history' => $history]);
}
