<div class="container py-4">
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php elseif ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4">Sua Carteira</h2>

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Chave Pública (Hash)</h5>
                        <p class="card-text text-break"><?= htmlspecialchars($publicHash) ?></p>
                        <h5>Saldo: <?= $balance ?> COIN</h5>
                        <a href="/wallet?export=1" class="btn btn-chc-turquoise text-white">Exportar Carteira (.json)</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="mb-3">Enviar Moedas</h3>
                <form method="post" class="mb-5">
                    <input type="hidden" name="send" value="1">
                    <div class="mb-3">
                        <label for="to_hash" class="form-label">Hash de Destino</label>
                        <input type="text" class="form-control" id="to_hash" name="to_hash" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Quantia</label>
                        <input type="number" class="form-control" id="amount" name="amount" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-chc-orange w-100">Enviar CHC</button>
                </form>
            </div>
        </div>
    </div>

    <h3>Histórico de Transações</h3>
    <form method="get" class="mb-3">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="date_filter_wallet" class="form-label">Filtrar por Data</label>
                <input type="date" name="date_filter" id="date_filter_wallet" class="form-control"
                    value="<?= $_GET['date_filter'] ?? '' ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
            <div class="col-md-2">
                <a href="/wallet" class="btn btn-secondary w-100">Limpar</a>
            </div>
        </div>
    </form>

    <ul class="list-group">
        <?php
        // $transactions variable is already passed by the controller.
        $dateFilter = $_GET['date_filter'] ?? '';
        // Assuming $transactions is already available from the controller
        $filtered = Transaction::filterTransactions($transactions, null, $dateFilter);
        $page = $_GET['txpage'] ?? 1; // Renamed from $page to $currentPage to avoid conflict if $paged['page'] is used directly
        $per_page = 5;
        $paginated = Transaction::paginateArray($filtered, $page, $per_page);

        if (!empty($paginated['data'])): ?>
            <?php foreach ($paginated['data'] as $t): ?>
                <li class="list-group-item">
                    De: <?= htmlspecialchars($t['from']) ?><br>
                    Para: <?= htmlspecialchars($t['to']) ?><br>
                    Quantia: <?= $t['amount'] ?> COIN<br>
                    Data: <?= $t['time'] ?>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li class="list-group-item">Nenhuma transação registrada.</li>
        <?php endif; ?>
    </ul>
    
    <?php if (isset($paginated['totalPages']) && $paginated['totalPages'] > 1): ?>
        <div class="pagination-container mt-3">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $paginated['page'] <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?txpage=<?= $paginated['page'] - 1 ?>&date_filter=<?= urlencode($dateFilter) ?>">&laquo;</a>
                    </li>
                    <?php for ($i = 1; $i <= $paginated['totalPages']; $i++): ?>
                        <li class="page-item <?= $i == $paginated['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?txpage=<?= $i ?>&date_filter=<?= urlencode($dateFilter) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $paginated['page'] >= $paginated['totalPages'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="?txpage=<?= $paginated['page'] + 1 ?>&date_filter=<?= urlencode($dateFilter) ?>">&raquo;</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>