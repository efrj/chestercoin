<?php $title = "Chestercoin (CHC) | Blockchain Público"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Hero Section -->
<div class="hero-section mb-5">
    <div class="hero-background">
        <div class="container">
            <div class="row align-items-center justify-content-center g-4 py-5">
                <div class="col-lg-4 text-center">
                    <div class="logo-container">
                        <div class="logo-wrapper position-relative mx-auto">
                            <img src="/assets/img/logo.png" alt="Logo Chestercoin" class="hero-logo">
                            <div class="logo-glow"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 text-center text-lg-start">
                    <h1 class="hero-title">
                        <span class="gradient-text">Chestercoin</span>
                        <span class="currency-badge">CHC</span>
                    </h1>
                    <p class="hero-subtitle">O futuro das transações descentralizadas</p>
                    <div class="hero-description">
                        <p class="lead">
                            Veja em tempo real todas as transações realizadas na rede Chestercoin.
                            Um sistema de blockchain simulado e educacional.
                        </p>
                        <p class="hero-tagline">
                            <i class="fas fa-rocket me-2"></i>
                            A moeda digital que nasceu para facilitar a compreensão da tecnologia blockchain
                        </p>
                    </div>
                    <div class="hero-actions mt-4">
                        <a href="/new-wallet" class="btn btn-hero-primary me-3">
                            <i class="fas fa-wallet me-2"></i>
                            Criar Carteira
                        </a>
                        <a href="/login" class="btn btn-hero-secondary">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Fazer Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-primary">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= number_format($totalTransactions) ?></h3>
                <p class="stat-label">Total de Transações</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-success">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= number_format($totalCoinsMoved) ?></h3>
                <p class="stat-label">CHC Movimentados</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-warning">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= number_format($totalWallets) ?></h3>
                <p class="stat-label">Carteiras Ativas</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon bg-gradient-info">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <h3 class="stat-number"><?= count($dailyFlow) ?></h3>
                <p class="stat-label">Dias Ativos</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mb-5">
    <div class="col-lg-6 mb-4">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-trophy me-2"></i>
                    Top 5 Maiores Saldos
                </h5>
            </div>
            <div class="chart-body">
                <canvas id="balanceChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title">
                    <i class="fas fa-chart-area me-2"></i>
                    Fluxo de Moedas por Dia
                </h5>
            </div>
            <div class="chart-body">
                <canvas id="coinFlowChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="filter-section mb-4">
    <div class="filter-card">
        <div class="filter-header">
            <h5 class="filter-title">
                <i class="fas fa-filter me-2"></i>
                Filtros de Busca
            </h5>
        </div>
        <div class="filter-body">
            <form method="get">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label for="hash_filter" class="form-label fw-bold">
                            <i class="fas fa-search me-1"></i>
                            Buscar por Hash
                        </label>
                        <input type="text" name="hash_filter" id="hash_filter" 
                               class="form-control form-control-lg" 
                               placeholder="Digite o hash da carteira"
                               value="<?= htmlspecialchars($hashFilter) ?>">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="date_filter" class="form-label fw-bold">
                            <i class="fas fa-calendar me-1"></i>
                            Filtrar por Data
                        </label>
                        <input type="date" name="date_filter" id="date_filter" 
                               class="form-control form-control-lg" 
                               value="<?= $dateFilter ?>">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <button type="submit" class="btn btn-chc-orange btn-lg w-100">
                            <i class="fas fa-search me-2"></i>
                            Filtrar
                        </button>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <a href="/" class="btn btn-outline-secondary btn-lg w-100">
                            <i class="fas fa-times me-2"></i>
                            Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Transactions Section -->
