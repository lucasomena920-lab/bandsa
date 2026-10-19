<?php
// garantir sessão
if (session_status() === PHP_SESSION_NONE) session_start();

/**
 * Verifica se o token enviado é válido.
 */
function verify_admin_token(?string $token): bool {
    if (!$token) return false;
    // 1) token na sessão
    if (!empty($_SESSION['admin']['token']) && hash_equals($_SESSION['admin']['token'], $token)) {
        return true;
    }
    // 2) token fixo na config (opcional)
    if (defined('ADMIN_API_TOKEN') && hash_equals(ADMIN_API_TOKEN, $token)) {
        return true;
    }
    // 3) verificação em banco (opcional)
    global $pdo;
    if (!empty($pdo)) {
        try {
            $stmt = $pdo->prepare('SELECT 1 FROM admin_tokens WHERE token = :t AND expires_at > NOW() LIMIT 1');
            $stmt->execute([':t' => $token]);
            return (bool) $stmt->fetchColumn();
        } catch (Throwable $e) {
            // ignorar erro DB -> token inválido
        }
    }
    return false;
}

/**
 * Obriga autenticação admin.
 * - aceita sessão ou X-ADMIN-TOKEN
 * - para requisições AJAX/JSON retorna 401 JSON
 * - para navegação normal redireciona para /admin/login.php
 */
function require_admin(): void {
    // sessão válida?
    if (!empty($_SESSION['admin']['id'])) return;

    // token via header X-ADMIN-TOKEN
    $token = $_SERVER['HTTP_X_ADMIN_TOKEN'] ?? null;
    if ($token && verify_admin_token($token)) {
        // opcional: popular session com dados do admin
        return;
    }

    // detectar requisição AJAX/JSON
    $isAjax = (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest')
              || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);

    if ($isAjax) {
        header('Content-Type: application/json', true, 401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // redirecionar para login (ajuste o caminho conforme rota)
    header('Location: /admin/login.php');
    exit;
}