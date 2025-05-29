<?php
// Ensure data directory exists for Wallet class operations
if (! is_dir('data')) {
    mkdir('data', 0777, true);
}

require_once 'app/models/Wallet.php';

$wallet = new Wallet();
$keys   = $wallet->registerWallet(); // Create a new wallet

if (isset($keys['publicHash'])) {
    echo "CREATED_PUBLIC_HASH=" . $keys['publicHash'] . "\n";
} else {
    echo "Error: Could not create a new wallet or retrieve public hash.\n";
    exit(1);
}

// Optionally, create a dummy transaction for this new wallet to test transaction listing
// This part is more complex as it requires the Transaction model and another wallet.
// For now, just creating the wallet is the priority for testing the /wallet page load.
// If an empty transaction list is fine, we can skip this.
// Let's assume an empty transaction list is a valid starting point for this test.
