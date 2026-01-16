<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors, but log them
ini_set('log_errors', 1);

$metricsPath = __DIR__ . '/data/dashboard-metrics.json';

// Data is now loaded only from external JSON files - no hardcoded defaults
// This ensures code changes don't affect the data

function ensureMetricsFileExists($path)
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
        $default = [
            'cards' => [
                'balances' => [
                    'available' => 0,
                    'pending' => 0,
                    'account' => 0,
                    'cumulative' => 0,
                ],
                'funding' => [
                    'total' => 0,
                    'etransfer' => 0,
                    'received' => 0,
                    'balance' => 0,
                ],
                'roi' => [
                    'percentage' => 0,
                    'investment' => 0,
                    'income' => 0,
                ],
                'territories' => [
                    'totalAmount' => 0,
                    'totalCount' => 0,
                    'soldAmount' => 0,
                    'soldCount' => 0,
                    'inProgressAmount' => 0,
                    'inProgressCount' => 0,
                    'onHoldAmount' => 0,
                    'onHoldCount' => 0,
                ],
            ],
            'meta' => [
                'updatedAt' => date(DATE_ATOM),
            ],
        ];
        @file_put_contents($path, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

function cleanCurrencyInput($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = str_replace([',', '$', ' '], '', $value);
    $numeric = preg_replace('/[^0-9.\-]/', '', $numeric);
    return $numeric === '' ? 0 : (float)$numeric;
}

function cleanIntInput($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = preg_replace('/[^0-9\-]/', '', $value);
    return $numeric === '' ? 0 : (int)$numeric;
}

// Helper function for PHP 5.x compatibility (replaces ?? operator)
function getValue($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

function displayNumber($value, $decimals = null)
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

ensureMetricsFileExists($metricsPath);
$metrics = [];
$messages = [];
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'main';

// Try to load metrics, handle errors gracefully
if (file_exists($metricsPath)) {
    $metricsContent = file_get_contents($metricsPath);
    if ($metricsContent !== false) {
        $metrics = json_decode($metricsContent, true);
        if ($metrics === null && json_last_error() !== JSON_ERROR_NONE) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
            $messages[] = array('type' => 'error', 'text' => 'Error reading dashboard metrics file: ' . $errorMsg);
            $metrics = array();
        }
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to read dashboard metrics file. Check file permissions.');
    }
} else {
    $messages[] = array('type' => 'warning', 'text' => 'Dashboard metrics file not found. It will be created when you save.');
}

// Handle Generate form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_dashboard'])) {
    $tab = 'generate'; // Stay on generate tab after submission
    require_once __DIR__ . '/generate-dashboard.php';
    $generator = new DashboardGenerator();
    $result = $generator->generate($_POST);
    if ($result['success']) {
        $messages[] = array('type' => 'success', 'text' => $result['message']);
        // Reload metrics after generation
        if (file_exists($metricsPath)) {
            $metricsContent = file_get_contents($metricsPath);
            if ($metricsContent !== false) {
                $metrics = json_decode($metricsContent, true);
            }
        }
    } else {
        $messages[] = array('type' => 'error', 'text' => $result['message']);
    }
}

