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
    <style>
        :root {
            --chc-gold: #FFD700;
            --chc-turquoise: #40E0D0;
            --chc-orange: #FFA500;
            --chc-bg: #f5f7fa;
            --chc-card: #ffffff;
            --chc-border: #dee2e6;
        }

        body {
            background-color: var(--chc-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-dark.bg-chc {
            background-color: var(--chc-gold) !important;
        }

        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
            font-size: 1.3rem;
        }

        .nav-link {
            color: #333333 !important;
            font-weight: bold;
        }

        .nav-link:hover {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            background-color: var(--chc-turquoise);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            font-weight: bold;
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
<nav class="navbar navbar-expand-lg navbar-dark bg-chc fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/"><img src="/assets/img/logo.png" alt="Chestercoin Logo" height="60"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <?php if (!$publicHash): ?>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/new-wallet">Nova Carteira</a></li>
                    <li class="nav-item"><a class="nav-link" href="/import">Importar</a></li>
                </ul>
            <?php else: ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link d-flex align-items-center" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="profile-icon me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36"><path fill="#ffcc4d" d="M36 18c0 9.941-8.059 18-18 18c-9.94 0-18-8.059-18-18C0 8.06 8.06 0 18 0c9.941 0 18 8.06 18 18"/><path fill="#664500" d="M7 21.263c0 3.964 4.596 9 11 9s11-5 11-9c0 0-10.333 2.756-22 0"/><path fill="#5d9040" d="M12 23.003v6.961c0 3 2 6 6 6s6-3 6-6v-6.92c-6.291 1.292-12-.041-12-.041"/><path fill="#664500" d="M12.284 12.33c-.756-.292-1.471-.568-1.471-.968c0-.659.884-.693 1.061-.693c.625 0 .936.234 1.21.441c.21.159.428.323.731.323c.564 0 .821-.397.821-.766c0-.736-.902-1.256-1.858-1.474V8.8a.94.94 0 0 0-1.878 0v.426c-1.144.333-1.845 1.174-1.845 2.235c0 1.311 1.293 1.849 2.434 2.323c.817.34 1.589.661 1.589 1.162c0 .366-.46.762-1.203.762c-.75 0-1.18-.3-1.56-.564c-.262-.183-.51-.356-.806-.356c-.43 0-.766.362-.766.824c0 .679.931 1.356 2.157 1.594v.47a.939.939 0 0 0 1.878 0l-.005-.498c1.296-.315 2.062-1.222 2.062-2.459c.001-1.404-1.414-1.95-2.551-2.389m14.41 2.406c0-1.404-1.415-1.95-2.552-2.389c-.756-.292-1.47-.568-1.47-.968c0-.659.884-.693 1.061-.693c.625 0 .935.234 1.21.441c.211.159.428.323.73.323c.565 0 .822-.397.822-.766c0-.737-.902-1.256-1.858-1.474v-.392a.94.94 0 0 0-1.879 0v.426c-1.143.333-1.845 1.174-1.845 2.235c0 1.311 1.293 1.849 2.434 2.323c.817.34 1.589.661 1.589 1.162c0 .366-.459.762-1.202.762c-.75 0-1.18-.3-1.559-.564c-.263-.183-.511-.356-.806-.356c-.431 0-.767.362-.767.824c0 .679.931 1.356 2.156 1.594v.47a.939.939 0 0 0 1.879 0l-.003-.498c1.295-.317 2.06-1.223 2.06-2.46"/><path fill="#fff" d="M20.97 31.069c0-1.368-1.379-1.901-2.487-2.329c-.737-.284-1.434-.554-1.434-.943c0-.643.861-.675 1.034-.675c.609 0 .912.229 1.18.43c.205.155.418.315.713.315c.55 0 .801-.388.801-.747c0-.717-.879-1.224-1.812-1.437V25.3a.917.917 0 0 0-1.832 0v.415c-1.115.324-1.799 1.145-1.799 2.179c0 1.278 1.261 1.803 2.373 2.265c.797.331 1.549.644 1.549 1.132c0 .357-.448.743-1.173.743c-.731 0-1.15-.292-1.52-.551c-.255-.178-.497-.347-.785-.347c-.419 0-.748.354-.748.804c0 .663.907 1.322 2.102 1.554v.459a.916.916 0 0 0 1.832 0l-.003-.486c1.263-.309 2.009-1.193 2.009-2.398"/></svg>
                            </span> Minha Carteira
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/wallet">Meu dinheiro</a></li>
                            <li><a class="dropdown-item" href="/wallet?export=1">Exportar Carteira</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="/logout">Sair</a></li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container py-5 mt-5">
    <?= $content ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>