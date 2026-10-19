<?php
require_once 'includes/config.php';

// Buscar produtos da base de dados
$stmt = $pdo->query("SELECT * FROM produtos WHERE stock > 0 ORDER BY tipo, data_evento DESC");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Guardar em sessão para usar no checkout
$_SESSION['produtos_disponiveis'] = $produtos;
?>
<!DOCTYPE html>
<html lang="pt-PT">

</html>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Seu carrinho de ingressos - [Nome da Banda] - Finalize sua compra de forma rápida e segura">
    <meta name="keywords" content="carrinho, ingressos, compra, checkout, pagamento, banda, concertos">
    <title>Carrinho de Ingressos | [Nome da Banda]</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .carrinho-hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: rgb(255, 255, 255);
            padding: 80px 0 60px;
            margin-bottom: 40px;
        }
        
        .carrinho-item {
            background: rgb(255, 255, 255);
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .carrinho-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 35px rgba(0,0,0,0.15);
        }
        
        .evento-badge {
            background: linear-gradient(45deg, #FF6B6B, #FF8E53);
            color: white;
            padding: 8px 15px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .price-tag {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2c3e50;
        }
        
        .quantity-controls .btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .resumo-card {
            background: rgb(255, 255, 255);
            border: none;
            color: black;
            border-radius: 20px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.1);
            position: relative;
            top: 100px;
        }
        
        .empty-cart-icon {
            font-size: 5rem;
            opacity: 0.7;
            margin-bottom: 20px;
        }
        
        .btn-checkout {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 40px;
        }
        
        .step {
            display: flex;
            align-items: center;
            color: #6c757d;
        }
        
        .step.active {
            color: #667eea;
            font-weight: 600;
        }
        
        .step-number {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: 600;
        }
        
        .step.active .step-number {
            background: #667eea;
            color: white;
        }
        
        .step-line {
            width: 80px;
            height: 2px;
            background: #e9ecef;
            margin: 0 15px;
        }
        
        .evento-image {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-right: 15px;
          }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <header class="bg-dark py-3 sticky-top">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="logo">
                        <a href="index.html">
                            <img src="imagens/Logotipo para banda.png" alt="Logo [Nome da Banda]" class="img-fluid" style="max-height: 80px;">
                        </a>
                    </div>
                </div>
                <div class="col-md-8">
                    <nav class="navbar navbar-expand-lg navbar-dark">
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item">
                                    <a class="nav-link" href="index.html">Início</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="sobre.html">Sobre</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="albuns.html">Álbuns</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="tour.html">Tour</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="contactos.html">Contactos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link position-relative carrinho-link" href="carrinho.php">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span id="carrinho-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="carrinho-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">Meu Carrinho</h1>
                    <p class="lead mb-0">Revise seus ingressos e finalize sua compra com segurança</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="hero-icon">
                        <i class="fas fa-ticket-alt fa-4x"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Indicador de Passos -->
    <div class="container">
        <div class="step-indicator">
            <div class="step active">
                <div class="step-number">1</div>
                <span>Carrinho</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-number">2</div>
                <span>Checkout</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-number">3</div>
                <span>Confirmação</span>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <main class="pb-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Carrinho Vazio -->
                    <div id="carrinho-vazio" class="text-center py-5">
                        <div class="empty-cart-icon">🛒</div>
                        <h3 class="fw-bold text-muted mb-3">Seu carrinho está vazio</h3>
                        <p class="text-muted mb-4">Descubra nossos incríveis concertos e viva experiências únicas</p>
                        <a href="tour.html" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-music me-2"></i>Explorar Concertoss
                        </a>
                    </div>

                    <!-- Carrinho com Itens -->
                    <div id="carrinho-com-itens" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0">Seus Ingressos</h4>
                            <span class="text-muted" id="total-itens">0 itens</span>
                        </div>
                        
                        <div id="carrinho-itens-lista">
                            <!-- Itens serão inseridos aqui pelo JavaScript -->
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="card carrinho-item mt-4">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">📋 Informações Importantes</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-clock text-primary me-3 fa-lg"></i>
                                            <div>
                                                <strong>Horário de Entrada</strong>
                                                <p class="mb-0 text-muted">Acesso 1h antes do espetáculo</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-id-card text-primary me-3 fa-lg"></i>
                                            <div>
                                                <strong>Documentação</strong>
                                                <p class="mb-0 text-muted">Leve documento de identificação</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-camera text-primary me-3 fa-lg"></i>
                                            <div>
                                                <strong>Fotografia</strong>
                                                <p class="mb-0 text-muted">Câmaras profissionais proibidas</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-wheelchair text-primary me-3 fa-lg"></i>
                                            <div>
                                                <strong>Acessibilidade</strong>
                                                <p class="mb-0 text-muted">Acesso para mobilidade reduzida</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<!-- Resumo do Pedido -->
<div class="col-lg-4">
    <div class="resumo-card">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Resumo do Pedido</h4>
            
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span id="resumo-subtotal" class="fw-semibold">€0,00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Taxa de serviço</span>
                    <span id="resumo-taxa" class="fw-semibold">€0,00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Descontos</span>
                    <span class="fw-semibold text-success">-€0,00</span>
                </div>
            </div>
            
            <hr>
            
            <div class="d-flex justify-content-between mb-4">
                <strong class="fs-5">Total</strong>
                <strong id="resumo-total" class="fs-5 text-primary">€0,00</strong>
            </div>
            
            <button class="btn btn-checkout w-100 mb-3" onclick="iniciarCheckout()">
                <i class="fas fa-lock me-2"></i>Finalizar Compra
            </button>
            
            <a href="tour.html" class="btn btn-outline-primary w-100">
                <i class="fas fa-plus me-2"></i>Adicionar Mais Ingressos
            </a>
            
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Compra 100% segura e protegida
                </small>
            </div>
        </div>
    </div>

    <!-- Garantias -->
     <div class="resumo-card mb-8"></div>
    <div class="card carrinho-item" style="margin-top: 120px !important;">
        <div class="card-body text-center py-8">    
            <div class="row">
                <div class="col-4">
                    <i class="fas fa-truck text-primary fa-2x mb-2"></i>
                    <p class="small mb-0">Entrega Imediata</p>
                </div>
                <div class="col-4">
                    <i class="fas fa-shield-alt text-success fa-2x mb-2"></i>
                    <p class="small mb-0">Compra Segura</p>
                </div>
                <div class="col-4">
                    <i class="fas fa-headset text-info fa-2x mb-2"></i>
                    <p class="small mb-0">Suporte 24/7</p>
                </div>
            </div>
        </div>
    </div>
</div>
    </main>

    <!-- Rodapé -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <img src="imagens/Logotipo para banda.png" alt="Eclipse Logo" class="img-fluid" width="90">
                    <p class="text-light">Vivendo a música, criando experiências inesquecíveis.</p>
                    <div class="social-icons">
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Contactos</h5>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i>info@banda.com</p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i>+351 123 456 789</p>
                    <p class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Lisboa, Portugal</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Suporte</h5>
                    <p class="mb-2">Dúvidas sobre ingressos?</p>
                    <p class="mb-2"><i class="fas fa-ticket-alt me-2"></i>bilheteira@banda.com</p>
                    <p class="mb-0"><i class="fas fa-clock me-2"></i>Atendimento 24 horas</p>
                </div>
            </div>
            <hr class="my-4 bg-light">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2023 Eclipse. Todos os direitos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Desenvolvido com <i class="fas fa-heart text-danger"></i> por <a href="#" class="text-white">RockTech</a></p>
                </div>
            
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript do Carrinho -->
    <script src="js/carrinho.js"></script>
    <script src="js/carrinho-pagina.js"></script>
    <script src="js/cart-to-checkout.js"></script>
   <script>
    // Adicionar evento ao botão de checkout
    document.addEventListener('DOMContentLoaded', function() {
        const checkoutBtn = document.querySelector('.btn-checkout');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', iniciarCheckout);
        }
        
        // Simular dados de teste (remova quando tiver dados reais)
        if (!localStorage.getItem('carrinhoItens')) {
            const dadosTeste = [
                {
                    id: 1,
                    nome: "Concerto em Lisboa",
                    preco: 45.00,
                    quantidade: 2,
                    imagem: "imagens/concerto1.jpg",
                    data: "15/12/2024",
                    local: "Coliseu de Lisboa"
                },
                {
                    id: 2,
                    nome: "VIP Backstage",
                    preco: 120.00,
                    quantidade: 1,
                    imagem: "imagens/vip.jpg",
                    data: "15/12/2024",
                    local: "Coliseu de Lisboa"
                }
            ];
            localStorage.setItem('carrinhoItens', JSON.stringify(dadosTeste));
            
            // Atualizar a página
            atualizarResumo();
        }
    });
    
    function atualizarResumo() {
        const cartItems = JSON.parse(localStorage.getItem('carrinhoItens') || '[]');
        
        if (cartItems.length > 0) {
            // Mostrar carrinho com itens
            document.getElementById('carrinho-vazio').classList.add('d-none');
            document.getElementById('carrinho-com-itens').classList.remove('d-none');
            
            // Atualizar contador
            document.getElementById('carrinho-count').textContent = cartItems.length;
            document.getElementById('total-itens').textContent = `${cartItems.length} itens`;
            
            // Calcular totais
            let subtotal = 0;
            const listaItens = document.getElementById('carrinho-itens-lista');
            listaItens.innerHTML = '';
            
            cartItems.forEach((item, index) => {
                subtotal += item.preco * item.quantidade;
                
                // Criar item na lista
                listaItens.innerHTML += `
                    <div class="card carrinho-item mb-3">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center">
                                        <div class="evento-image me-3">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div>
                                            <h5 class="fw-bold mb-1">${item.nome}</h5>
                                            <p class="text-muted mb-1 small">
                                                <i class="fas fa-calendar me-1"></i>${item.data || 'Data não especificada'}
                                                <i class="fas fa-map-marker-alt ms-3 me-1"></i>${item.local || 'Local não especificado'}
                                            </p>
                                            <div class="evento-badge d-inline-block">Ingresso Digital</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="quantity-controls d-flex align-items-center">
                                            <button class="btn btn-outline-secondary me-2" onclick="alterarQuantidade(${index}, -1)">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="quantity-value mx-2 fw-bold">${item.quantidade}</span>
                                            <button class="btn btn-outline-secondary ms-2" onclick="alterarQuantidade(${index}, 1)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="text-end">
                                            <div class="price-tag">€${(item.preco * item.quantidade).toFixed(2)}</div>
                                            <small class="text-muted">€${item.preco.toFixed(2)} cada</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            const taxa = subtotal * 0.10; // 10% de taxa
            const total = subtotal + taxa;
            
            // Atualizar resumo
            document.getElementById('resumo-subtotal').textContent = `€${subtotal.toFixed(2).replace('.', ',')}`;
            document.getElementById('resumo-taxa').textContent = `€${taxa.toFixed(2).replace('.', ',')}`;
            document.getElementById('resumo-total').textContent = `€${total.toFixed(2).replace('.', ',')}`;
            
            // Atualizar botão
            const checkoutBtn = document.querySelector('.btn-checkout');
            checkoutBtn.innerHTML = `<i class="fas fa-lock me-2"></i>Finalizar Compra - €${total.toFixed(2).replace('.', ',')}`;
        }
    }
    
    function alterarQuantidade(index, mudanca) {
        const cartItems = JSON.parse(localStorage.getItem('carrinhoItens') || '[]');
        
        if (cartItems[index]) {
            cartItems[index].quantidade += mudanca;
            
            // Remover se quantidade for 0 ou menos
            if (cartItems[index].quantidade <= 0) {
                cartItems.splice(index, 1);
            }
            
            localStorage.setItem('carrinhoItens', JSON.stringify(cartItems));
            atualizarResumo();
        }
    }
</script>
 <script>
    // Dados dos produtos da BD para o JavaScript
    const produtosBD = <?php echo json_encode($produtos); ?>;
    
    // Carrinho inicial
    let carrinhoItens = JSON.parse(localStorage.getItem('carrinhoItens')) || [];

    function adicionarAoCarrinho(produtoId) {
        const produto = produtosBD.find(p => p.id == produtoId);
        if (!produto) return;

        const itemExistente = carrinhoItens.find(item => item.id == produtoId);
        
        if (itemExistente) {
            itemExistente.quantidade++;
        } else {
            carrinhoItens.push({
                id: produto.id,
                nome: produto.nome,
                preco: parseFloat(produto.preco),
                quantidade: 1,
                descricao: produto.descricao,
                imagem: produto.imagem,
                data_evento: produto.data_evento,
                local_evento: produto.local_evento
            });
        }
        
        localStorage.setItem('carrinhoItens', JSON.stringify(carrinhoItens));
        atualizarCarrinho();
        atualizarResumo();
    }

    function iniciarCheckout() {
        if (carrinhoItens.length === 0) {
            alert('Seu carrinho está vazio!');
            return;
        }

        // Criar formulário para enviar para o checkout.php
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'checkout.php';
        form.style.display = 'none';

        // Adicionar dados como JSON
        addHiddenField(form, 'carrinho_json', JSON.stringify(carrinhoItens));
        
        document.body.appendChild(form);
        form.submit();
    }

    // Resto do teu JavaScript atual...
    </script>
</body>
</html>