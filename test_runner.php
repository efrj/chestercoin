<?php

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set the public_hash for the "logged-in" user
$_SESSION['public_hash'] = 'f2bf1f9a3033791f03034b6707eaeee69148ebd8a831ced5b80378dcc4b1d952';

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/wallet';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

ob_start();
include 'public/index.php';
$output = ob_get_clean();

$error_patterns = [
    "PHP Fatal error:",
    "Parse error:",
    "Undefined class",
    "Call to undefined method",
    "PHP Warning:",
    "PHP Notice:"
];

$error_found = false;
$errors_detected = [];
foreach ($error_patterns as $pattern) {
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
    echo "GET /wallet for logged-in user seems to load without fatal errors, warnings, or notices.\n";
    if (empty(trim($output))) {
        echo "Warning: Output was empty. This might indicate an issue or premature exit.\n";
    } else {
        // echo "Output snippet:\n" . substr($output, 0, 500) . "\n"; // For debugging if needed
    }
} else {
    echo "Error(s) found for GET /wallet for logged-in user:\n";
    foreach($errors_detected as $err) {
        echo "- " . $err . "\n";
    }
    // echo "Full Output for GET /wallet:\n" . $output . "\n"; // For debugging if needed
}

// Clean up session
// session_destroy(); // Not strictly necessary for this test run
?>
