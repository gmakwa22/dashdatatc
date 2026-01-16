<?php
/**
 * Quick test to check if CrewAI API is running
 */

$apiUrl = 'http://127.0.0.1:5000/health';

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

?>
<!DOCTYPE html>
<html>
<head>
    <title>API Connection Test</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #181825; color: #fff; }
        .status { padding: 15px; margin: 10px 0; border-radius: 5px; }
        .success { background: #198754; }
        .error { background: #dc3545; }
        pre { background: #12121b; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>CrewAI API Connection Test</h1>
    
    <?php if ($httpCode === 200): ?>
        <div class="status success">
            <strong>✓ SUCCESS!</strong> API is running and responding.
        </div>
        <pre><?php echo htmlspecialchars($response); ?></pre>
    <?php else: ?>
        <div class="status error">
            <strong>✗ FAILED</strong> API is not responding.
        </div>
        <p><strong>HTTP Code:</strong> <?php echo $httpCode ?: 'No response'; ?></p>
        <?php if ($error): ?>
            <p><strong>Error:</strong> <?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <h3>To start the API:</h3>
        <ol>
            <li>Open a terminal/command prompt</li>
            <li>Navigate to: <code>E:\xampp\htdocs\dashdata</code></li>
            <li>Run: <code>python crewai_api.py</code></li>
            <li>You should see: <code>* Running on http://127.0.0.1:5000</code></li>
            <li>Keep that terminal window open</li>
            <li>Refresh this page</li>
        </ol>
    <?php endif; ?>
    
    <p><a href="chat-loan-manager.php" style="color: #5e5efc;">← Back to Chat Interface</a></p>
</body>
</html>

