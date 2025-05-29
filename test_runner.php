<?php
$_SERVER['REQUEST_METHOD'] = 'POST'; // Changed for this test
$_SERVER['REQUEST_URI'] = '/login'; // Changed for this test
$_POST['private_key'] = 'dummy_private_key_for_testing'; // Added for POST
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// Simulate session start if not already started by the application itself upon include
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ob_start();
include 'public/index.php';
$output = ob_get_clean();

$fatal_error_patterns = [
    "PHP Fatal error:",
    "Parse error:",
    "Undefined class",
    "Call to undefined method",
    "PHP Warning:",
    "PHP Notice:"
];

$error_found = false;
$errors_detected = [];
foreach ($fatal_error_patterns as $pattern) {
    if (stripos($output, $pattern) !== false) {
        $errors_detected[] = $pattern . " detected.";
        // Capture the line of output
        $lines = explode("\n", $output);
        foreach($lines as $line) {
            if (stripos($line, $pattern) !== false) {
                $errors_detected[] = "Line: " . $line;
            }
        }
        $error_found = true;
    }
}

if (!$error_found) {
    echo "POST /login with dummy key seems to load without fatal errors, warnings, or notices.\n";
    if (stripos($output, "Chave privada inválida.") !== false || stripos($output, "Invalid private key.") !== false) {
        echo "And it correctly showed 'Chave privada inválida.' error message.\n";
    } else {
        echo "But it did NOT show the expected 'Chave privada inválida.' error message. Check output:\n";
        // echo $output; // Potentially too verbose, but useful for debugging
    }
} else {
    echo "Error(s) found for POST /login with dummy key:\n";
    foreach($errors_detected as $err) {
        echo "- " . $err . "\n";
    }
    // echo "Full Output for POST /login:\n" . $output . "\n";
}

// Clean up session for next test if needed
// session_destroy(); // Might be too aggressive if other tests rely on session state
?>
