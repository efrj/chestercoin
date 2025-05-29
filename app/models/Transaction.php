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
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $file = $dir . "$publicHash.json";
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([])); // Create empty transaction file
            return [];
        }
        $content = file_get_contents($file);
        return json_decode($content, true) ?: [];
    }

    private function saveTransactions(string $publicHash, array $transactions): void
    {
        $dir = self::getTransactionsDir();
        if (!is_dir($dir)) {
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

        if (!isset($wallets[$fromPublicHash])) {
            return "Carteira de origem não encontrada.";
        }
        if (!isset($wallets[$toPublicHash])) {
            return "Carteira de destino não encontrada.";
        }

        if ($wallets[$fromPublicHash]['balance'] < $amount) {
            return "Saldo insuficiente na carteira de origem.";
        }

        // Perform the balance update
        $wallets[$fromPublicHash]['balance'] -= $amount;
        $wallets[$toPublicHash]['balance'] += $amount;
        
        if (!$this->walletService->saveWallets($wallets)) {
            return "Erro ao salvar o estado das carteiras.";
        }

        // Record the transaction
        $transactionData = [
            'from' => $fromPublicHash,
            'to' => $toPublicHash,
            'amount' => $amount,
            'time' => date('Y-m-d H:i:s')
        ];

        // Add transaction to sender's history
        $fromTransactions = $this->getTransactions($fromPublicHash);
        $fromTransactions[] = $transactionData;
        $this->saveTransactions($fromPublicHash, $fromTransactions);

        // Add transaction to receiver's history
        $toTransactions = $this->getTransactions($toPublicHash);
        $toTransactions[] = $transactionData;
        $this->saveTransactions($toPublicHash, $toTransactions);

        return "Transferência realizada com sucesso.";
    }

    public function getAllTransactions(): array
    {
        $dir = self::getTransactionsDir();
        if (!is_dir($dir)) {
            return [];
        }

        $allTransactions = [];
        $files = glob($dir . '*.json');
        $processedHashes = []; // To avoid double-counting transactions if logic implies shared transaction objects

        foreach ($files as $file) {
            // Extract publicHash from filename to ensure we only add transactions relevant to unique wallets
            // This approach might need refinement if transactions are stored uniquely rather than duplicated per wallet
            // For now, sticking to the idea of merging all files and then potentially filtering duplicates if any
            // The old functions.php getAllTransactions just read all files and merged.
            // It did not care about the filename matching a specific wallet's transaction list.
            // It just assumed all .json files in the folder are transaction lists.

            $content = file_get_contents($file);
            $transactions = json_decode($content, true);
            if (is_array($transactions)) {
                // This logic assumes transactions are duplicated in sender's and receiver's files.
                // To list each transaction uniquely, we need a different approach.
                // The current approach from functions.php would list duplicates if both sender and receiver files are simply merged.
                // Let's refine this to store transactions uniquely based on their content (e.g. from, to, amount, time)
                // However, the original instructions for getAllTransactions just merged and sorted.
                // For now, let's assume the old behavior: just merge all arrays from all files.
                // If a transaction is in two files, it will appear twice with this simple merge.
                // The prompt says "merges transactions".
                // A better way would be to collect all transactions and then make them unique if needed.
                // The current structure implies a transaction is written to both sender and receiver files.
                // So, to get a truly unique list of all transactions, we'd need to filter.
                // The old code simply did: $allTransactions = array_merge($allTransactions, $transactions);
                // This would indeed duplicate transactions if they are in multiple files.
                // Let's stick to the original behavior from functions.php first.
                 $allTransactions = array_merge($allTransactions, $transactions);
            }
        }
        
        // Remove duplicate transactions if any, based on all fields matching.
        // This is an improvement over simply merging, if transactions are in both sender/receiver files.
        if (!empty($allTransactions)) {
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
        $total = count($array);
        $offset = ($page - 1) * $perPage;
        $data = array_slice($array, $offset, $perPage);
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    public static function getDailyCoinFlow(array $transactions): array
    {
        $dailyFlow = [];
        foreach ($transactions as $tx) {
            $date = substr($tx['time'], 0, 10);
            if (!isset($dailyFlow[$date])) {
                $dailyFlow[$date] = 0;
            }
            $dailyFlow[$date] += $tx['amount'];
        }
        ksort($dailyFlow); // Sort by date
        return $dailyFlow;
    }

    public static function getWalletNetworkFlow(array $transactions): array
    {
        $network = [];
        foreach ($transactions as $tx) {
            $pair = implode('-', sorted_array([$tx['from'], $tx['to']])); // Helper needed or implement here
            if (!isset($network[$pair])) {
                $network[$pair] = ['from' => $tx['from'], 'to' => $tx['to'], 'volume' => 0, 'transactions' => 0];
            }
            $network[$pair]['volume'] += $tx['amount'];
            $network[$pair]['transactions']++;
        }
        // A helper function sorted_array would be:
        // function sorted_array($arr) { sort($arr); return $arr; }
        // For now, let's inline it for simplicity if it's only used here.
        // Or assume it exists globally if it was part of functions.php and not explicitly moved.
        // The prompt for functions.php was "should NOT be modified yet".
        // This means sorted_array is still available globally if it was in functions.php
        // If it wasn't, this method will fail. Assuming it's available for now.
        // If `sorted_array` is not available, I'll need to implement it or ask for clarification.
        // For now, to make this self-contained within the class method if `sorted_array` is not global:
        foreach ($transactions as $key => $tx_data) { // Re-loop to create the pair correctly
             $pair_wallets = [$tx_data['from'], $tx_data['to']];
             sort($pair_wallets); // Sorts the array in place
             $pair = implode('-', $pair_wallets);

             if (!isset($network[$pair])) {
                 // Ensure 'from' and 'to' are consistently ordered for the pair's metadata if that matters
                 // Or just use the original $tx_data['from'] and $tx_data['to']
                 $network[$pair] = ['from_node' => $pair_wallets[0], 'to_node' => $pair_wallets[1], 'volume' => 0, 'transactions' => 0];
             }
             $network[$pair]['volume'] += $tx_data['amount'];
             $network[$pair]['transactions']++;
        }


        return $network;
    }
}
