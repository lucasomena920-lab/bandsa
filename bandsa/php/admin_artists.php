<?php

require __DIR__ . '/../includes/config.php'; require __DIR__ . '/admin_functions.php';
header('Content-Type: application/json; charset=utf-8');
if (empty($_SESSION['admin']) || $_SESSION['admin']['role']!=='admin') { http_response_code(403); echo json_encode(['success'=>false]); exit; }
$action = $_REQUEST['action'] ?? 'list';
try {
  if ($action === 'list') { echo json_encode(['success'=>true,'artists'=>$pdo->query('SELECT * FROM artists ORDER BY created_at DESC')->fetchAll()]); exit; }
  if ($action==='create') { $name=trim($_POST['name']??''); if (!$name) { echo json_encode(['success'=>false,'message'=>'Nome obrigatório']); exit; } $stmt=$pdo->prepare('INSERT INTO artists (name,bio) VALUES (:n,:b)'); $stmt->execute([':n'=>$name,':b'=>$_POST['bio']??'']); echo json_encode(['success'=>true]); exit; }
  if ($action==='delete') { $id=(int)($_POST['id']??0); $pdo->prepare('DELETE FROM artists WHERE id=:id')->execute([':id'=>$id]); echo json_encode(['success'=>true]); exit; }
  echo json_encode(['success'=>false,'message'=>'Ação inválida']);
} catch (Exception $e) { http_response_code(500); echo json_encode(['success'=>false,'message'=>'Erro interno']); }