// Handle Generate Licensees form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_licensees'])) {
    $tab = 'generate-licensees'; // Stay on generate-licensees tab after submission
    require_once __DIR__ . '/generate-licensees.php';
    $generator = new LicenseeGenerator();
    $result = $generator->generate($_POST);
    if ($result['success']) {
        $messages[] = array('type' => 'success', 'text' => $result['message']);
    } else {
        $messages[] = array('type' => 'error', 'text' => $result['message']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['generate_dashboard']) && !isset($_POST['generate_licensees'])) {
    $payload = [
        'cards' => [
            'balances' => [
                'available' => cleanCurrencyInput(getValue($_POST, 'balances_available', '')),
                'pending' => cleanCurrencyInput(getValue($_POST, 'balances_pending', '')),
                'account' => cleanCurrencyInput(getValue($_POST, 'balances_account', '')),
                'cumulative' => cleanCurrencyInput(getValue($_POST, 'balances_cumulative', '')),
            ],
            'funding' => [
                'total' => cleanCurrencyInput(getValue($_POST, 'funding_total', '')),
                'etransfer' => cleanCurrencyInput(getValue($_POST, 'funding_etransfer', '')),
                'received' => cleanCurrencyInput(getValue($_POST, 'funding_received', '')),
                'balance' => cleanCurrencyInput(getValue($_POST, 'funding_balance', '')),
            ],
            'roi' => [
                'percentage' => cleanCurrencyInput(getValue($_POST, 'roi_percentage', '')),
                'investment' => cleanCurrencyInput(getValue($_POST, 'roi_investment', '')),
                'income' => cleanCurrencyInput(getValue($_POST, 'roi_income', '')),
            ],
            'territories' => [
                'totalAmount' => cleanCurrencyInput(getValue($_POST, 'territories_totalAmount', '')),
                'totalCount' => cleanIntInput(getValue($_POST, 'territories_totalCount', '')),
                'soldAmount' => cleanCurrencyInput(getValue($_POST, 'territories_soldAmount', '')),
                'soldCount' => cleanIntInput(getValue($_POST, 'territories_soldCount', '')),
                'inProgressAmount' => cleanCurrencyInput(getValue($_POST, 'territories_inProgressAmount', '')),
                'inProgressCount' => cleanIntInput(getValue($_POST, 'territories_inProgressCount', '')),
                'onHoldAmount' => cleanCurrencyInput(getValue($_POST, 'territories_onHoldAmount', '')),
                'onHoldCount' => cleanIntInput(getValue($_POST, 'territories_onHoldCount', '')),
            ],
        ],
        'meta' => [
            'updatedAt' => date(DATE_ATOM),
        ],
    ];

    $encoded = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if (file_put_contents($metricsPath, $encoded) !== false) {
        $metrics = $payload;
        $messages[] = array('type' => 'success', 'text' => 'Dashboard metrics updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save dashboard metrics.');
    }
}

