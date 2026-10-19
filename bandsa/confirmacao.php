<?php
session_start();
if (!isset($_SESSION['pedido_concluido'])) {
    header('Location: carrinho.php');
    exit();
}

$pedido = $_SESSION['pedido_concluido'];
unset($_SESSION['pedido_concluido']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação do Pedido - Eclipse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-black text-white">
    <!-- Header -->
    <header class="bg-dark py-3 border-bottom border-danger">
        <div class="container d-flex align-items-center">
            <h5 class="mb-0 text-danger">
                <i class="fas fa-check-circle me-2"></i>Eclipse
            </h5>
            <nav class="ms-auto">
                <a href="index.html" class="text-white me-3">
                    <i class="fas fa-home"></i> Início
                </a>
                <a href="tour.html" class="text-white">
                    <i class="fas fa-ticket-alt"></i> Tour
                </a>
            </nav>
        </div>
    </header>

    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card bg-dark border-success">
                        <div class="card-header bg-success text-white text-center">
                            <h2><i class="fas fa-check-circle me-2"></i>Pedido Confirmado!</h2>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                                <h3>Obrigado pela sua compra!</h3>
                                <p class="lead">O seu pedido foi processado com sucesso.</p>
                            </div>

                            <div class="row text-start mb-4">
                                <div class="col-md-6">
                                    <div class="card bg-secondary border-0">
                                        <div class="card-body">
                                            <h5 class="text-danger"><i class="fas fa-receipt me-2"></i>Detalhes do Pedido</h5>
                                            <p><strong>Número do Pedido:</strong> <?php echo htmlspecialchars($pedido['numero_pedido']); ?></p>
                                            <p><strong>Data:</strong> <?php echo htmlspecialchars($pedido['data']); ?></p>
                                            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($pedido['cliente']); ?></p>
                                            <p><strong>Total:</strong> €<?php echo number_format($pedido['total'], 2); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-secondary border-0">
                                        <div class="card-body">
                                            <h5 class="text-danger"><i class="fas fa-shipping-fast me-2"></i>Informações de Envio</h5>
                                            <p>• Entrega em 2-3 dias úteis</p>
                                            <p>• Portes de envio incluídos</p>
                                            <p>• Envio para Portugal Continental</p>
                                            <p>• Receberá email de confirmação</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <i class="fas fa-envelope me-2"></i>
                                <strong>Confirmação enviada!</strong> Verifique o seu email para mais detalhes sobre o pedido.
                            </div>

                            <div class="mt-4">
                                <a href="tour.html" class="btn btn-danger btn-lg me-3">
                                    <i class="fas fa-ticket-alt me-2"></i>Comprar Mais Bilhetes
                                </a>
                                <a href="index.html" class="btn btn-outline-light btn-lg">
                                    <i class="fas fa-home me-2"></i>Voltar ao Início
                                </a>
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
