<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success'=>false,'message'=>'Método não permitido.']);
    exit;
}

require __DIR__ . '/../includes/config.php';

$username = trim($_POST['username'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';
$password_confirm = $_POST['password_confirm'] ?? '';

$errors = [];
if (!$username) $errors[] = 'Nome de utilizador obrigatório.';
if (!$email) $errors[] = 'Email inválido.';
if (strlen($password) < 8) $errors[] = 'Senha deve ter pelo menos 8 caracteres.';
if ($password !== $password_confirm) $errors[] = 'Senhas não coincidem.';

if ($errors) {
    echo json_encode(['success'=>false,'message'=>implode(' ', $errors)]);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1');
    $stmt->execute([':u'=>$username, ':e'=>$email]);
    if ($stmt->fetch()) {
        echo json_encode(['success'=>false,'message'=>'Nome de utilizador ou email já existente.']);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (:u, :e, :p)');
    $stmt->execute([':u'=>$username, ':e'=>$email, ':p'=>$hash]);

    echo json_encode(['success'=>true]);
    exit;
} catch (PDOException $ex) {
    // log seguro
    file_put_contents(__DIR__.'/error.log', "[".date('c')."] register.php: ".$ex->getMessage()."\n", FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Erro interno ao criar conta.']);
    exit;
}