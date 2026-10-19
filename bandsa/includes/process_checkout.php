<?php
require_once 'includes/config.php';

// Verificar se temos dados do checkout
if (!isset($_SESSION['carrinho_checkout']) || !isset($_SESSION['totais_checkout'])) {
    header('Location: carrinho.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();
        
        // Dados do cliente
        $nome_cliente = $_POST['nome'];
        $email_cliente = $_POST['email'];
        $telefone_cliente = $_POST['telefone'];
        $metodo_pagamento = $_POST['pagamento'];
        $nif = $_POST['nif'] ?? null;
        
        // Totais da sessão
        $totais = $_SESSION['totais_checkout'];
        
        // Gerar número do pedido único
        $numero_pedido = 'PED' . date('YmdHis') . rand(100, 999);
        
        // 1. Criar encomenda
        $stmt = $pdo->prepare("
            INSERT INTO encomendas 
            (numero_pedido, nome_cliente, email_cliente, telefone_cliente, metodo_pagamento, subtotal, taxa, total, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendente')
        ");
        
        $stmt->execute([
            $numero_pedido,
            $nome_cliente,
            $email_cliente,
            $telefone_cliente,
            $metodo_pagamento,
            $totais['subtotal'],
            $totais['taxa'],
            $totais['total']
        ]);
        
        $encomenda_id = $pdo->lastInsertId();
        
        // 2. Adicionar itens da encomenda
        $carrinho = $_SESSION['carrinho_checkout'];
        
        foreach ($carrinho as $item) {
            // Atualizar stock do produto
            $stmt = $pdo->prepare("UPDATE produtos SET stock = stock - ? WHERE id = ? AND stock >= ?");
            $stmt->execute([$item['quantidade'], $item['id'], $item['quantidade']]);
            
            // Adicionar item à encomenda
            $stmt = $pdo->prepare("
                INSERT INTO encomenda_itens 
                (encomenda_id, produto_id, produto_nome, quantidade, preco_unitario, subtotal_item) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            $subtotal_item = $item['preco'] * $item['quantidade'];
            $stmt->execute([
                $encomenda_id,
                $item['id'],
                $item['nome'],
                $item['quantidade'],
                $item['preco'],
                $subtotal_item
            ]);
        }
        
        $pdo->commit();
        
        // Guardar dados para mostrar na confirmação
        $_SESSION['pedido_concluido'] = [
            'numero_pedido' => $numero_pedido,
            'total' => $totais['total'],
            'data' => date('d/m/Y H:i'),
            'cliente' => $nome_cliente
        ];
        
        // Limpar carrinho da sessão
        unset($_SESSION['carrinho_checkout']);
        unset($_SESSION['totais_checkout']);
        
        // Redirecionar para página de confirmação
        header('Location: confirmacao.php');
        exit();
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao processar pedido: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Processando...</title>
</head>
<body>
    <?php if (isset($erro)): ?>
        <div class="alert alert-danger"><?php echo $erro; ?></div>
        <a href="checkout.php">Voltar ao checkout</a>
    <?php endif; ?>
</body>
</html>