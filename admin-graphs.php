<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors, but log them
ini_set('log_errors', 1);

$dataPath = __DIR__ . '/data/loan-graph.json';

// Data is now loaded only from external JSON files - no hardcoded defaults
// This ensures code changes don't affect the data

function ensureLoanDataFileExists($path)
{
    if (!file_exists($path)) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true)) {
                // If mkdir fails, try to continue - the error will be caught later
                error_log("Warning: Could not create directory: " . $dir);
            }
        }
        // Create empty structure - data must be added through the admin interface
        $payload = [
            'loans' => [],
            'gauges' => [],
            'meta' => ['updatedAt' => date(DATE_ATOM)],
        ];
        @file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

function cleanFloat($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = str_replace([',', '$', ' '], '', $value);
    $numeric = preg_replace('/[^0-9.\-]/', '', $numeric);
    return $numeric === '' ? 0 : (float)$numeric;
}

function cleanInt($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = preg_replace('/[^0-9\-]/', '', $value);
    return $numeric === '' ? 0 : (int)$numeric;
}

function cleanString($value)
{
    return trim((string)$value);
}

// Helper function for PHP 5.x compatibility (replaces ?? operator)
function getValue($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

function displayValue($value, $decimals = null)
{
    if ($value === null || $value === '') {
        return '';
    }
    if (!is_numeric($value)) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    $num = (float)$value;
    if ($decimals === null) {
        $decimals = abs($num - round($num)) > 0.009 ? 2 : 0;
    }
    return htmlspecialchars(number_format($num, $decimals), ENT_QUOTES, 'UTF-8');
}

ensureLoanDataFileExists($dataPath);
$data = [];
$loans = [];
$gauges = [];
$updatedAt = null;
$messages = [];

// Try to load data, handle errors gracefully
if (file_exists($dataPath)) {
    $dataContent = file_get_contents($dataPath);
    if ($dataContent !== false) {
        $data = json_decode($dataContent, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
            $messages[] = array('type' => 'error', 'text' => 'Error reading loan graph file: ' . $errorMsg);
            $data = array();
        } else {
            $loans = isset($data['loans']) ? $data['loans'] : array();
            $gauges = isset($data['gauges']) ? $data['gauges'] : array();
            $updatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
        }
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to read loan graph file. Check file permissions.');
    }
} else {
    $messages[] = array('type' => 'warning', 'text' => 'Loan graph file not found. It will be created when you save.');
}

// Show warning if no data exists
if (empty($loans)) {
    $messages[] = array('type' => 'warning', 'text' => 'No loan data found. Please add loan entries below and save.');
}
if (empty($gauges)) {
    $messages[] = array('type' => 'info', 'text' => 'No gauge data found. You can add gauge entries below, or they will be calculated automatically from loan data.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputLoans = isset($_POST['loans']) ? $_POST['loans'] : array();
    $normalized = array();

    foreach ($inputLoans as $index => $loan) {
        $title = cleanString(getValue($loan, 'particularTitle', ''));
        if ($title === '') {
            $title = 'Item ' . ($index + 1);
        }
        $varietyPercent = getValue($loan, 'varietyPercent', 0);
        $normalized[] = array(
            'id' => cleanInt(getValue($loan, 'id', $index + 1)),
            'particularTitle' => $title,
            'itemCount' => cleanInt(getValue($loan, 'itemCount', 0)),
            'disbursedAmount' => cleanFloat(getValue($loan, 'disbursedAmount', 0)),
            'interestAmount' => cleanFloat(getValue($loan, 'interestAmount', 0)),
            'totalAmount' => cleanFloat(getValue($loan, 'totalAmount', 0)),
            'collectAmount' => cleanFloat(getValue($loan, 'collectAmount', 0)),
            'varietyPercent' => cleanFloat($varietyPercent),
            'totalTfpPaid' => cleanFloat(getValue($loan, 'totalTfpPaid', 0)),
            'totalTfpCollected' => cleanFloat(getValue($loan, 'totalTfpCollected', 0)),
            'displayPercent' => cleanFloat(getValue($loan, 'displayPercent', getValue($loan, 'varietyPercent', 0))),
        );
    }

    // Require at least one loan entry
    if (!count($normalized)) {
        $messages[] = array('type' => 'error', 'text' => 'At least one loan entry is required.');
        // Keep existing data if validation fails
        $normalized = $loans;
    }

    $inputGauges = isset($_POST['gauges']) ? $_POST['gauges'] : array();
    $normalizedGauges = array();
    foreach ($inputGauges as $index => $gauge) {
        $normalizedGauges[] = array(
            'id' => cleanString(getValue($gauge, 'id', 'chartdiv' . ($index + 1))),
            'title' => cleanString(getValue($gauge, 'title', '')),
            'value' => cleanFloat(getValue($gauge, 'value', 0)),
        );
    }
    // Gauges are optional - can be empty or calculated from loan data

    $payload = [
        'loans' => array_values($normalized),
        'gauges' => array_values($normalizedGauges),
        'meta' => ['updatedAt' => date(DATE_ATOM)],
    ];

    if (file_put_contents($dataPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false) {
        $loans = $payload['loans'];
        $gauges = $payload['gauges'];
        $updatedAt = $payload['meta']['updatedAt'];
        $messages[] = array('type' => 'success', 'text' => 'Loan graph totals updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save loan graph data.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Graph Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="dist/vendors/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #181825;
            color: #f5f5f5;
            min-height: 100vh;
            padding: 30px;
        }
        .card, .table {
            background: #1f1f2e;
            border: 1px solid #2c2c3d;
        }
        .form-control {
            background: #12121b;
            border: 1px solid #3a3a4d;
            color: #fff;
        }
        .form-control:focus {
            border-color: #5e5efc;
            box-shadow: none;
        }
        .btn-primary {
            background: #5e5efc;
            border-color: #5e5efc;
        }
        .admin-nav .nav-link {
            color: #d0d0f5;
            background: #11111b;
            border: 1px solid #2c2c3d;
            margin-right: 5px;
        }
        .admin-nav .nav-link.active {
            background: #5e5efc;
            border-color: #5e5efc;
            color: #fff;
        }
        .table thead th {
            border-bottom-color: #2c2c3d;
        }
        .table td, .table th {
            border-color: #2c2c3d;
            vertical-align: middle;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Loan Graph Admin</h1>
        <a href="index.html" class="btn btn-outline-light btn-sm">View Dashboard</a>
    </div>
    <ul class="nav nav-tabs admin-nav mb-4">
        <li class="nav-item"><a class="nav-link" href="admin.php">Main</a></li>
        <li class="nav-item"><a class="nav-link active" href="admin-graphs.php">Graphs</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-aging.php">Aging</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-licensees.php">Licensees</a></li>
    </ul>
    <?php foreach ($messages as $message): ?>
        <?php
        $alertClass = 'alert-info';
        if ($message['type'] === 'success') {
            $alertClass = 'alert-success';
        } elseif ($message['type'] === 'error' || $message['type'] === 'danger') {
            $alertClass = 'alert-danger';
        } elseif ($message['type'] === 'warning') {
            $alertClass = 'alert-warning';
        }
        ?>
        <div class="alert <?php echo $alertClass; ?>">
            <?php echo htmlspecialchars($message['text'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endforeach; ?>
    <?php if ($updatedAt): ?>
        <p><span class="badge badge-success">Last updated: <?php echo htmlspecialchars($updatedAt, ENT_QUOTES, 'UTF-8'); ?></span></p>
    <?php endif; ?>
    <div class="alert alert-info">
        <strong>Tip:</strong> The dashboard pie chart recalculates automatically from the <em>Loan Count</em> values (the percentages are derived for you). Update the counts and click <strong>Save Loan Totals</strong>, then refresh the dashboard.
    </div>
    <form method="post">
        <div class="table-responsive">
            <table class="table table-dark table-striped">
                <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="min-width: 180px;">Title</th>
                    <th>Loan Count</th>
                    <th>Disbursed</th>
                    <th>Interest</th>
                    <th>Total Amount</th>
                    <th>Collected</th>
                    <th>% of Total (Auto)</th>
                    <th>% Override</th>
                    <th>TFP Paid</th>
                    <th>TFP Collected</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($loans as $index => $loan): ?>
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][id]" value="<?php echo displayValue(isset($loan['id']) ? $loan['id'] : '', 0); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][particularTitle]" value="<?php echo htmlspecialchars(isset($loan['particularTitle']) ? $loan['particularTitle'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][itemCount]" value="<?php echo displayValue(isset($loan['itemCount']) ? $loan['itemCount'] : '', 0); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][disbursedAmount]" value="<?php echo displayValue(isset($loan['disbursedAmount']) ? $loan['disbursedAmount'] : ''); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][interestAmount]" value="<?php echo displayValue(isset($loan['interestAmount']) ? $loan['interestAmount'] : ''); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][totalAmount]" value="<?php echo displayValue(isset($loan['totalAmount']) ? $loan['totalAmount'] : ''); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][collectAmount]" value="<?php echo displayValue(isset($loan['collectAmount']) ? $loan['collectAmount'] : ''); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][varietyPercent]" value="<?php echo displayValue(isset($loan['varietyPercent']) ? $loan['varietyPercent'] : '', 2); ?>">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="loans[<?php echo $index; ?>][displayPercent]" value="<?php echo displayValue(isset($loan['displayPercent']) ? $loan['displayPercent'] : '', 2); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][totalTfpPaid]" value="<?php echo displayValue(isset($loan['totalTfpPaid']) ? $loan['totalTfpPaid'] : ''); ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="loans[<?php echo $index; ?>][totalTfpCollected]" value="<?php echo displayValue(isset($loan['totalTfpCollected']) ? $loan['totalTfpCollected'] : ''); ?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-3">Save Loan Totals</button>
        <div class="card h-100 p-3 mt-4">
            <h5>Gauge Values</h5>
            <p class="small text-muted mb-3">These values drive the six gauges (“Total Collected”, “Funding”, etc.) in the dashboard.</p>
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                    <tr>
                        <th>Chart Target (ID)</th>
                        <th>Title</th>
                        <th style="width: 160px;">Value (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($gauges as $index => $gauge): ?>
                        <tr>
                            <td><input type="text" class="form-control" name="gauges[<?php echo $index; ?>][id]" value="<?php echo htmlspecialchars(isset($gauge['id']) ? $gauge['id'] : ('chartdiv' . ($index + 1)), ENT_QUOTES, 'UTF-8'); ?>"></td>
                            <td><input type="text" class="form-control" name="gauges[<?php echo $index; ?>][title]" value="<?php echo htmlspecialchars(isset($gauge['title']) ? $gauge['title'] : '', ENT_QUOTES, 'UTF-8'); ?>"></td>
                            <td><input type="text" class="form-control" name="gauges[<?php echo $index; ?>][value]" value="<?php echo displayValue(isset($gauge['value']) ? $gauge['value'] : '', 2); ?>"></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-3">Save Gauge Values</button>
    </form>
</div>
</body>
</html>

