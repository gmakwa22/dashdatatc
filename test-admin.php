<?php
// Simple diagnostic page to identify the 500 error
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Admin Page Diagnostic</h1>";

echo "<h2>PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";

echo "<h2>File Permissions</h2>";
$dataDir = __DIR__ . '/data';
echo "Data directory exists: " . (is_dir($dataDir) ? "Yes" : "No") . "<br>";
if (is_dir($dataDir)) {
    echo "Data directory is writable: " . (is_writable($dataDir) ? "Yes" : "No") . "<br>";
}

echo "<h2>File Operations Test</h2>";
$testFile = __DIR__ . '/data/test-write.txt';
if (@file_put_contents($testFile, 'test')) {
    echo "File write test: SUCCESS<br>";
    @unlink($testFile);
} else {
    echo "File write test: FAILED<br>";
    echo "Error: " . error_get_last()['message'] . "<br>";
}

echo "<h2>JSON Functions</h2>";
echo "json_encode available: " . (function_exists('json_encode') ? "Yes" : "No") . "<br>";
echo "json_decode available: " . (function_exists('json_decode') ? "Yes" : "No") . "<br>";

echo "<h2>Required Files</h2>";
$files = [
    'admin.php',
    'admin-graphs.php',
    'admin-aging.php',
    'admin-licensees.php',
    'data/dashboard-metrics.json',
    'data/loan-graph.json',
    'data/aging-data.json',
    'data/licensee-income.json'
];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    echo $file . ": " . (file_exists($path) ? "EXISTS" : "MISSING") . "<br>";
}

echo "<h2>Syntax Check</h2>";
$phpFiles = ['admin.php', 'admin-graphs.php', 'admin-aging.php', 'admin-licensees.php'];
foreach ($phpFiles as $file) {
    $output = [];
    $return = 0;
    exec("php -l " . escapeshellarg(__DIR__ . '/' . $file) . " 2>&1", $output, $return);
    echo $file . ": " . ($return === 0 ? "OK" : "ERROR - " . implode(" ", $output)) . "<br>";
}

echo "<h2>Test Loading admin.php</h2>";
try {
    ob_start();
    include __DIR__ . '/admin.php';
    $output = ob_get_clean();
    echo "admin.php loaded: SUCCESS<br>";
    echo "Output length: " . strlen($output) . " bytes<br>";
} catch (Exception $e) {
    echo "admin.php loaded: FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
} catch (Error $e) {
    echo "admin.php loaded: FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}
?>

