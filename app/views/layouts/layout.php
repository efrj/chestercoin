<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$publicHash = $_SESSION['public_hash'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Chestercoin - CHC' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-chc fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/assets/img/logo.png" alt="Chestercoin Logo" height="60">
            <span class="brand-text">Chestercoin</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <?php if (!$publicHash): ?>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/about' ? 'active' : '' ?>" href="/about">
                            <i class="fas fa-info-circle me-1"></i> Quem Somos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/login' ? 'active' : '' ?>" href="/login">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/new-wallet' ? 'active' : '' ?>" href="/new-wallet">
                            <i class="fas fa-wallet me-1"></i> Nova Carteira
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/import' ? 'active' : '' ?>" href="/import">
                            <i class="fas fa-file-import me-1"></i> Importar
                        </a>
                    </li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= $_SERVER['REQUEST_URI'] === '/about' ? 'active' : '' ?>" href="/about">
                            <i class="fas fa-info-circle me-1"></i> Quem Somos
                        </a>
                    </li>
                    <li class="nav-item dropdown profile-dropdown">
                        <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="profile-icon">
                                <i class="fas fa-coins"></i>
                            </span>
                            <span>Minha Carteira</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="/wallet">
                                    <i class="fas fa-wallet me-2"></i> Meu dinheiro
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/wallet?export=1">
                                    <i class="fas fa-file-export me-2"></i> Exportar Carteira
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="/logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
// Add scroll effect to navbar
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar-chc');
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});
</script>

<div class="container py-5 mt-5">
    <?= $content ?>
</div>

<footer class="footer mt-auto py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 mb-4">
                <div class="footer-brand">
                    <img src="/assets/img/logo.png" alt="Chestercoin Logo" height="50" class="mb-3">
                    <h5 class="footer-title">Chestercoin (CHC)</h5>
                    <p class="footer-description">
                        Uma plataforma educacional para entender blockchain e criptomoedas através da prática.
                    </p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="footer-header">Navegação</h6>
                <ul class="footer-links">
                    <li><a href="/about">Quem Somos</a></li>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/new-wallet">Nova Carteira</a></li>
                    <li><a href="/import">Importar</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="footer-header">Recursos</h6>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-book me-2"></i>Documentação</a></li>
                    <li><a href="#"><i class="fas fa-graduation-cap me-2"></i>Tutorial</a></li>
                    <li><a href="#"><i class="fas fa-question-circle me-2"></i>FAQ</a></li>
                    <li><a href="#"><i class="fas fa-code me-2"></i>API</a></li>
                </ul>
            </div>
            
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-header">Conecte-se</h6>
                <div class="footer-social">
                    <a href="#" class="social-link">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-discord"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-telegram"></i>
                    </a>
                </div>
                <div class="footer-newsletter mt-4">
                    <h6 class="footer-header">Newsletter</h6>
                    <p class="small">Receba as últimas novidades e atualizações</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Seu e-mail">
                        <button class="btn btn-chc-gold" type="button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="footer-divider">
        
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="copyright mb-0">
                        © <?= date('Y') ?> Chestercoin. Todos os direitos reservados.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="footer-legal">
                        <a href="#">Termos de Uso</a>
                        <span class="separator">|</span>
                        <a href="#">Privacidade</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>