<div class="transactions-section">
    <div class="transactions-card">
        <div class="transactions-header">
            <h5 class="transactions-title">
                <i class="fas fa-list me-2"></i>
                Últimas Transações<?= $hashFilter || $dateFilter ? ' (filtradas)' : '' ?>
            </h5>
            <?php if ($hashFilter || $dateFilter): ?>
                <span class="badge bg-info">Filtros Ativos</span>
            <?php endif; ?>
        </div>
        <div class="transactions-body">
            <?php if (!empty($paged['data'])): ?>
                <div class="transactions-list">
                    <?php foreach ($paged['data'] as $index => $t): ?>
                        <div class="transaction-item">
                            <div class="transaction-icon">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="transaction-content">
                                <div class="transaction-addresses">
                                    <div class="address-from">
                                        <span class="address-label">De:</span>
                                        <span class="address-hash"><?= $t['from'] === 'genesis' ? 'Sistema (Genesis)' : htmlspecialchars(substr($t['from'], 0, 12)) . '...' ?></span>
                                    </div>
                                    <div class="address-to">
                                        <span class="address-label">Para:</span>
                                        <span class="address-hash"><?= htmlspecialchars(substr($t['to'], 0, 12)) ?>...</span>
                                    </div>
                                </div>
                                <div class="transaction-meta">
                                    <small class="transaction-time">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= date('d/m/Y H:i', strtotime($t['time'])) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="transaction-amount">
                                <span class="amount-value"><?= number_format($t['amount'], 2) ?></span>
                                <span class="amount-currency">CHC</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma transação encontrada</h5>
                    <p class="text-muted">Tente ajustar os filtros ou aguarde novas transações.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if (isset($paged['totalPages']) && $paged['totalPages'] > 1): ?>
    <div class="pagination-container mt-4">
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $paged['page'] <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $paged['page'] - 1 ?>&hash_filter=<?= urlencode($hashFilter) ?>&date_filter=<?= urlencode($dateFilter) ?>">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $paged['totalPages']; $i++): ?>
                    <li class="page-item <?= $i == $paged['page'] ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&hash_filter=<?= urlencode($hashFilter) ?>&date_filter=<?= urlencode($dateFilter) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $paged['page'] >= $paged['totalPages'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $paged['page'] + 1 ?>&hash_filter=<?= urlencode($hashFilter) ?>&date_filter=<?= urlencode($dateFilter) ?>">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
<?php endif; ?>

<script>
// Balance Chart
const ctx = document.getElementById('balanceChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: [
            <?php foreach ($topBalances as $hash => $balance): ?>
            "<?= substr($hash, 0, 8) ?>...",
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Saldo (CHC)',
            data: [
                <?php foreach ($topBalances as $balance): ?>
                <?= $balance ?>,
                <?php endforeach; ?>
            ],
            backgroundColor: [
                'rgba(255, 215, 0, 0.8)',
                'rgba(64, 224, 208, 0.8)',
                'rgba(255, 165, 0, 0.8)',
                'rgba(32, 201, 151, 0.8)',
                'rgba(255, 107, 53, 0.8)'
            ],
            borderColor: [
                'rgba(255, 215, 0, 1)',
                'rgba(64, 224, 208, 1)',
                'rgba(255, 165, 0, 1)',
                'rgba(32, 201, 151, 1)',
                'rgba(255, 107, 53, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.parsed + ' CHC';
                    }
                }
            }
        }
    }
});

// Coin Flow Chart
const ctxFlow = document.getElementById('coinFlowChart').getContext('2d');
new Chart(ctxFlow, {
    type: 'line',
    data: {
        labels: [
            <?php foreach ($dailyFlow as $date => $total): ?>
            "<?= date('d/m', strtotime($date)) ?>",
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'CHC Transferidos',
            data: [
                <?php foreach ($dailyFlow as $date => $total): ?>
                <?= $total ?>,
                <?php endforeach; ?>
            ],
            borderColor: 'rgba(64, 224, 208, 1)',
            backgroundColor: 'rgba(64, 224, 208, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(64, 224, 208, 1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' CHC transferidos';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            }
        }
    }
});
</script>
