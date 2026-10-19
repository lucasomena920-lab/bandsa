<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['admin']) || !$_SESSION['admin']['authenticated']) {
    http_response_code(401);
    exit('Não autorizado');
}

// após autenticar o admin com sucesso:
$token = bin2hex(random_bytes(16));
$_SESSION['admin']['token'] = $token;
// opcional: persistir no DB com validade
$stmt = $pdo->prepare('INSERT INTO admin_tokens (admin_id, token, expires_at) VALUES (:id, :t, DATE_ADD(NOW(), INTERVAL 7 DAY))');
$stmt->execute([':id' => $_SESSION['admin']['id'], ':t' => $token]);
// retorne o token ao frontend (JSON) para usar no header X-ADMIN-TOKEN
echo json_encode(['success'=>true,'token'=>$token]);