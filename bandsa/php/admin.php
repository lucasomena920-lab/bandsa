tas<?php
session_start();
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/../includes/config.php';

// Fetch data with proper queries
$users = $pdo->query('SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC')->fetchAll();
$orders = $pdo->query('SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.order_date DESC LIMIT 20')->fetchAll();
$products = $pdo->query('SELECT * FROM produtos ORDER BY created_at DESC')->fetchAll();

// Statistics
$total_users = $pdo->query('SELECT COUNT(*) as count FROM users')->fetch()['count'];
$total_orders = $pdo->query('SELECT COUNT(*) as count FROM orders')->fetch()['count'];
$total_products = $pdo->query('SELECT COUNT(*) as count FROM produtos')->fetch()['count'];
$total_revenue = $pdo->query('SELECT SUM(total) as revenue FROM orders WHERE status = "completed"')->fetch()['revenue'] ?? 0;
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Painel Admin • Eclipse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        }
        .admin-header {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            box-shadow: 0 4px 20px rgba(220, 53, 69, 0.3);
        }
        .stat-card {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            border: 2px solid #dc3545;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(220, 53, 69, 0.4);
        }
        .stat-number {
            font-family: 'Bebas Neue', cursive;
            font-size: 3rem;
            color: #ffffff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .stat-label {
            color: #cccccc;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .nav-tabs .nav-link {
            color: #dc3545;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .nav-tabs .nav-link:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b7a;
        }
        .card {
            background: linear-gradient(135deg, #2d2d2d 0%, #1a1a1a 100%);
            border: 2px solid #dc3545;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }
        .card-header {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .table {
            color: #ffffff;
        }
        .table thead th {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }
        .table tbody tr {
            transition: background 0.3s ease;
        }
        .table tbody tr:hover {
            background: rgba(220, 53, 69, 0.1);
        }
        .badge {
            font-weight: 600;
            padding: 0.5em 0.75em;
        }
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
            transition: all 0.3s ease;
        }
        .btn-outline-danger:hover {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            border-color: #dc3545;
            color: white;
            transform: scale(1.05);
        }
        .text-danger {
            color: #ff6b7a !important;
        }
    </style>
</head>
<body class="bg-black text-white">
    <!-- Header -->
    <header class="bg-dark py-3 border-bottom border-danger">
        <div class="container d-flex align-items-center">
            <h5 class="mb-0 text-danger">
                <i class="fas fa-cog me-2"></i>Painel Administrativo
            </h5>
            <nav class="ms-auto">
                <a href="../index.html" class="text-white me-3">
                    <i class="fas fa-home"></i> Site
                </a>
                <a href="profile.php" class="text-white me-3">
                    <i class="fas fa-user"></i> Perfil
                </a>
                <a href="logout.php" class="text-white">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </nav>
        </div>
    </header>

    <main class="py-4">
        <div class="container-fluid">
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card stat-card text-center">
                        <div class="card-body">
                            <i class="fas fa-users fa-2x text-danger mb-3"></i>
                            <h3 class="stat-number"><?=$total_users?></h3>
                            <p class="stat-label mb-0">Utilizadores</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card text-center">
                        <div class="card-body">
                            <i class="fas fa-shopping-cart fa-2x text-danger mb-3"></i>
                            <h3 class="stat-number"><?=$total_orders?></h3>
                            <p class="stat-label mb-0">Encomendas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card text-center">
                        <div class="card-body">
                            <i class="fas fa-music fa-2x text-danger mb-3"></i>
                            <h3 class="stat-number"><?=$total_products?></h3>
                            <p class="stat-label mb-0">Produtos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stat-card text-center">
                        <div class="card-body">
                            <i class="fas fa-euro-sign fa-2x text-danger mb-3"></i>
                            <h3 class="stat-number">€<?=number_format($total_revenue, 2)?></h3>
                            <p class="stat-label mb-0">Receita Total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4" id="adminTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active text-danger" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>Utilizadores
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-danger" id="orders-tab" data-bs-toggle="tab" data-bs-target="#orders" type="button" role="tab">
                        <i class="fas fa-shopping-cart me-2"></i>Encomendas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link text-danger" id="products-tab" data-bs-toggle="tab" data-bs-target="#products" type="button" role="tab">
                        <i class="fas fa-music me-2"></i>Produtos
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="adminTabContent">
                <!-- Users Tab -->
                <div class="tab-pane fade show active" id="users" role="tabpanel">
                    <div class="card bg-dark border-danger">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users me-2"></i>Gestão de Utilizadores</span>
                            <span class="badge bg-dark"><?=count($users)?> total</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag"></i> ID</th>
                                            <th><i class="fas fa-user"></i> Utilizador</th>
                                            <th><i class="fas fa-envelope"></i> Email</th>
                                            <th><i class="fas fa-crown"></i> Função</th>
                                            <th><i class="fas fa-calendar"></i> Registo</th>
                                            <th><i class="fas fa-cogs"></i> Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?=$user['id']?></td>
                                                <td><?=$user['username']?></td>
                                                <td><?=$user['email']?></td>
                                                <td>
                                                    <span class="badge bg-<?=$user['role'] === 'admin' ? 'danger' : 'secondary'?>">
                                                        <?=ucfirst($user['role'])?>
                                                    </span>
                                                </td>
                                                <td><?=date('d/m/Y', strtotime($user['created_at']))?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger me-1" data-target="#editUserModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" data-action="delete" data-type="user" data-id="<?=$user['id']?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Tab -->
                <div class="tab-pane fade" id="orders" role="tabpanel">
                    <div class="card bg-dark border-danger">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-shopping-cart me-2"></i>Gestão de Encomendas</span>
                            <span class="badge bg-dark"><?=count($orders)?> recentes</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag"></i> ID</th>
                                            <th><i class="fas fa-user"></i> Cliente</th>
                                            <th><i class="fas fa-euro-sign"></i> Total</th>
                                            <th><i class="fas fa-calendar"></i> Data</th>
                                            <th><i class="fas fa-info-circle"></i> Status</th>
                                            <th><i class="fas fa-cogs"></i> Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($orders as $order): ?>
                                            <tr>
                                                <td>#<?=$order['id']?></td>
                                                <td><?=$order['username']?></td>
                                                <td>€<?=number_format($order['total'], 2)?></td>
                                                <td><?=date('d/m/Y H:i', strtotime($order['order_date']))?></td>
                                                <td>
                                                    <span class="badge bg-<?=match($order['status']) {
                                                        'completed' => 'success',
                                                        'pending' => 'warning',
                                                        'cancelled' => 'danger',
                                                        default => 'secondary'
                                                    }?>">
                                                        <?=ucfirst($order['status'])?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger me-1">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" data-target="#editOrderModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Tab -->
                <div class="tab-pane fade" id="products" role="tabpanel">
                    <div class="card bg-dark border-danger">
                        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-music me-2"></i>Gestão de Produtos</span>
                            <span class="badge bg-dark"><?=count($products)?> total</span>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th><i class="fas fa-hashtag"></i> ID</th>
                                            <th><i class="fas fa-music"></i> Nome</th>
                                            <th><i class="fas fa-euro-sign"></i> Preço</th>
                                            <th><i class="fas fa-boxes"></i> Stock</th>
                                            <th><i class="fas fa-tag"></i> Tipo</th>
                                            <th><i class="fas fa-cogs"></i> Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><?=$product['id']?></td>
                                                <td><?=$product['nome']?></td>
                                                <td>€<?=number_format($product['preco'], 2)?></td>
                                                <td>
                                                    <span class="badge bg-<?=$product['stock'] > 10 ? 'success' : ($product['stock'] > 0 ? 'warning' : 'danger')?>">
                                                        <?=$product['stock']?>
                                                    </span>
                                                </td>
                                                <td><?=$product['tipo']?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-danger me-1" data-target="#editProductModal">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" data-action="delete" data-type="product" data-id="<?=$product['id']?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modals for Editing -->
    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Utilizador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editUserForm">
                    <div class="modal-body">
                        <input type="hidden" id="editUserId" name="id">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Utilizador</label>
                            <input type="text" class="form-control bg-black text-white border-secondary" id="editUsername" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control bg-black text-white border-secondary" id="editEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRole" class="form-label">Função</label>
                            <select class="form-control bg-black text-white border-secondary" id="editRole" name="role">
                                <option value="user">Utilizador</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Produto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editProductForm">
                    <div class="modal-body">
                        <input type="hidden" id="editProductId" name="id">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Nome</label>
                            <input type="text" class="form-control bg-black text-white border-secondary" id="editProductName" name="nome" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Preço</label>
                            <input type="number" step="0.01" class="form-control bg-black text-white border-secondary" id="editProductPrice" name="preco" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductStock" class="form-label">Stock</label>
                            <input type="number" class="form-control bg-black text-white border-secondary" id="editProductStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductType" class="form-label">Tipo</label>
                            <input type="text" class="form-control bg-black text-white border-secondary" id="editProductType" name="tipo" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Order Modal -->
    <div class="modal fade" id="editOrderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white border-danger">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Editar Encomenda</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editOrderForm">
                    <div class="modal-body">
                        <input type="hidden" id="editOrderId" name="id">
                        <div class="mb-3">
                            <label for="editOrderStatus" class="form-label">Status</label>
                            <select class="form-control bg-black text-white border-secondary" id="editOrderStatus" name="status">
                                <option value="pending">Pendente</option>
                                <option value="completed">Processada</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Edit User
        document.querySelectorAll('.btn-outline-danger[data-target="#editUserModal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                document.getElementById('editUserId').value = row.cells[0].textContent;
                document.getElementById('editUsername').value = row.cells[1].textContent;
                document.getElementById('editEmail').value = row.cells[2].textContent;
                document.getElementById('editRole').value = row.cells[3].textContent.toLowerCase();
                new bootstrap.Modal(document.getElementById('editUserModal')).show();
            });
        });

        // Edit Product
        document.querySelectorAll('.btn-outline-danger[data-target="#editProductModal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                document.getElementById('editProductId').value = row.cells[0].textContent;
                document.getElementById('editProductName').value = row.cells[1].textContent;
                document.getElementById('editProductPrice').value = parseFloat(row.cells[2].textContent.replace('€', ''));
                document.getElementById('editProductStock').value = row.cells[3].textContent;
                document.getElementById('editProductType').value = row.cells[4].textContent;
                new bootstrap.Modal(document.getElementById('editProductModal')).show();
            });
        });

        // Edit Order
        document.querySelectorAll('.btn-outline-danger[data-target="#editOrderModal"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                document.getElementById('editOrderId').value = row.cells[0].textContent.replace('#', '');
                document.getElementById('editOrderStatus').value = row.cells[4].textContent.toLowerCase();
                new bootstrap.Modal(document.getElementById('editOrderModal')).show();
            });
        });

        // Delete actions
        document.querySelectorAll('.btn-outline-danger[data-action="delete"]').forEach(btn => {
            btn.addEventListener('click', function() {
                if (confirm('Tem certeza que deseja excluir este item?')) {
                    const type = this.dataset.type;
                    const id = this.dataset.id;
                    // Implement delete logic here
                    console.log('Delete', type, id);
                }
            });
        });

        // Form submissions
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Implement update user logic
            console.log('Update user');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
        });

        document.getElementById('editProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Implement update product logic
            console.log('Update product');
            bootstrap.Modal.getInstance(document.getElementById('editProductModal')).hide();
        });

        document.getElementById('editOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Implement update order logic
            console.log('Update order');
            bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
        });
    </script>
</body>
</html>
