<?php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/../includes/config.php';

$user_id = $_SESSION['user']['id'];

// Fetch full user details
$stmt = $pdo->prepare('SELECT id, username, email, role, created_at FROM users WHERE id = :id');
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch();

// Fetch order history
$stmt = $pdo->prepare('
    SELECT o.id, o.total, o.order_date, o.status,
           COUNT(oi.id) as item_count
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = :user_id
    GROUP BY o.id
    ORDER BY o.order_date DESC
    LIMIT 10
');
$stmt->execute([':user_id' => $user_id]);
$orders = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Perfil • Eclipse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
    <!-- Header -->
    <header class="bg-dark py-3 border-bottom border-danger">
        <div class="container d-flex align-items-center">
            <h5 class="mb-0 text-danger">
                <i class="fas fa-user-circle me-2"></i>Área do Utilizador
            </h5>
            <nav class="ms-auto">
                <a href="../index.html" class="text-white me-3">
                    <i class="fas fa-home"></i> Início
                </a>
                <a href="logout.php" class="text-white">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </nav>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            <!-- Welcome Section -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card bg-light border-danger">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-user-circle fa-4x text-danger mb-3"></i>
                            <h2 class="text-dark mb-2">Bem-vindo, <?=htmlspecialchars($user['username'])?></h2>
                            <p class="text-muted mb-0">Membro desde <?=date('d/m/Y', strtotime($user['created_at']))?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- User Info -->
                <div class="col-lg-4 mb-4">
                    <div class="card bg-dark border-danger h-100">
                        <div class="card-header bg-danger text-white">
                            <i class="fas fa-info-circle me-2"></i>Informações da Conta
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label text-muted">Nome de Utilizador</label>
                                <p class="mb-0"><?=htmlspecialchars($user['username'])?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Email</label>
                                <p class="mb-0"><?=htmlspecialchars($user['email'])?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Função</label>
                                <span class="badge bg-danger">
                                    <i class="fas fa-crown me-1"></i><?=ucfirst(htmlspecialchars($user['role']))?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order History -->
                <div class="col-lg-8 mb-4">
                    <div class="card bg-light border-danger h-100">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-shopping-cart me-2"></i>Histórico de Compras</span>
                            <span class="badge bg-light text-dark"><?=count($orders)?> pedidos</span>
                        </div>
                        <div class="card-body">
                            <?php if (empty($orders)): ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Nenhuma compra realizada ainda.</p>
                                    <a href="../tour.html" class="btn btn-danger btn-sm">
                                        <i class="fas fa-ticket-alt me-1"></i>Comprar Bilhetes
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-light table-hover">
                                        <thead>
                                            <tr>
                                                <th><i class="fas fa-hashtag"></i> Pedido</th>
                                                <th><i class="fas fa-calendar"></i> Data</th>
                                                <th><i class="fas fa-list"></i> Itens</th>
                                                <th><i class="fas fa-euro-sign"></i> Total</th>
                                                <th><i class="fas fa-info-circle"></i> Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($orders as $order): ?>
                                                <tr>
                                                    <td>#<?=htmlspecialchars($order['id'])?></td>
                                                    <td><?=date('d/m/Y', strtotime($order['order_date']))?></td>
                                                    <td><?=$order['item_count']?></td>
                                                    <td>€<?=number_format($order['total'], 2)?></td>
                                                    <td>
                                                        <span class="badge bg-<?=match($order['status']) {
                                                            'completed' => 'success',
                                                            'pending' => 'warning',
                                                            'cancelled' => 'danger',
                                                            default => 'secondary'
                                                        }?>">
                                                            <?=ucfirst(htmlspecialchars($order['status']))?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row">
                <div class="col-12">
                    <div class="card bg-dark border-danger">
                        <div class="card-header bg-danger text-white">
                            <i class="fas fa-bolt me-2"></i>Ações Rápidas
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <a href="../tour.html" class="btn btn-outline-danger btn-lg w-100">
                                        <i class="fas fa-ticket-alt fa-2x mb-2"></i><br>
                                        Comprar Bilhetes
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="../albuns.html" class="btn btn-outline-danger btn-lg w-100">
                                        <i class="fas fa-music fa-2x mb-2"></i><br>
                                        Explorar Álbuns
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="../contactos.html" class="btn btn-outline-danger btn-lg w-100">
                                        <i class="fas fa-envelope fa-2x mb-2"></i><br>
                                        Contactar
                                    </a>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <a href="logout.php" class="btn btn-outline-danger btn-lg w-100">
                                        <i class="fas fa-sign-out-alt fa-2x mb-2"></i><br>
                                        Terminar Sessão
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
