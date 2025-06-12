<?php

session_start();

require_once 'config.php';
require_once CORE_PATH . '/Router.php';
require_once CORE_PATH . '/View.php';
require_once __DIR__ . '/app/models/Wallet.php';
require_once __DIR__ . '/app/models/Transaction.php';

$router = new Router();

$publicHash = $_SESSION['public_hash'] ?? null;

$router->add('GET', '/', function () use ($publicHash) {
    $wallet             = new Wallet();
    $transactionService = new Transaction($wallet);

    $allTransactions      = $transactionService->getAllTransactions();
    $allBalances          = $wallet->getAllBalances();
    $hashFilter           = $_GET['hash_filter'] ?? '';
    $dateFilter           = $_GET['date_filter'] ?? '';
    $filteredTransactions = Transaction::filterTransactions($allTransactions, $hashFilter, $dateFilter);
    $page                 = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $perPage              = 5;
    $paged                = Transaction::paginateArray($filteredTransactions, $page, $perPage);
    arsort($allBalances);
    $topBalances = array_slice($allBalances, 0, 5, true);

    $dailyFlow = Transaction::getDailyCoinFlow($allTransactions);

    $totalTransactions = count($allTransactions);
    $totalCoinsMoved   = array_sum(array_column($allTransactions, 'amount'));
    $totalWallets      = count($allBalances);

    View::render('index.view.php', compact(
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
    if (! $publicHash) {
        header("Location: /");
        exit;
    }

    $wallet             = new Wallet();
    $transactionService = new Transaction($wallet);

    if (isset($_GET['export'])) {
        $filePath = $wallet->exportWalletToFile($publicHash);

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

    $balance      = $wallet->getBalance($publicHash);
    $transactions = $transactionService->getTransactions($publicHash);
    $error        = $success        = '';

    View::render('wallet.view.php', compact('publicHash', 'balance', 'transactions', 'error', 'success'));
});

$router->add('POST', '/wallet', function () use ($publicHash) {
    if (! $publicHash) {
        header("Location: /");
        exit;
    }

    $wallet             = new Wallet();
    $transactionService = new Transaction($wallet);

    $toHash = $_POST['to_hash'] ?? '';
    $amount = (int) ($_POST['amount'] ?? 0);

    $result = $transactionService->transfer($publicHash, $toHash, $amount);

    $balance      = $wallet->getBalance($publicHash);
    $transactions = $transactionService->getTransactions($publicHash);
    $error        = '';
    $success      = '';

    if ($result === "Transferência realizada com sucesso.") {
        $success = $result;
    } else {
        $error = $result;
    }

    View::render('wallet.view.php', compact('publicHash', 'balance', 'transactions', 'error', 'success'));
});

$router->add('GET', '/login', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }
    $wallet = new Wallet();

    if (isset($_GET['new'])) {
        $generatedKeys = $wallet->registerWallet('');
        View::render('login.view.php', [
            'publicHash'    => $publicHash,
            'generatedKeys' => $generatedKeys,
        ]);
    } else {
        View::render('login.view.php', [
            'publicHash'    => $publicHash,
            'generatedKeys' => null,
            'error'         => '',
        ]);
    }
});

$router->add('POST', '/login', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    $wallet     = new Wallet();
    $privateKey = $_POST['private_key'] ?? '';
    $error      = '';

    $loginPublicHash = $wallet->authenticate($privateKey);

    if ($loginPublicHash) {
        $_SESSION['public_hash'] = $loginPublicHash;
        header("Location: /wallet");
        exit;
    } else {
        $error = "Chave privada inválida.";
    }

    View::render('login.view.php', [
        'publicHash'    => null,
        'generatedKeys' => null,
        'error'         => $error,
    ]);
});

$router->add('GET', '/new-wallet', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    $wallet = new Wallet();
    $keys   = $wallet->registerWallet('');

    View::render('new-wallet.view.php', [
        'publicHash'    => $publicHash,
        'generatedKeys' => $keys,
    ]);
});

$router->add('GET', '/generate-keys', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    $wallet = new Wallet();
    $keys   = $wallet->registerWallet('');

    View::render('login.view.php', [
        'publicHash'    => $publicHash,
        'generatedKeys' => $keys,
    ]);
});

$router->add('GET', '/import', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }
    View::render('import.view.php', ['publicHash' => $publicHash]);
});

$router->add('POST', '/import', function () use ($publicHash) {
    if ($publicHash) {
        header("Location: /wallet");
        exit;
    }

    if (isset($_FILES['wallet_file']) && $_FILES['wallet_file']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['wallet_file']['tmp_name'];
        $content  = file_get_contents($tmp_name);
        $data     = json_decode($content, true);

        if ($data && isset($data['public_hash'], $data['private_key'])) {
            $expectedHash = hash('sha256', $data['private_key']);

            if ($expectedHash === $data['public_hash']) {
                $wallet = new Wallet();
                if ($wallet->importWalletData($data)) {
                    $_SESSION['public_hash'] = $data['public_hash'];
                    header("Location: /wallet");
                    exit;
                } else {
                    die("Erro ao importar dados da carteira.");
                }
            } else {
                die("Arquivo inválido. Hash não corresponde.");
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

$router->add('GET', '/about', function () use ($publicHash) {
    View::render('about.view.php', [
        'publicHash' => $publicHash,
        'title' => 'Quem Somos - Chestercoin'
    ]);
});

$router->dispatch();
