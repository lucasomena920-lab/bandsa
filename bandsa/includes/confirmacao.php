<?php
require_once 'includes/config.php';

if (!isset($_SESSION['pedido_concluido'])) {
    header('Location: carrinho.php');
    exit();
}

$pedido = $_SESSION['pedido_concluido'];
unset($_SESSION['pedido_concluido']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pedido Confirmado!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card text-center border-success">
                    <div class="card-header bg-success text-white">
                        <h2>✅ Pedido Confirmado!</h2>
                    </div>
                    <div class="card-body">
                        <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                        <h3>Obrigado, <?php echo $pedido['cliente']; ?>!</h3>
                        <p>Seu pedido foi processado com sucesso.</p>
                        
                        <div class="alert alert-info">
                            <h5>Detalhes do Pedido:</h5>
                            <p><strong>Número:</strong> <?php echo $pedido['numero_pedido']; ?></p>
                            <p><strong>Data:</strong> <?php echo $pedido['data']; ?></p>
                            <p><strong>Total:</strong> €<?php echo number_format($pedido['total'], 2, ',', ' '); ?></p>
                        </div>
                        
                        <p>Você receberá um email de confirmação em breve.</p>
                        
                        <div class="mt-4">
                            <a href="carrinho.php" class="btn btn-primary">Voltar à Loja</a>
                            <button onclick="window.print()" class="btn btn-secondary">Imprimir Recibo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>