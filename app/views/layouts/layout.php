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
        <!-- Logo Section -->
        <div class="row mb-4">
            <div class="col-12 text-center">
                <a class="logo-footer" href="/">
                    <img src="/assets/img/logo.png" alt="Chestercoin" height="60" class="mb-3">
                </a>
            </div>
        </div>

        <div class="row footer-row">
            <!-- Donation Section -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="donate">
                    <div class="donate-row">
                        <span class="footer-title">Apoie o Chestercoin:</span>
                        <button onclick="alert('Funcionalidade em desenvolvimento')" class="donate-btn btn btn-chc-gold mt-2 mb-3">
                            <i class="fas fa-heart me-2"></i>Doar
                        </button>
                        <p class="donate-text">
                            <small class="text-muted">Ajude a manter este projeto educacional</small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Introduction Menu -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footermenu-item footermenu-introduction">
                    <p class="footer-title">Introdução:</p>
                    <ul class="footermenu-list footer-links">
                        <li><a href="/about">Para Iniciantes</a></li>
                        <li><a href="/new-wallet">Como Começar</a></li>
                        <li><a href="/login">Acessar Carteira</a></li>
                        <li><a href="/import">Importar Carteira</a></li>
                        <li><a href="#">Como Funciona</a></li>
                        <li><a href="#">O que Precisa Saber</a></li>
                    </ul>
                </div>
            </div>

            <!-- Resources Menu -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footermenu-item footermenu-resources">
                    <p class="footer-title">Recursos:</p>
                    <ul class="footermenu-list footer-links">
                        <li><a href="#">Documentação</a></li>
                        <li><a href="#">Tutorial</a></li>
                        <li><a href="#">Comunidade</a></li>
                        <li><a href="#">Vocabulário</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">API</a></li>
                    </ul>
                </div>
            </div>

            <!-- Participate Menu -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="footermenu-item footermenu-participate">
                    <p class="footer-title">Participar:</p>
                    <ul class="footermenu-list footer-links">
                        <li><a href="#">Apoiar Chestercoin</a></li>
                        <li><a href="/new-wallet">Criar Carteira</a></li>
                        <li><a href="/wallet">Gerenciar Moedas</a></li>
                        <li><a href="#">Desenvolvimento</a></li>
                        <li><a href="#">Contribuir</a></li>
                    </ul>
                    
                    <div class="footermenu-item footermenu-other mt-4">
                        <p class="footer-title">Outros:</p>
                        <div class="footer-links">
                            <a href="#" class="d-block mb-2">Evitar Golpes</a>
                            <a href="#" class="d-block mb-2">Legal</a>
                            <a href="#" class="d-block mb-2">Política de Privacidade</a>
                            <a href="#" class="d-block mb-2">Imprensa</a>
                            <a href="/about" class="d-block mb-2">Sobre o Chestercoin</a>
                            <a href="#" class="d-block">Blog</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="footer-social text-center">
                    <a href="#" class="social-link me-3">
                        <i class="fab fa-github"></i>
                    </a>
                    <a href="#" class="social-link me-3">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="social-link me-3">
                        <i class="fab fa-discord"></i>
                    </a>
                    <a href="#" class="social-link">
                        <i class="fab fa-telegram"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <hr class="footer-divider">
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="container">
                <div class="row footer-bottom-row align-items-center">
                    <div class="col-md-6">
                        <div class="footerlicense">
                            © Projeto Chestercoin <?= date('Y') ?> Lançado sob a 
                            <a href="http://opensource.org/licenses/mit-license.php" target="_blank" class="text-decoration-none">licença MIT</a>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="row footer-status-block justify-content-end align-items-center">
                            <div class="col-auto">
                                <a href="#" class="statusmenu text-decoration-none me-3">Status da Rede</a>
                            </div>
                            <div class="col-auto">
                                <div class="footer-langselect langselect">
                                    <select class="form-select form-select-sm" onchange="alert('Seleção de idioma em desenvolvimento')">
                                        <option value="pt-BR" selected>Português Brasil</option>
                                        <option value="en">English</option>
                                        <option value="es">Español</option>
                                        <option value="fr">Français</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>