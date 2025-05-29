<?php

class Transaction
{
    private Wallet $walletService;

    public function __construct(Wallet $walletService)
    {
        $this->walletService = $walletService;
    }

    private static function getTransactionsDir(): string
    {
        return __DIR__ . '/../../data/transactions/';
    }

    public function getTransactions(string $publicHash): array
    {
        $dir = self::getTransactionsDir();
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $file = $dir . "$publicHash.json";
        if (! file_exists($file)) {
            file_put_contents($file, json_encode([]));
            return [];
        }
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }

    private function saveTransactions(string $publicHash, array $transactions): void
    {
        $dir = self::getTransactionsDir();
        if (! is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $file = $dir . "$publicHash.json";
        file_put_contents($file, json_encode($transactions, JSON_PRETTY_PRINT));
    }

    public function transfer(string $fromPublicHash, string $toPublicHash, int $amount): string
    {
        if ($fromPublicHash === $toPublicHash) {
            return "Não é possível transferir para a mesma carteira.";
        }

        if ($amount <= 0) {
            return "O valor da transferência deve ser positivo.";
        }

        $wallets = $this->walletService->loadWallets();

        if (! isset($wallets[$fromPublicHash])) {
            return "Carteira de origem não encontrada.";
        }
        if (! isset($wallets[$toPublicHash])) {
            return "Carteira de destino não encontrada.";
        }

        if ($wallets[$fromPublicHash]['balance'] < $amount) {
            return "Saldo insuficiente na carteira de origem.";
        }

        $wallets[$fromPublicHash]['balance'] -= $amount;
        $wallets[$toPublicHash]['balance'] += $amount;

        if (! $this->walletService->saveWallets($wallets)) {
            return "Erro ao salvar o estado das carteiras.";
        }

        $transactionData = [
            'from'   => $fromPublicHash,
            'to'     => $toPublicHash,
            'amount' => $amount,
            'time'   => date('Y-m-d H:i:s'),
        ];

        $fromTransactions   = $this->getTransactions($fromPublicHash);
        $fromTransactions[] = $transactionData;
        $this->saveTransactions($fromPublicHash, $fromTransactions);

        $toTransactions   = $this->getTransactions($toPublicHash);
        $toTransactions[] = $transactionData;
        $this->saveTransactions($toPublicHash, $toTransactions);

        return "Transferência realizada com sucesso.";
    }

    public function getAllTransactions(): array
    {
        $dir = self::getTransactionsDir();
        if (! is_dir($dir)) {
            return [];
        }

        $allTransactions = [];
        $files           = glob($dir . '*.json');
        $processedHashes = [];

        foreach ($files as $file) {
            $content      = file_get_contents($file);
            $transactions = json_decode($content, true);
            if (is_array($transactions)) {
                $allTransactions = array_merge($allTransactions, $transactions);
            }
        }

        if (! empty($allTransactions)) {
            $allTransactions = array_map("unserialize", array_unique(array_map("serialize", $allTransactions)));
        }

        usort($allTransactions, function ($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });

        return $allTransactions;
    }

    public static function filterTransactions(array $transactions, ?string $hashFilter = null, ?string $dateFilter = null): array
    {
        if ($hashFilter) {
            $transactions = array_filter($transactions, function ($tx) use ($hashFilter) {
                return $tx['from'] === $hashFilter || $tx['to'] === $hashFilter;
            });
        }
        if ($dateFilter) {
            $transactions = array_filter($transactions, function ($tx) use ($dateFilter) {
                return strpos($tx['time'], $dateFilter) === 0;
            });
        }
        return $transactions;
    }

    public static function paginateArray(array $array, int $page, int $perPage): array
    {
        $total  = count($array);
        $offset = ($page - 1) * $perPage;
        $data   = array_slice($array, $offset, $perPage);
        return [
            'data'       => $data,
            'total'      => $total,
            'page'       => $page,
            'perPage'    => $perPage,
            'totalPages' => ceil($total / $perPage),
        ];
    }

    public static function getDailyCoinFlow(array $transactions): array
    {
        $dailyFlow = [];
        foreach ($transactions as $tx) {
            $date = substr($tx['time'], 0, 10);
            if (! isset($dailyFlow[$date])) {
                $dailyFlow[$date] = 0;
            }
            $dailyFlow[$date] += $tx['amount'];
        }
        ksort($dailyFlow);
        return $dailyFlow;
    }

    public static function getWalletNetworkFlow(array $transactions): array
    {
        $network = [];
        foreach ($transactions as $tx) {
            $pair = implode('-', sorted_array([$tx['from'], $tx['to']])); // Helper needed or implement here
            if (! isset($network[$pair])) {
                $network[$pair] = ['from' => $tx['from'], 'to' => $tx['to'], 'volume' => 0, 'transactions' => 0];
            }
            $network[$pair]['volume'] += $tx['amount'];
            $network[$pair]['transactions']++;
        }

        foreach ($transactions as $key => $tx_data) {
            $pair_wallets = [$tx_data['from'], $tx_data['to']];
            sort($pair_wallets); // Sorts the array in place
            $pair = implode('-', $pair_wallets);

            if (! isset($network[$pair])) {
                $network[$pair] = ['from_node' => $pair_wallets[0], 'to_node' => $pair_wallets[1], 'volume' => 0, 'transactions' => 0];
            }
            $network[$pair]['volume'] += $tx_data['amount'];
            $network[$pair]['transactions']++;
        }

        return $network;
    }
}
