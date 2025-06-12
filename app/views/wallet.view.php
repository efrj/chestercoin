<div class="container py-4">
    <?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?= $error ?>
        </div>
    <?php elseif ($success): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle me-2"></i>
            <?= $success ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Wallet Information Section -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient text-center py-3" style="background: linear-gradient(135deg, var(--chc-gold), var(--chc-orange));">
                    <h4 class="mb-0 fw-bold text-dark">
                        <i class="fas fa-wallet me-2"></i>
                        Sua Carteira CHC
                    </h4>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="balance-display p-3 rounded-3 mb-3" style="background: linear-gradient(135deg, var(--chc-turquoise), #20c997);">
                            <h2 class="text-white mb-0 fw-bold"><?= $balance ?> CHC</h2>
                            <small class="text-white opacity-75">Saldo Atual</small>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">CHAVE PÚBLICA (HASH)</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0" 
                                   value="<?= htmlspecialchars($publicHash) ?>" 
                                   readonly id="publicHashInput">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('publicHashInput')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <a href="/wallet?export=1" class="btn btn-chc-turquoise btn-lg">
                            <i class="fas fa-download me-2"></i>
                            Exportar Carteira (.json)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Send Coins Section -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient text-center py-3" style="background: linear-gradient(135deg, var(--chc-orange), #ff6b35);">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="fas fa-paper-plane me-2"></i>
                        Enviar CHC
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="post">
                        <input type="hidden" name="send" value="1">
                        
                        <div class="mb-4">
                            <label for="to_hash" class="form-label fw-bold">
                                <i class="fas fa-user me-1"></i>
                                Hash de Destino
                            </label>
                            <input type="text" class="form-control form-control-lg border-2" 
                                   id="to_hash" name="to_hash" 
                                   placeholder="Cole o hash da carteira de destino"
                                   required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="amount" class="form-label fw-bold">
                                <i class="fas fa-coins me-1"></i>
                                Quantia (CHC)
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control border-2" 
                                       id="amount" name="amount" 
                                       min="1" max="<?= $balance ?>"
                                       placeholder="0"
                                       required>
                                <span class="input-group-text bg-light">CHC</span>
                            </div>
                            <div class="form-text">
                                Saldo disponível: <strong><?= $balance ?> CHC</strong>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-chc-orange btn-lg">
                                <i class="fas fa-send me-2"></i>
                                Enviar CHC
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient py-3" style="background: linear-gradient(135deg, var(--chc-gold), var(--chc-turquoise));">
                    <div class="row align-items-center">
                        <div class="col">
                            <h4 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-history me-2"></i>
                                Histórico de Transações
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Filter Section -->
                    <form method="get" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="date_filter_wallet" class="form-label fw-bold">
                                    <i class="fas fa-calendar me-1"></i>
                                    Filtrar por Data
                                </label>
                                <input type="date" name="date_filter" id="date_filter_wallet" 
                                       class="form-control border-2"
                                       value="<?= $_GET['date_filter'] ?? '' ?>">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-chc-gold w-100">
                                    <i class="fas fa-filter me-2"></i>
                                    Filtrar
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="/wallet" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-times me-2"></i>
                                    Limpar
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Transactions List -->
                    <div class="transactions-container">
                        <?php
                        $dateFilter = $_GET['date_filter'] ?? '';
                        $filtered = Transaction::filterTransactions($transactions, null, $dateFilter);
                        $page = $_GET['txpage'] ?? 1;
                        $per_page = 5;
                        $paginated = Transaction::paginateArray($filtered, $page, $per_page);

                        if (!empty($paginated['data'])): ?>
                            <?php foreach ($paginated['data'] as $index => $t): 
                                $isReceived = $t['to'] === $publicHash;
                                $isGenesis = $t['from'] === 'genesis';
                            ?>
                                <div class="transaction-card mb-3 p-3 rounded-3 border-2 <?= $isReceived ? 'border-success bg-light-success' : 'border-warning bg-light-warning' ?>">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="transaction-icon p-2 rounded-circle <?= $isReceived ? 'bg-success' : 'bg-warning' ?>">
                                                <i class="fas <?= $isReceived ? 'fa-arrow-down' : 'fa-arrow-up' ?> text-white"></i>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <strong class="<?= $isReceived ? 'text-success' : 'text-warning' ?>">
                                                            <?= $isReceived ? 'RECEBIDO' : 'ENVIADO' ?>
                                                        </strong>
                                                        <?php if ($isGenesis): ?>
                                                            <span class="badge bg-info ms-2">GENESIS</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <strong>De:</strong> 
                                                        <span class="text-break"><?= $isGenesis ? 'Sistema (Genesis)' : htmlspecialchars(substr($t['from'], 0, 20) . '...') ?></span>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <strong>Para:</strong> 
                                                        <span class="text-break"><?= htmlspecialchars(substr($t['to'], 0, 20) . '...') ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-center">
                                                    <div class="amount-display">
                                                        <h5 class="mb-0 fw-bold <?= $isReceived ? 'text-success' : 'text-warning' ?>">
                                                            <?= $isReceived ? '+' : '-' ?><?= $t['amount'] ?> CHC
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 text-end">
                                                    <small class="text-muted">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?= date('d/m/Y H:i', strtotime($t['time'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhuma transação encontrada</h5>
                                <p class="text-muted">Suas transações aparecerão aqui quando você enviar ou receber CHC.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (isset($paginated['totalPages']) && $paginated['totalPages'] > 1): ?>
                        <div class="pagination-container mt-4">
                            <nav>
                                <ul class="pagination justify-content-center">
                                    <li class="page-item <?= $paginated['page'] <= 1 ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?txpage=<?= $paginated['page'] - 1 ?>&date_filter=<?= urlencode($dateFilter) ?>">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                    <?php for ($i = 1; $i <= $paginated['totalPages']; $i++): ?>
                                        <li class="page-item <?= $i == $paginated['page'] ? 'active' : '' ?>">
                                            <a class="page-link" href="?txpage=<?= $i ?>&date_filter=<?= urlencode($dateFilter) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= $paginated['page'] >= $paginated['totalPages'] ? 'disabled' : '' ?>">
                                        <a class="page-link" href="?txpage=<?= $paginated['page'] + 1 ?>&date_filter=<?= urlencode($dateFilter) ?>">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.transaction-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.transaction-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.transaction-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.balance-display {
    transition: transform 0.2s;
}

.balance-display:hover {
    transform: scale(1.02);
}

.form-control:focus {
    border-color: var(--chc-gold);
    box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .amount-display h5 {
        font-size: 1rem;
    }
    
    .transaction-card .row > div {
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(element.value);
    
    // Show feedback
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i>';
    button.classList.add('btn-success');
    button.classList.remove('btn-outline-secondary');
    
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}
</script>
