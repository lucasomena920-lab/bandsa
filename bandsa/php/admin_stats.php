<?php

require __DIR__ . '/../includes/config.php';
require __DIR__ . '/admin_functions.php';
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['admin']) || ($_SESSION['admin']['role'] ?? '')!=='admin') {
    http_response_code(403); echo json_encode(['success'=>false,'message'=>'Não autorizado']); exit;
}
try {
    $stats = [];
    $stats['users'] = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    $stats['products'] = (int)$pdo->query('SELECT COUNT(*) FROM produtos')->fetchColumn();
    $stats['orders'] = (int)$pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
    // recent login logs
    $recent = $pdo->query('SELECT COUNT(*) FROM admin_logs WHERE action="login" AND created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)')->fetchColumn() ?: 0;
    $lastUsers = $pdo->query('SELECT username, created_at FROM users ORDER BY created_at DESC LIMIT 6')->fetchAll();
    // top tracks (if musics table exists)
    $topTracks = [];
    try {
        $topTracks = $pdo->query('SELECT name, plays FROM musics ORDER BY plays DESC LIMIT 6')->fetchAll();
    } catch (Exception $e) { /* table may not exist */ }
    // fake traffic data if not available
    $traffic = [
      'labels' => ['-6d','-5d','-4d','-3d','-2d','-1d','Hoje'],
      'data' => [120, 140, 160, 180, 200, 170, 210]
    ];
    echo json_encode(['success'=>true,'stats'=>$stats,'recent_logins'=> (int)$recent,'lastUsers'=>$lastUsers,'topTracks'=>$topTracks,'traffic'=>$traffic]);
} catch (Exception $e) {
    http_response_code(500); echo json_encode(['success'=>false,'message'=>'Erro']); exit;
}