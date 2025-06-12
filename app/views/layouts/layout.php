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
    <style>
        :root {
            --chc-gold: #FFD700;
            --chc-turquoise: #40E0D0;
            --chc-orange: #FFA500;
            --chc-bg: #f5f7fa;
            --chc-card: #ffffff;
            --chc-border: #dee2e6;
            --chc-dark: #2c3e50;
            --chc-gradient: linear-gradient(135deg, var(--chc-gold), var(--chc-orange));
        }

        body {
            background-color: var(--chc-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 80px;
        }

        /* Enhanced Navbar Styling */
        .navbar-chc {
            background: var(--chc-gradient) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            border-bottom: 3px solid var(--chc-turquoise);
            transition: all 0.3s ease;
            min-height: 80px;
        }

        .navbar-chc.scrolled {
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
            min-height: 70px;
        }

        .navbar-brand {
            font-weight: 900;
            color: var(--chc-dark) !important;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
            color: var(--chc-dark) !important;
        }

        .navbar-brand img {
            transition: transform 0.3s ease;
            filter: drop-shadow(2px 2px 4px rgba(0,0,0,0.2));
        }

        .navbar-brand:hover img {
            transform: rotate(5deg) scale(1.1);
        }

        .brand-text {
            margin-left: 12px;
            font-weight: 900;
            background: linear-gradient(45deg, var(--chc-dark), var(--chc-turquoise));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Navigation Links */
        .navbar-nav .nav-link {
            color: var(--chc-dark) !important;
            font-weight: 600;
            font-size: 1rem;
            padding: 12px 20px !important;
            margin: 0 5px;
            border-radius: 25px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .navbar-nav .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .navbar-nav .nav-link:hover::before {
            left: 100%;
        }

        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: var(--chc-dark) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .navbar-nav .nav-link.active {
            background-color: var(--chc-turquoise);
            color: white !important;
            box-shadow: 0 4px 12px rgba(64, 224, 208, 0.3);
        }

        /* Profile Dropdown */
        .profile-dropdown .nav-link {
            background: linear-gradient(135deg, var(--chc-turquoise), #20c997);
            color: white !important;
            border-radius: 30px;
            padding: 8px 16px !important;
            box-shadow: 0 4px 12px rgba(64, 224, 208, 0.3);
        }

        .profile-dropdown .nav-link:hover {
            background: linear-gradient(135deg, #20c997, var(--chc-turquoise));
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(64, 224, 208, 0.4);
        }

        .profile-icon {
            width: 35px;
            height: 35px;
            background: linear-gradient(135deg, var(--chc-gold), var(--chc-orange));
            color: var(--chc-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: bold;
            margin-right: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .profile-icon:hover {
            transform: rotate(360deg);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background: white;
            margin-top: 10px;
            overflow: hidden;
        }

        .dropdown-item {
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .dropdown-item:hover {
            background: linear-gradient(90deg, var(--chc-gold), var(--chc-orange));
            color: var(--chc-dark);
            border-left-color: var(--chc-turquoise);
            transform: translateX(5px);
        }

        .dropdown-divider {
            margin: 0;
            border-color: var(--chc-border);
        }

        /* Mobile Menu Toggle */
        .navbar-toggler {
            border: 2px solid var(--chc-dark);
            border-radius: 8px;
            padding: 8px 12px;
            transition: all 0.3s ease;
        }

        .navbar-toggler:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2844, 62, 80, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Responsive Design */
        @media (max-width: 991.98px) {
            .navbar-nav {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 15px;
                margin-top: 15px;
                padding: 15px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            }

            .navbar-nav .nav-link {
                margin: 5px 0;
                text-align: center;
            }

            .brand-text {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .navbar-brand img {
                height: 45px;
            }
        }

        .card {
            border: 1px solid var(--chc-border);
            box-shadow: 0 0.1rem 0.5rem rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .card-header {
            background-color: var(--chc-card);
            border-bottom: 1px solid var(--chc-border);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--chc-gold);
            border-color: var(--chc-gold);
            color: #000;
            font-weight: bold;
        }

        .pagination .page-link {
            color: var(--chc-gold);
            border-color: var(--chc-gold);
        }

        .pagination .page-link:hover {
            background-color: var(--chc-gold);
            color: #fff;
        }

        .btn-chc-gold {
            background-color: var(--chc-gold);
            color: #000;
            font-weight: bold;
            border: none;
        }

        .btn-chc-gold:hover {
            background-color: #ffd000;
            color: #000;
        }

        .btn-chc-turquoise {
            background-color: var(--chc-turquoise);
            color: #fff;
            font-weight: bold;
            border: none;
        }

        .btn-chc-turquoise:hover {
            background-color: #33d0c0;
            color: #fff;
        }

        .btn-chc-orange {
            background-color: var(--chc-orange);
            color: #fff;
            font-weight: bold;
            border: none;
        }

        .btn-chc-orange:hover {
            background-color: #ff8c00;
            color: #fff;
        }

        h1.display-6 {
            color: #333;
            font-weight: bold;
        }

        .lead.text-muted {
            color: #666 !important;
        }

        .container.py-5.mt-5 {
            margin-top: 70px;
        }

        .jumbotron {
            padding: 3rem 2rem;
            margin-bottom: 3rem;
            background-color: var(--chc-gold);
            border-radius: 1.5rem;
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .logo-wrapper {
            animation: floatEffect 3s ease-in-out infinite;
        }
        .logo-img {
            transition: transform 0.3s ease-in-out;
        }
        .logo-img:hover {
            transform: scale(1.05) rotate(2deg);
        }

        .logo-shine {
            pointer-events: none;
            background: radial-gradient(circle at center, rgba(255,255,255,0.4), transparent 70%);
            animation: shine 6s linear infinite;
            z-index: 1;
        }

        @keyframes shine {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes floatEffect {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }
        
        .display-2 {
            font-size: 3rem;
            line-height: 1.2;
        }

        @media (max-width: 768px) {
            .jumbotron .display-2 {
                font-size: 2.2rem;
            }

            .jumbotron .lead {
                font-size: 1.2rem;
            }

            .logo-wrapper {
                width: 140px !important;
                height: 140px !important;
            }
        }
    </style>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>