<?php

class Wallet
{
    public function __construct()
    {}

    private static function getWalletsFile(): string
    {
        return __DIR__ . '/../../data/wallets.json';
    }

    public function loadWallets(): array
    {
        $filePath = self::getWalletsFile();
        if (! file_exists($filePath)) {
            if (! is_dir(dirname($filePath))) {
                mkdir(dirname($filePath), 0777, true);
            }

            file_put_contents($filePath, json_encode([]));
            return [];
        }
        $content = file_get_contents($filePath);
        return json_decode($content, true) ?: [];
    }

    public function saveWallets(array $wallets): bool
    {
        $filePath = self::getWalletsFile();
        if (! is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }
        return file_put_contents($filePath, json_encode($wallets, JSON_PRETTY_PRINT)) !== false;
    }

    public static function generateKeyPair(): array
    {
        $privateKey = bin2hex(random_bytes(32));
        $publicKey  = hash('sha256', $privateKey);
        return [
            'privateKey' => $privateKey,
            'publicHash' => $publicKey,
        ];
    }

    public function registerWallet(string $privateKey = ''): array
    {
        $keys = $privateKey ? ['privateKey' => $privateKey, 'publicHash' => hash('sha256', $privateKey)] : self::generateKeyPair();

        $wallets                      = $this->loadWallets();
        $wallets[$keys['publicHash']] = [
            'privateKey' => $keys['privateKey'],
            'balance'    => 100,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $this->saveWallets($wallets);

        return $keys;
    }

    public function authenticate(string $privateKey): ?string
    {
        $wallets    = $this->loadWallets();
        $publicHash = hash('sha256', $privateKey);

        if (isset($wallets[$publicHash]) && $wallets[$publicHash]['privateKey'] === $privateKey) {
            return $publicHash;
        }
        return null;
    }

    public function getBalance(string $publicHash): int
    {
        $wallets = $this->loadWallets();
        return $wallets[$publicHash]['balance'] ?? 0;
    }

    public function exportWalletToFile(string $publicHash): ?string
    {
        $wallets = $this->loadWallets();
        if (! isset($wallets[$publicHash])) {
            return null;
        }

        $walletData = $wallets[$publicHash];
        $exportData = [
            'public_hash' => $publicHash,
            'private_key' => $walletData['privateKey'],
            'balance'     => $walletData['balance'],
            'created_at'  => $walletData['created_at'],
        ];

        $dataDir = __DIR__ . '/../../data';
        if (! is_dir($dataDir)) {
            mkdir($dataDir, 0777, true);
        }

        $tempFilePath = $dataDir . '/export_wallet_' . $publicHash . '_' . time() . '.json';
        if (file_put_contents($tempFilePath, json_encode($exportData, JSON_PRETTY_PRINT))) {
            return $tempFilePath;
        }
        return null;
    }

    public function importWalletData(array $walletDataToImport): bool
    {
        if (! isset($walletDataToImport['public_hash'], $walletDataToImport['private_key'])) {
            return false;
        }

        $wallets                                     = $this->loadWallets();
        $wallets[$walletDataToImport['public_hash']] = [
            'privateKey' => $walletDataToImport['private_key'],
            'balance'    => $walletDataToImport['balance'] ?? 100,
            'created_at' => $walletDataToImport['created_at'] ?? date('Y-m-d H:i:s'),
        ];
        return $this->saveWallets($wallets);
    }

    public function getAllBalances(): array
    {
        $wallets  = $this->loadWallets();
        $balances = [];
        foreach ($wallets as $hash => $data) {
            $balances[$hash] = $data['balance'];
        }
        return $balances;
    }
}
