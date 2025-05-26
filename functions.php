<?php

require_once __DIR__ . '/config.php';

function getWalletsFile()
{
    return __DIR__ . '/data/wallets.json';
}

function getTransactionsDir()
{
    return __DIR__ . '/data/transactions/';
}

function loadWallets()
{
    $file = getWalletsFile();
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    return json_decode(file_get_contents($file), true);
}

function saveWallets($wallets)
{
    file_put_contents(getWalletsFile(), json_encode($wallets, JSON_PRETTY_PRINT));
}

function generateKeyPair()
{
    $privateKey = bin2hex(random_bytes(32));
    $publicHash = hash('sha256', $privateKey);
    return compact('privateKey', 'publicHash');
}

function registerWallet($privateKey)
{
    $keys = generateKeyPair();

    if ($privateKey !== '') {
        $keys['privateKey'] = $privateKey;
        $keys['publicHash'] = hash('sha256', $privateKey);
    }

    $wallets = loadWallets();
    $wallets[$keys['publicHash']] = [
        'privateKey' => $keys['privateKey'],
        'balance' => 100,
        'created_at' => date('Y-m-d H:i:s')
    ];

    saveWallets($wallets);

    return $keys;
}

function authenticate($privateKey)
{
    $wallets = loadWallets();
    $publicHash = hash('sha256', $privateKey);

    if (isset($wallets[$publicHash]) && $wallets[$publicHash]['privateKey'] === $privateKey) {
        return $publicHash;
    }

    return false;
}

function getBalance($publicHash)
{
    $wallets = loadWallets();
    return $wallets[$publicHash]['balance'] ?? 0;
}

function transfer($fromPublicHash, $toPublicHash, $amount)
{
    $wallets = loadWallets();

    if (!isset($wallets[$fromPublicHash])) return "Carteira de origem não encontrada.";

    if (!isset($wallets[$toPublicHash])) return "O hash de destino não existe. Carteira inválida.";

    if ($wallets[$fromPublicHash]['balance'] < $amount) return "Saldo insuficiente.";

    $wallets[$fromPublicHash]['balance'] -= $amount;
    $wallets[$toPublicHash]['balance'] += $amount;

    saveWallets($wallets);

    $timestamp = date('Y-m-d H:i:s');
    $transaction = [
        'from' => $fromPublicHash,
        'to' => $toPublicHash,
        'amount' => $amount,
        'time' => $timestamp
    ];

    file_put_contents(getTransactionsDir() . "$fromPublicHash.json", json_encode(array_merge(
        getTransactions($fromPublicHash),
        [$transaction]
    ), JSON_PRETTY_PRINT));

    file_put_contents(getTransactionsDir() . "$toPublicHash.json", json_encode(array_merge(
        getTransactions($toPublicHash),
        [$transaction]
    ), JSON_PRETTY_PRINT));

    return "Transferência realizada com sucesso!";
}

function getTransactions($publicHash)
{
    $file = getTransactionsDir() . "$publicHash.json";
    if (!file_exists($file)) {
        file_put_contents($file, json_encode([]));
    }
    return json_decode(file_get_contents($file), true);
}

function exportWalletToFile($publicHash)
{
    $wallets = loadWallets();
    if (!isset($wallets[$publicHash])) return false;

    $data = [
        'public_hash' => $publicHash,
        'private_key' => $wallets[$publicHash]['privateKey'],
        'balance' => $wallets[$publicHash]['balance'],
        'created_at' => $wallets[$publicHash]['created_at']
    ];

    $filename = sys_get_temp_dir() . "/wallet_$publicHash.json";
    file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));

    return $filename;
}

function importWalletFromFile($filePath)
{
    if (!file_exists($filePath)) return false;

    $content = file_get_contents($filePath);
    $data = json_decode($content, true);

    if (!isset($data['public_hash'], $data['private_key'])) return false;

    $expectedHash = hash('sha256', $data['private_key']);
    if ($expectedHash !== $data['public_hash']) return false;

    $wallets = loadWallets();

    $wallets[$data['public_hash']] = [
        'private_key' => $data['private_key'],
        'balance' => $data['balance'] ?? 100,
        'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s')
    ];

    saveWallets($wallets);

    return $data['public_hash'];
}

function getAllTransactions()
{
    $dir = getTransactionsDir();
    $allTransactions = [];

    if (!is_dir($dir)) return [];

    foreach (scandir($dir) as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
            $content = file_get_contents($dir . $file);
            $transactions = json_decode($content, true);

            if (is_array($transactions)) {
                $allTransactions = array_merge($allTransactions, $transactions);
            }
        }
    }

    usort($allTransactions, function ($a, $b) {
        return strtotime($b['time']) - strtotime($a['time']);
    });

    return $allTransactions;
}

function getAllBalances()
{
    $wallets = loadWallets();
    $balances = [];

    foreach ($wallets as $hash => $data) {
        $balances[$hash] = $data['balance'];
    }

    return $balances;
}

function filterTransactions($transactions, $hashFilter = null, $dateFilter = null)
{
    return array_filter($transactions, function ($t) use ($hashFilter, $dateFilter) {
        if ($hashFilter && !str_contains($t['from'], $hashFilter) && !str_contains($t['to'], $hashFilter)) {
            return false;
        }

        if ($dateFilter) {
            $dateTrans = date('Y-m-d', strtotime($t['time']));
            $dateInput = date('Y-m-d', strtotime($dateFilter));
            if ($dateTrans !== $dateInput) {
                return false;
            }
        }

        return true;
    });
}

function paginateArray($array, $page, $perPage)
{
    $total = count($array);
    $pages = ceil($total / $perPage);

    $page = max(1, min($pages, $page));

    $offset = ($page - 1) * $perPage;

    return [
        'data' => array_slice($array, $offset, $perPage),
        'current_page' => $page,
        'total_pages' => $pages,
        'total_items' => $total
    ];
}

function getDailyCoinFlow($transactions)
{
    $flow = [];

    foreach ($transactions as $t) {
        $date = date('Y-m-d', strtotime($t['time']));
        if (!isset($flow[$date])) {
            $flow[$date] = 0;
        }
        $flow[$date] += $t['amount'];
    }

    ksort($flow);
    return $flow;
}

function getWalletNetworkFlow($transactions)
{
    $edges = [];

    foreach ($transactions as $t) {
        $from = $t['from'];
        $to = $t['to'];
        $amount = $t['amount'];

        $key = "$from-$to";
        if (!isset($edges[$key])) {
            $edges[$key] = [
                'from' => $from,
                'to' => $to,
                'weight' => 0
            ];
        }
        $edges[$key]['weight'] += $amount;
    }

    return array_values($edges);
}
