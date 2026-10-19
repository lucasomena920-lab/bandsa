<?php
session_start();
if (!isset($_SESSION['order_success'])) {
    header("Location: shop.php");
    exit();
}

$order_data = $_SESSION['order_success'];
unset($_SESSION['order_success']);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Compra Realizada - Minha Banda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-success">
                    <div class="card-header bg-success text-white text-center">
                        <h2>✅ Compra Realizada com Sucesso!</h2>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                            <h3>Obrigado pela sua compra!</h3>
                            <p class="lead">Receberá um email de confirmação em breve.</p>
                        </div>
                        
                        <div class="row text-start">
                            <div class="col-md-6">
                                <h5>📦 Detalhes do Pedido</h5>
                                <p><strong>Número do Pedido:</strong> #<?php echo $order_data['order_id']; ?></p>
                                <p><strong>Data:</strong> <?php echo date('d/m/Y H:i'); ?></p>
                                <p><strong>Total:</strong> €<?php echo number_format($order_data['total'], 2); ?></p>
                                <p><strong>Método de Pagamento:</strong> <?php echo ucfirst($order_data['payment_method']); ?></p>
                            </div>
                            <div class="col-md-6">
                                <h5>🚚 Informação de Envio</h5>
                                <p>• Entrega em 2-3 dias úteis</p>
                                <p>• Portes: €5.00 incluídos</p>
                                <p>• Envio para Portugal Continental</p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <a href="../shop.php" class="btn btn-primary btn-lg">🛒 Continuar a Comprar</a>
                            <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">🖨️ Imprimir Recibo</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>