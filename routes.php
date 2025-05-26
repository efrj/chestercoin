<?php

session_start();

require_once 'config.php';
require_once CORE_PATH . '/Router.php';

$router = new Router();

$publicHash = $_SESSION['public_hash'] ?? null;

function render(string $view, array $data = []) {
    extract($data);
    ob_start();
    require VIEW_PATH . "/$view";
    $content = ob_get_clean();
    require VIEW_PATH . "/layouts/layout.php";
}

$router->add('GET', '/', function () use ($publicHash) {
    require_once __DIR__ . '/functions.php';
    $allTransactions = getAllTransactions();
    $allBalances = getAllBalances();
    $hashFilter = $_GET['hash_filter'] ?? '';
    $dateFilter = $_GET['date_filter'] ?? '';
    $filteredTransactions = filterTransactions($allTransactions, $hashFilter, $dateFilter);
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = 5;
    $paged = paginateArray($filteredTransactions, $page, $perPage);
    arsort($allBalances);
    $topBalances = array_slice($allBalances, 0, 5, true);

    $dailyFlow = getDailyCoinFlow($allTransactions);

    $totalTransactions = count($allTransactions);
    $totalCoinsMoved = array_sum(array_column($allTransactions, 'amount'));
    $totalWallets = count($allBalances);

    render('index.view.php', compact(
        'publicHash',
        'hashFilter',
        'dateFilter',
        'paged',
        'topBalances',
        'dailyFlow',
        'totalTransactions',
        'totalCoinsMoved',
        'totalWallets'
    ));
});

$router->add('GET', '/wallet', function () use ($publicHash) {
    if (!$publicHash) {
        header("Location: /");
        exit;
    }

    require_once __DIR__ . '/functions.php';

    if (isset($_GET['export'])) {
        $filePath = exportWalletToFile($publicHash);

        if ($filePath) {
            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="carteira_' . $publicHash . '.json"');
            readfile($filePath);
            unlink($filePath);
            exit;
        } else {
            die("Erro ao exportar carteira.");
        }
    }

    $balance = getBalance($publicHash);
    $transactions = getTransactions($publicHash);
    $error = $success = '';

    render('wallet.view.php', compact('publicHash', 'balance', 'transactions', 'error', 'success'));
});

$router->add('POST', '/wallet', function () use ($publicHash) {
    if (!$publicHash) {
        header("Location: /");
        exit;
    }

    require_once __DIR__ . '/functions.php';

    $toHash = $_POST['to_hash'] ?? '';
    $amount = (int)($_POST['amount'] ?? 0);

    $result = transfer($publicHash, $toHash, $amount);

    if ($result === "Transferência realizada com sucesso.") {
        $balance = getBalance($publicHash);
        $transactions = getTransactions($publicHash);
        $success = $result;
        $error = '';
    } else {
        $balance = getBalance($publicHash);
        $transactions = getTransactions($publicHash);
        $error = $result;
        $success = '';
    }

    render('wallet.view.php', compact('publicHash', 'balance', 'transactions', 'error', 'success'));
});

$router->add('GET', '/login', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    if (isset($_GET['new'])) {
        $generatedKeys = registerWallet('');
        render('login.view.php', [
            'publicHash' => $publicHash,
            'generatedKeys' => $generatedKeys
        ]);
    } else {
        render('login.view.php', [
            'publicHash' => $publicHash,
            'generatedKeys' => null,
            'error' => ''
        ]);
    }
});

$router->add('POST', '/login', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    require_once __DIR__ . '/functions.php';

    $privateKey = $_POST['private_key'] ?? '';
    $error = '';

    $publicHash = authenticate($privateKey);

    if ($publicHash) {
        $_SESSION['public_hash'] = $publicHash;
        header("Location: /wallet");
        exit;
    } else {
        $error = "Chave privada inválida.";
    }

    render('login.view.php', [
        'publicHash' => $publicHash,
        'generatedKeys' => null,
        'error' => $error
    ]);
});

$router->add('GET', '/new-wallet', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    require_once __DIR__ . '/functions.php';
    $keys = registerWallet('');

    render('login.view.php', [
        'publicHash' => $publicHash,
        'generatedKeys' => $keys
    ]);
});

$router->add('GET', '/import', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }
    render('import.view.php', ['publicHash' => $publicHash]);
});

$router->add('POST', '/import', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    if (isset($_FILES['wallet_file']) && $_FILES['wallet_file']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['wallet_file']['tmp_name'];
        $content = file_get_contents($tmp_name);
        $data = json_decode($content, true);

        if ($data && isset($data['public_hash'], $data['private_key'])) {
            $expectedHash = hash('sha256', $data['private_key']);

            if ($expectedHash === $data['public_hash']) {
                require_once __DIR__ . '/functions.php';
                $wallets = loadWallets();
                $wallets[$data['public_hash']] = [
                    'privateKey' => $data['private_key'],
                    'balance' => $data['balance'] ?? 100,
                    'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
                ];
                saveWallets($wallets);

                $_SESSION['public_hash'] = $data['public_hash'];
                header("Location: /wallet");
                exit;
            } else {
                die("Arquivo inválido.");
            }
        } else {
            die("Formato do arquivo incorreto.");
        }
    } else {
        die("Erro ao enviar o arquivo.");
    }
});

$router->add('GET', '/logout', function () {
    session_start();
    session_destroy();
    header("Location: /");
});

$router->dispatch();