$balances = isset($metrics['cards']['balances']) ? $metrics['cards']['balances'] : array();
$funding = isset($metrics['cards']['funding']) ? $metrics['cards']['funding'] : array();
$roi = isset($metrics['cards']['roi']) ? $metrics['cards']['roi'] : array();
$territories = isset($metrics['cards']['territories']) ? $metrics['cards']['territories'] : array();
$updatedAt = isset($metrics['meta']['updatedAt']) ? $metrics['meta']['updatedAt'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="dist/vendors/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #181825;
            color: #f5f5f5;
            min-height: 100vh;
            padding: 30px;
        }
        .card {
            background: #1f1f2e;
            border: 1px solid #2c2c3d;
        }
        label {
            font-weight: 600;
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
        .badge-success {
            background-color: #198754;
        }
        .badge-error {
            background-color: #dc3545;
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
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Dashboard Metrics Admin</h1>
        <div>
            <a href="index.html" class="btn btn-outline-light btn-sm">View Dashboard</a>
            <a href="admin-lic.php" class="btn btn-outline-light btn-sm ml-2">Licensee Dashboards</a>
        </div>
    </div>
    <ul class="nav nav-tabs admin-nav mb-4">
        <li class="nav-item"><a class="nav-link <?php echo $tab !== 'generate' && $tab !== 'generate-licensees' ? 'active' : ''; ?>" href="admin.php">Main</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-graphs.php">Graphs</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-aging.php">Aging</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-licensees.php">Licensees</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'generate' ? 'active' : ''; ?>" href="admin.php?tab=generate">Dash Gen</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'generate-licensees' ? 'active' : ''; ?>" href="admin.php?tab=generate-licensees">Licencee Gen</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-crewai.php">🤖 CrewAI</a></li>
        <li class="nav-item"><a class="nav-link" href="chat-loan-manager.php">💬 Loan Manager Chat</a></li>
    </ul>
    <?php foreach ($messages as $message): ?>
        <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($message['text'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endforeach; ?>
    <?php if ($updatedAt): ?>
        <p><span class="badge badge-success">Last updated: <?php echo htmlspecialchars($updatedAt, ENT_QUOTES, 'UTF-8'); ?></span></p>
    <?php endif; ?>
    
    <?php if ($tab === 'generate'): ?>
        <?php include __DIR__ . '/admin-generate-tab.php'; ?>
    <?php elseif ($tab === 'generate-licensees'): ?>
        <?php include __DIR__ . '/admin-generate-licensees-tab.php'; ?>
    <?php else: ?>
    <form method="post">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>Balances</h5>
                    <div class="form-group">
                        <label for="balances_available">Available Balance</label>
                        <input type="text" class="form-control" id="balances_available" name="balances_available" value="<?php echo displayNumber(isset($balances['available']) ? $balances['available'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="balances_pending">Pending Balance</label>
                        <input type="text" class="form-control" id="balances_pending" name="balances_pending" value="<?php echo displayNumber(isset($balances['pending']) ? $balances['pending'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="balances_account">Account Balance</label>
                        <input type="text" class="form-control" id="balances_account" name="balances_account" value="<?php echo displayNumber(isset($balances['account']) ? $balances['account'] : ''); ?>" />
                    </div>
                    <div class="form-group mb-0">
                        <label for="balances_cumulative">Cumulative Balance</label>
                        <input type="text" class="form-control" id="balances_cumulative" name="balances_cumulative" value="<?php echo displayNumber(isset($balances['cumulative']) ? $balances['cumulative'] : ''); ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>Funding to Lending Account</h5>
                    <div class="form-group">
                        <label for="funding_total">Total</label>
                        <input type="text" class="form-control" id="funding_total" name="funding_total" value="<?php echo displayNumber(isset($funding['total']) ? $funding['total'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="funding_etransfer">E-Transfer Balance</label>
                        <input type="text" class="form-control" id="funding_etransfer" name="funding_etransfer" value="<?php echo displayNumber(isset($funding['etransfer']) ? $funding['etransfer'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="funding_received">Funding Received</label>
                        <input type="text" class="form-control" id="funding_received" name="funding_received" value="<?php echo displayNumber(isset($funding['received']) ? $funding['received'] : ''); ?>" />
                    </div>
                    <div class="form-group mb-0">
                        <label for="funding_balance">Funding Balance</label>
                        <input type="text" class="form-control" id="funding_balance" name="funding_balance" value="<?php echo displayNumber(isset($funding['balance']) ? $funding['balance'] : ''); ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>ROI</h5>
                    <div class="form-group">
                        <label for="roi_percentage">ROI %</label>
                        <input type="text" class="form-control" id="roi_percentage" name="roi_percentage" value="<?php echo displayNumber(isset($roi['percentage']) ? $roi['percentage'] : '', 2); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="roi_investment">Investment so far</label>
                        <input type="text" class="form-control" id="roi_investment" name="roi_investment" value="<?php echo displayNumber(isset($roi['investment']) ? $roi['investment'] : ''); ?>" />
                    </div>
                    <div class="form-group mb-0">
                        <label for="roi_income">Income</label>
                        <input type="text" class="form-control" id="roi_income" name="roi_income" value="<?php echo displayNumber(isset($roi['income']) ? $roi['income'] : '', 2); ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>Territories</h5>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="territories_totalAmount">Total Amount</label>
                            <input type="text" class="form-control" id="territories_totalAmount" name="territories_totalAmount" value="<?php echo displayNumber(isset($territories['totalAmount']) ? $territories['totalAmount'] : ''); ?>" />
                        </div>
                        <div class="form-group col-6">
                            <label for="territories_totalCount">Total Count</label>
                            <input type="text" class="form-control" id="territories_totalCount" name="territories_totalCount" value="<?php echo displayNumber(isset($territories['totalCount']) ? $territories['totalCount'] : '', 0); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="territories_soldAmount">Sold Amount</label>
                            <input type="text" class="form-control" id="territories_soldAmount" name="territories_soldAmount" value="<?php echo displayNumber(isset($territories['soldAmount']) ? $territories['soldAmount'] : ''); ?>" />
                        </div>
                        <div class="form-group col-6">
                            <label for="territories_soldCount">Sold Count</label>
                            <input type="text" class="form-control" id="territories_soldCount" name="territories_soldCount" value="<?php echo displayNumber(isset($territories['soldCount']) ? $territories['soldCount'] : '', 0); ?>" />
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="territories_inProgressAmount">In Progress Amount</label>
                            <input type="text" class="form-control" id="territories_inProgressAmount" name="territories_inProgressAmount" value="<?php echo displayNumber(isset($territories['inProgressAmount']) ? $territories['inProgressAmount'] : ''); ?>" />
                        </div>
                        <div class="form-group col-6">
                            <label for="territories_inProgressCount">In Progress Count</label>
                            <input type="text" class="form-control" id="territories_inProgressCount" name="territories_inProgressCount" value="<?php echo displayNumber(isset($territories['inProgressCount']) ? $territories['inProgressCount'] : '', 0); ?>" />
                        </div>
                    </div>
                    <div class="form-row mb-0">
                        <div class="form-group col-6">
                            <label for="territories_onHoldAmount">On Holding Amount</label>
                            <input type="text" class="form-control" id="territories_onHoldAmount" name="territories_onHoldAmount" value="<?php echo displayNumber(isset($territories['onHoldAmount']) ? $territories['onHoldAmount'] : ''); ?>" />
                        </div>
                        <div class="form-group col-6">
                            <label for="territories_onHoldCount">On Holding Count</label>
                            <input type="text" class="form-control" id="territories_onHoldCount" name="territories_onHoldCount" value="<?php echo displayNumber(isset($territories['onHoldCount']) ? $territories['onHoldCount'] : '', 0); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-3">Save Metrics</button>
    </form>
    
    <!-- Export/Import Section for AI Analysis -->
    <div class="card p-4 mt-5">
        <h4 class="mb-3">AI Data Analysis Tools</h4>
        <p class="text-muted mb-4">
            Export all dashboard variables to a document for AI analysis (e.g., Grok). 
            After analysis, upload the updated file to automatically update all variables.
        </p>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">Export All Data</h5>
                    <p class="small text-muted mb-3">
                        Download a comprehensive JSON file containing all dashboard variables:
                        metrics, loan graphs, aging data, and licensee income.
                    </p>
                    <a href="export-data.php" class="btn btn-success" download>
                        📥 Export All Variables
                    </a>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">Import Analyzed Data</h5>
                    <p class="small text-muted mb-3">
                        Upload the analyzed JSON file to update all dashboard variables at once.
                        The file should be the exported file after AI analysis.
                    </p>
                    <form id="importForm" enctype="multipart/form-data" style="margin: 0;">
                        <div class="form-group mb-2">
                            <input type="file" class="form-control-file" id="dataFile" name="dataFile" accept=".json" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            📤 Upload & Update All Variables
                        </button>
                    </form>
                    <div id="importMessages" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#importForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            var messagesDiv = $('#importMessages');
            messagesDiv.html('<div class="alert alert-info">Uploading and processing...</div>');
            
            $.ajax({
                url: 'import-data.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    messagesDiv.empty();
                    if (response.success) {
                        var alertClass = 'alert-success';
                        var html = '<div class="alert ' + alertClass + '"><strong>Success!</strong><ul style="margin-bottom: 0; padding-left: 20px;">';
                        response.messages.forEach(function(msg) {
                            html += '<li>' + $('<div>').text(msg).html() + '</li>';
                        });
                        html += '</ul></div>';
                        messagesDiv.html(html);
                        // Reload page after 2 seconds to show updated data
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    } else {
                        var alertClass = 'alert-danger';
                        var html = '<div class="alert ' + alertClass + '"><strong>Error:</strong><ul style="margin-bottom: 0; padding-left: 20px;">';
                        response.messages.forEach(function(msg) {
                            html += '<li>' + $('<div>').text(msg).html() + '</li>';
                        });
                        html += '</ul></div>';
                        messagesDiv.html(html);
                    }
                },
                error: function(xhr, status, error) {
                    messagesDiv.html('<div class="alert alert-danger"><strong>Error:</strong> ' + error + '</div>');
                }
            });
        });
    });
</script>
</body>
</html>

