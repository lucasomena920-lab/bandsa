<?php
session_start();
require __DIR__ . '/../includes/config.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$username || !$password) $errors[] = 'Preencha todos os campos.';
    else {
        $stmt = $pdo->prepare('SELECT id, username, password, role FROM users WHERE username = :u OR email = :u LIMIT 1');
        $stmt->execute([':u'=>$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = ['id'=>$user['id'],'username'=>$user['username'],'role'=>$user['role']];
            if ($user['role'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: profile.php');
            }
            exit;
        } else {
            $errors[] = 'Credenciais inválidas.';
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Entrar • Eclipse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(33, 37, 41, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: slideIn 0.6s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .login-header {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            padding: 2rem;
            text-align: center;
            position: relative;
        }
        .login-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.1;
        }
        .login-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        .form-control {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            color: white;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            background: rgba(0, 0, 0, 0.5);
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
            color: white;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .btn-login {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-login:hover::before {
            left: 100%;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        .alert {
            border-radius: 8px;
            border: none;
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        .form-floating > label {
            color: rgba(255, 255, 255, 0.7);
        }
        .login-footer {
            background: rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            padding: 1.5rem;
        }
        .login-footer a {
            color: #dc3545;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .login-footer a:hover {
            color: #ff6b7a;
        }
        .input-group-text {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.6);
        }
    </style>
</head>
<body class="bg-black text-white">
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6 col-lg-5">
                    <div class="login-card">
                        <div class="login-header">
                            <i class="fas fa-user-circle login-icon"></i>
                            <h2 class="mb-0 fw-bold">Bem-vindo de Volta</h2>
                            <p class="mb-0 opacity-75">Entre na sua conta Eclipse</p>
                        </div>

                        <div class="card-body p-4">
                            <?php if ($errors): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?=htmlspecialchars(implode(' ', $errors))?>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>

                            <form method="post" id="loginForm">
                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input type="text" name="username" class="form-control" id="username"
                                               placeholder="Nome de utilizador ou email"
                                               value="<?=htmlspecialchars($_POST['username'] ?? '')?>" required>
                                        <label for="username">
                                            <i class="fas fa-user me-2"></i>Nome de Utilizador ou Email
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input type="password" name="password" class="form-control" id="password"
                                               placeholder="Senha" required>
                                        <label for="password">
                                            <i class="fas fa-lock me-2"></i>Senha
                                        </label>
                                    </div>
                                </div>

                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">
                                        Lembrar-me
                                    </label>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-login btn-lg" id="loginBtn">
                                        <i class="fas fa-sign-in-alt me-2"></i>Entrar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="login-footer">
                            <p class="mb-2">Não tem conta?</p>
                            <a href="../criar-conta.html" class="d-inline-block">
                                <i class="fas fa-user-plus me-1"></i>Criar Conta
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scroll to form when page loads with errors
        <?php if ($errors): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.login-card').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        });
        <?php endif; ?>

        // Enhanced form interactions
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            const originalText = btn.innerHTML;

            // Add loading state
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Entrando...';
            btn.disabled = true;

            // Re-enable after 3 seconds (in case of slow response)
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 3000);
        });

        // Password visibility toggle (optional enhancement)
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const passwordLabel = document.querySelector('label[for="password"]');

            // Add eye icon to password field
            const eyeIcon = document.createElement('i');
            eyeIcon.className = 'fas fa-eye position-absolute';
            eyeIcon.style.right = '10px';
            eyeIcon.style.top = '50%';
            eyeIcon.style.transform = 'translateY(-50%)';
            eyeIcon.style.cursor = 'pointer';
            eyeIcon.style.color = 'rgba(255, 255, 255, 0.6)';

            passwordInput.parentNode.style.position = 'relative';
            passwordInput.parentNode.appendChild(eyeIcon);

            eyeIcon.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.className = 'fas fa-eye-slash position-absolute';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.className = 'fas fa-eye position-absolute';
                }
            });
        });

        // Add subtle animations on focus
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.style.transform = 'scale(1.02)';
                this.parentNode.style.transition = 'transform 0.3s ease';
            });

            input.addEventListener('blur', function() {
                this.parentNode.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
