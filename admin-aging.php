<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors, but log them
ini_set('log_errors', 1);

$dataPath = __DIR__ . '/data/aging-data.json';

// Data is now loaded only from external JSON files - no hardcoded defaults
// This ensures code changes don't affect the data

function ensureAgingDataFileExists($path)
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
            'aging' => [],
            'meta' => ['updatedAt' => date(DATE_ATOM)],
        ];
        @file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

function cleanFloatValue($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = str_replace([',', '$', ' '], '', $value);
    $numeric = preg_replace('/[^0-9.\-]/', '', $numeric);
    return $numeric === '' ? 0 : (float)$numeric;
}

function cleanStringValue($value)
{
    return trim((string)$value);
}

// Helper function for PHP 5.x compatibility (replaces ?? operator)
function getValue($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

function displayCurrencyValue($value)
{
    if ($value === null || $value === '') {
        return '';
    }
    if (!is_numeric($value)) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    $num = (float)$value;
    $decimals = abs($num - round($num)) > 0.009 ? 2 : 2;
    return htmlspecialchars(number_format($num, $decimals), ENT_QUOTES, 'UTF-8');
}

ensureAgingDataFileExists($dataPath);
$data = [];
$agingRows = [];
$updatedAt = null;
$messages = [];

// Try to load data, handle errors gracefully
if (file_exists($dataPath)) {
    $dataContent = file_get_contents($dataPath);
    if ($dataContent !== false) {
        $data = json_decode($dataContent, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
            $messages[] = array('type' => 'error', 'text' => 'Error reading aging data file: ' . $errorMsg);
            $data = array();
        } else {
            $agingRows = isset($data['aging']) ? $data['aging'] : array();
            $updatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
        }
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to read aging data file. Check file permissions.');
    }
} else {
    $messages[] = array('type' => 'warning', 'text' => 'Aging data file not found. It will be created when you save.');
}

// Show warning if no data exists
if (empty($agingRows)) {
    $messages[] = array('type' => 'warning', 'text' => 'No aging data found. Please add aging entries below and save.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputRows = isset($_POST['aging']) ? $_POST['aging'] : array();
    $normalized = array();
    foreach ($inputRows as $index => $row) {
        $normalized[] = array(
            'label' => cleanStringValue(getValue($row, 'label', 'Row ' . ($index + 1))),
            'loanDisbursement' => cleanFloatValue(getValue($row, 'loanDisbursement', 0)),
            'interest' => cleanFloatValue(getValue($row, 'interest', 0)),
            'partialPayment' => cleanFloatValue(getValue($row, 'partialPayment', 0)),
            'totalDebt' => cleanFloatValue(getValue($row, 'totalDebt', 0)),
            'totalCollected' => cleanFloatValue(getValue($row, 'totalCollected', 0)),
            'tfpPayment' => cleanFloatValue(getValue($row, 'tfpPayment', 0)),
            'tfpCollected' => cleanFloatValue(getValue($row, 'tfpCollected', 0)),
            'tfpNet' => cleanFloatValue(getValue($row, 'tfpNet', 0)),
        );
    }
    // Require at least one aging entry
    if (!count($normalized)) {
        $messages[] = array('type' => 'error', 'text' => 'At least one aging entry is required.');
        // Keep existing data if validation fails
        $normalized = $agingRows;
    }
    $payload = [
        'aging' => $normalized,
        'meta' => ['updatedAt' => date(DATE_ATOM)],
    ];
    if (file_put_contents($dataPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false) {
        $agingRows = $payload['aging'];
        $updatedAt = $payload['meta']['updatedAt'];
        $messages[] = array('type' => 'success', 'text' => 'Aging analysis updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save aging data.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aging Data Admin</title>
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
        .table td, .table th {
            border-color: #2c2c3d;
            vertical-align: middle;
        }
        .table thead th {
            border-bottom-color: #2c2c3d;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Aging Data Admin</h1>
        <a href="index.html" class="btn btn-outline-light btn-sm">View Dashboard</a>
    </div>
    <ul class="nav nav-tabs admin-nav mb-4">
        <li class="nav-item"><a class="nav-link" href="admin.php">Main</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-graphs.php">Graphs</a></li>
        <li class="nav-item"><a class="nav-link active" href="admin-aging.php">Aging</a></li>
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
        Update these aging rows to control the “Aging Analysis” table on the dashboard. Values are formatted as currency automatically.
    </div>
    <form method="post">
        <div class="table-responsive">
            <table class="table table-dark table-striped">
                <thead>
                <tr>
                    <th style="min-width: 160px;">Aging</th>
                    <th>Loan Disbursement</th>
                    <th>Interest</th>
                    <th>Partial Payment</th>
                    <th>Total Debt</th>
                    <th>Total Collected</th>
                    <th>TFP Payment</th>
                    <th>TFP Collected</th>
                    <th>TFP Net</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($agingRows as $index => $row): ?>
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="aging[<?php echo $index; ?>][label]" value="<?php echo htmlspecialchars(isset($row['label']) ? $row['label'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                        </td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][loanDisbursement]" value="<?php echo displayCurrencyValue(isset($row['loanDisbursement']) ? $row['loanDisbursement'] : ''); ?>"></td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][interest]" value="<?php echo displayCurrencyValue(isset($row['interest']) ? $row['interest'] : ''); ?>"></td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][partialPayment]" value="<?php echo displayCurrencyValue(isset($row['partialPayment']) ? $row['partialPayment'] : ''); ?>"></td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][totalDebt]" value="<?php echo displayCurrencyValue(isset($row['totalDebt']) ? $row['totalDebt'] : ''); ?>"></td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][totalCollected]" value="<?php echo displayCurrencyValue(isset($row['totalCollected']) ? $row['totalCollected'] : ''); ?>"></td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][tfpPayment]" value="<?php echo displayCurrencyValue(isset($row['tfpPayment']) ? $row['tfpPayment'] : ''); ?>"></td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][tfpCollected]" value="<?php echo displayCurrencyValue(isset($row['tfpCollected']) ? $row['tfpCollected'] : ''); ?>"></td>
                        <td><input type="text" class="form-control" name="aging[<?php echo $index; ?>][tfpNet]" value="<?php echo displayCurrencyValue(isset($row['tfpNet']) ? $row['tfpNet'] : ''); ?>"></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-3">Save Aging Data</button>
    </form>
</div>
</body>
</html>

