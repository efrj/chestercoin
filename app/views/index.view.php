<?php $title = "Chestercoin (CHC) | Blockchain Público"; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js "></script>

<?php $title = "Blockchain Público - Chestercoin"; ?>

<div class="jumbotron jumbotron-fluid bg-chc-gold text-dark text-center py-5 rounded-3 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="/assets/img/logo.png" alt="Logo Chestercoin" class="mb-3" style="max-width: 300px;">
            </div>
            <div class="col-md-6">
                <h1 class="display-2 fw-bold">Chestercoin (CHC)</h1>
                <p class="lead display-6">O futuro das transações descentralizadas</p>
            </div>
        </div>
        <hr class="my-4 bg-dark opacity-25">
        <p class="h3">Veja em tempo real todas as transações realizadas na rede Chestercoin. Um sistema de blockchain simulado e educacional.</p>
        <p class="h4 text-secondary"><em>A moeda digital que nasceu para facilitar a compreensão da tecnologia por trás da revolução cripto.</em></p>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5>Top 5 Maiores Saldos</h5>
            </div>
            <div class="card-body chart-container">
                <canvas id="balanceChart"></canvas>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5>Fluxo de Moedas por Dia</h5>
            </div>
            <div class="card-body chart-container">
                <canvas id="coinFlowChart"></canvas>
            </div>
        </div>
    </div>
</div>

<form method="get" class="mb-4">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label for="hash_filter" class="form-label">Buscar por Hash</label>
            <input type="text" name="hash_filter" id="hash_filter" class="form-control" value="<?= htmlspecialchars($hashFilter) ?>">
        </div>
        <div class="col-md-3">
            <label for="date_filter" class="form-label">Filtrar por Data</label>
            <input type="date" name="date_filter" id="date_filter" class="form-control" value="<?= $dateFilter ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-chc-orange w-100">Filtrar</button>
        </div>
        <div class="col-md-2">
            <a href="/" class="btn btn-chc-turquoise w-100">Limpar</a>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5>Últimas Transações<?= $hashFilter || $dateFilter ? ' (filtradas)' : '' ?></h5>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($paged['data'])): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($paged['data'] as $t): ?>
                            <li class="list-group-item blockchain-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong><?= htmlspecialchars(substr($t['from'], 0, 8)) ?>...</strong>
                                        →
                                        <strong><?= htmlspecialchars(substr($t['to'], 0, 8)) ?>...</strong><br>
                                        <small class="text-muted"><?= $t['time'] ?></small>
                                    </div>
                                    <div class="text-success"><?= $t['amount'] ?> CHC</div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="text-center text-muted p-4">Nenhuma transação encontrada com esses filtros.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if ($paged['total_pages'] > 1): ?>
    <div class="pagination-container mt-3">
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&hash_filter=<?= urlencode($hashFilter) ?>&date_filter=<?= urlencode($dateFilter) ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $paged['total_pages']; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&hash_filter=<?= urlencode($hashFilter) ?>&date_filter=<?= urlencode($dateFilter) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $paged['total_pages'] ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&hash_filter=<?= urlencode($hashFilter) ?>&date_filter=<?= urlencode($dateFilter) ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
<?php endif; ?>

<div class="row mb-5">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5>Estatísticas Gerais</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Total de Transações:</strong> <?= $totalTransactions ?></li>
                    <li class="list-group-item"><strong>Moedas Movimentadas:</strong> <?= $totalCoinsMoved ?> COIN</li>
                    <li class="list-group-item"><strong>Total de Carteiras:</strong> <?= $totalWallets ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('balanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                <?php foreach ($topBalances as $hash => $balance): ?>
                "<?= substr($hash, 0, 6) ?>...",
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Saldo (COIN)',
                data: [
                    <?php foreach ($topBalances as $balance): ?>
                    <?= $balance ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' COIN';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 10
                    }
                }
            }
        }
    });
</script>
<script>
    const ctxFlow = document.getElementById('coinFlowChart').getContext('2d');
    new Chart(ctxFlow, {
        type: 'line',
        data: {
            labels: [
                <?php foreach ($dailyFlow as $date => $total): ?>
                "<?= $date ?>",
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Total de Moedas Transferidas',
                data: [
                    <?php foreach ($dailyFlow as $date => $total): ?>
                    <?= $total ?>,
                    <?php endforeach; ?>
                ],
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' COIN';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
