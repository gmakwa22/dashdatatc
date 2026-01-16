<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors, but log them
ini_set('log_errors', 1);

$dataPath = __DIR__ . '/data/licensee-income.json';

// Data is now loaded only from external JSON files - no hardcoded defaults
// This ensures code changes don't affect the data

function ensureLicenseeDataFileExists($path)
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
            'licensees' => [],
            'meta' => ['updatedAt' => date(DATE_ATOM)]
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

function cleanBoolInput($value)
{
    if (is_bool($value)) {
        return $value;
    }
    return $value === '1' || $value === 'on' || $value === 'true';
}

function cleanIntInput($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = preg_replace('/[^0-9\-]/', '', $value);
    return $numeric === '' ? 0 : (int)$numeric;
}

function displayCurrencyInput($value)
{
    if ($value === null || $value === '') {
        return '';
    }
    if (!is_numeric($value)) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
    $num = (float)$value;
    return htmlspecialchars(number_format($num, 2, '.', ''), ENT_QUOTES, 'UTF-8');
}

function displayText($value)
{
    return htmlspecialchars(isset($value) ? $value : '', ENT_QUOTES, 'UTF-8');
}

// Helper function for PHP 5.x compatibility (replaces ?? operator)
function getValue($array, $key, $default = null) {
    return isset($array[$key]) ? $array[$key] : $default;
}

ensureLicenseeDataFileExists($dataPath);
$data = [];
$licensees = [];
$updatedAt = null;
$messages = [];
$tab = isset($_GET['tab']) ? $_GET['tab'] : 'main';

// Handle Generate Licensees form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_licensees'])) {
    require_once __DIR__ . '/generate-licensees.php';
    $generator = new LicenseeGenerator();
    $result = $generator->generate($_POST);
    if ($result['success']) {
        $messages[] = array('type' => 'success', 'text' => $result['message']);
        // Reload data after generation
        if (file_exists($dataPath)) {
            $licenseeContent = file_get_contents($dataPath);
            if ($licenseeContent !== false) {
                $licenseeData = json_decode($licenseeContent, true);
                if ($licenseeData && isset($licenseeData['licensees'])) {
                    $licensees = $licenseeData['licensees'];
                }
            }
        }
    } else {
        $messages[] = array('type' => 'error', 'text' => $result['message']);
    }
}

// Try to load data, handle errors gracefully
if (file_exists($dataPath)) {
    $dataContent = file_get_contents($dataPath);
    if ($dataContent !== false) {
        $data = json_decode($dataContent, true);
        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
            $messages[] = array('type' => 'error', 'text' => 'Error reading licensee data file: ' . $errorMsg);
            $data = array();
        } else {
            $licensees = isset($data['licensees']) ? $data['licensees'] : array();
            $updatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
        }
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to read licensee data file. Check file permissions.');
    }
} else {
    $messages[] = array('type' => 'warning', 'text' => 'Licensee data file not found. It will be created when you save.');
}

// Show warning if no data exists
if (empty($licensees)) {
    $messages[] = array('type' => 'warning', 'text' => 'No licensee data found. Please add licensee entries below and save.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputRows = isset($_POST['licensees']) ? $_POST['licensees'] : array();
    $normalized = array();
    foreach ($inputRows as $index => $row) {
        $normalized[] = array(
            'licenseeId' => cleanIntInput(getValue($row, 'licenseeId', $index + 1)),
            'licenseeName' => trim(getValue($row, 'licenseeName', '')),
            'transactionalFee' => cleanCurrencyInput(getValue($row, 'transactionalFee', 0)),
            'monthlyAmount' => cleanCurrencyInput(getValue($row, 'monthlyAmount', 0)),
            'licenseeFee' => cleanCurrencyInput(getValue($row, 'licenseeFee', 0)),
            'lendingFundsReceived' => cleanCurrencyInput(getValue($row, 'lendingFundsReceived', 0)),
            'totalIncome' => cleanCurrencyInput(getValue($row, 'totalIncome', 0)),
            'showInNav' => cleanBoolInput(getValue($row, 'showInNav', false)),
            'includeInSelect' => cleanBoolInput(getValue($row, 'includeInSelect', true)),
            'showInTable' => cleanBoolInput(getValue($row, 'showInTable', true)),
            'isAggregate' => cleanBoolInput(getValue($row, 'isAggregate', false))
        );
    }
    // Licensees are optional - can be empty
    // No validation required
    $payload = [
        'licensees' => $normalized,
        'meta' => ['updatedAt' => date(DATE_ATOM)]
    ];
    if (file_put_contents($dataPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) !== false) {
        $licensees = $normalized;
        $updatedAt = $payload['meta']['updatedAt'];
        $messages[] = array('type' => 'success', 'text' => 'Licensee income data updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save licensee data.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Licensee Income Admin</title>
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
        .checkbox-cell {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Licensee Income Admin</h1>
        <a href="index.html" class="btn btn-outline-light btn-sm">View Dashboard</a>
    </div>
    <ul class="nav nav-tabs admin-nav mb-4">
        <li class="nav-item"><a class="nav-link" href="admin.php">Main</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-graphs.php">Graphs</a></li>
        <li class="nav-item"><a class="nav-link" href="admin-aging.php">Aging</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab !== 'generate' ? 'active' : ''; ?>" href="admin-licensees.php">Licensees</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'generate' ? 'active' : ''; ?>" href="admin-licensees.php?tab=generate">Licencee Gen</a></li>
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
    
    <?php if ($tab === 'generate'): ?>
        <?php include __DIR__ . '/admin-generate-licensees-tab.php'; ?>
    <?php else: ?>
    <div class="alert alert-info">
        Adjust these rows to control the "Licensee Name" income table and navigation shortcuts on the dashboard. Amounts accept plain numbers (decimals allowed) and will be formatted automatically.
    </div>
    <form method="post" id="licenseeForm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Licensee Entries</h5>
            <button type="button" class="btn btn-success btn-sm" id="addLicenseeBtn">
                ➕ Add New Licensee
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-dark table-striped" id="licenseeTable">
                <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th style="min-width: 200px;">Licensee Name</th>
                    <th class="text-center">Show in Nav</th>
                    <th class="text-center">In Filter</th>
                    <th class="text-center">Show in Table</th>
                    <th>Transactional Fee</th>
                    <th>Monthly Amount</th>
                    <th>Licensee Fee</th>
                    <th>Lending Funds Received</th>
                    <th>Total Income</th>
                    <th style="width: 80px;">Actions</th>
                </tr>
                </thead>
                <tbody id="licenseeTableBody">
                <?php foreach ($licensees as $index => $row): ?>
                    <tr data-index="<?php echo $index; ?>">
                        <td>
                            <input type="text" class="form-control" name="licensees[<?php echo $index; ?>][licenseeId]" value="<?php echo displayText(isset($row['licenseeId']) ? $row['licenseeId'] : ($index + 1)); ?>">
                            <input type="hidden" name="licensees[<?php echo $index; ?>][isAggregate]" value="<?php echo !empty($row['isAggregate']) ? '1' : '0'; ?>">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="licensees[<?php echo $index; ?>][licenseeName]" value="<?php echo displayText(isset($row['licenseeName']) ? $row['licenseeName'] : ''); ?>">
                        </td>
                        <td class="checkbox-cell">
                            <input type="hidden" name="licensees[<?php echo $index; ?>][showInNav]" value="0">
                            <input type="checkbox" name="licensees[<?php echo $index; ?>][showInNav]" value="1" <?php echo !empty($row['showInNav']) ? 'checked' : ''; ?>>
                        </td>
                        <td class="checkbox-cell">
                            <input type="hidden" name="licensees[<?php echo $index; ?>][includeInSelect]" value="0">
                            <?php $includeInSelect = array_key_exists('includeInSelect', $row) ? !empty($row['includeInSelect']) : true; ?>
                            <input type="checkbox" name="licensees[<?php echo $index; ?>][includeInSelect]" value="1" <?php echo $includeInSelect ? 'checked' : ''; ?>>
                        </td>
                        <td class="checkbox-cell">
                            <input type="hidden" name="licensees[<?php echo $index; ?>][showInTable]" value="0">
                            <?php
                            $defaultShowInTable = !empty($row['isAggregate']) ? true : !empty($row['showInNav']);
                            $showInTable = array_key_exists('showInTable', $row) ? !empty($row['showInTable']) : $defaultShowInTable;
                            ?>
                            <input type="checkbox" name="licensees[<?php echo $index; ?>][showInTable]" value="1" <?php echo $showInTable ? 'checked' : ''; ?>>
                        </td>
                        <td><input type="text" class="form-control" name="licensees[<?php echo $index; ?>][transactionalFee]" value="<?php echo displayCurrencyInput(isset($row['transactionalFee']) ? $row['transactionalFee'] : (isset($row['totalFeeAmount']) ? $row['totalFeeAmount'] : '')); ?>"></td>
                        <td><input type="text" class="form-control" name="licensees[<?php echo $index; ?>][monthlyAmount]" value="<?php echo displayCurrencyInput(isset($row['monthlyAmount']) ? $row['monthlyAmount'] : (isset($row['totalMonthlyAmount']) ? $row['totalMonthlyAmount'] : '')); ?>"></td>
                        <td><input type="text" class="form-control" name="licensees[<?php echo $index; ?>][licenseeFee]" value="<?php echo displayCurrencyInput(isset($row['licenseeFee']) ? $row['licenseeFee'] : (isset($row['totalFirstAmount']) ? $row['totalFirstAmount'] : '')); ?>"></td>
                        <td><input type="text" class="form-control" name="licensees[<?php echo $index; ?>][lendingFundsReceived]" value="<?php echo displayCurrencyInput(isset($row['lendingFundsReceived']) ? $row['lendingFundsReceived'] : (isset($row['totalBalanceAmount']) ? $row['totalBalanceAmount'] : '')); ?>"></td>
                        <td><input type="text" class="form-control" name="licensees[<?php echo $index; ?>][totalIncome]" value="<?php echo displayCurrencyInput(isset($row['totalIncome']) ? $row['totalIncome'] : ''); ?>"></td>
                        <td>
                            <?php if (empty($row['isAggregate'])): ?>
                                <button type="button" class="btn btn-danger btn-sm remove-row" title="Remove this licensee">🗑️</button>
                            <?php else: ?>
                                <span class="text-muted small">Aggregate</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-3">Save Licensee Data</button>
    </form>
</div>

<script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
<script>
    $(document).ready(function() {
        // Function to get the next available index for form array
        function getNextIndex() {
            var maxIndex = -1;
            $('#licenseeTableBody tr').each(function() {
                var index = parseInt($(this).data('index')) || -1;
                if (index > maxIndex) {
                    maxIndex = index;
                }
            });
            return maxIndex + 1;
        }
        
        // Function to get the next available licensee ID
        function getNextLicenseeId() {
            var maxId = 0;
            $('#licenseeTableBody tr').each(function() {
                var idInput = $(this).find('input[name*="[licenseeId]"]');
                var id = parseInt(idInput.val()) || 0;
                if (id > maxId && id !== 999) { // Don't count the "All" aggregate row
                    maxId = id;
                }
            });
            return maxId + 1;
        }
        
        // Add new licensee row
        $('#addLicenseeBtn').on('click', function() {
            var newIndex = getNextIndex();
            var newId = getNextLicenseeId();
            var newRow = '<tr data-index="' + newIndex + '">' +
                '<td>' +
                    '<input type="text" class="form-control" name="licensees[' + newIndex + '][licenseeId]" value="' + newId + '">' +
                    '<input type="hidden" name="licensees[' + newIndex + '][isAggregate]" value="0">' +
                '</td>' +
                '<td>' +
                    '<input type="text" class="form-control" name="licensees[' + newIndex + '][licenseeName]" value="" placeholder="Enter licensee name">' +
                '</td>' +
                '<td class="checkbox-cell">' +
                    '<input type="hidden" name="licensees[' + newIndex + '][showInNav]" value="0">' +
                    '<input type="checkbox" name="licensees[' + newIndex + '][showInNav]" value="1" checked>' +
                '</td>' +
                '<td class="checkbox-cell">' +
                    '<input type="hidden" name="licensees[' + newIndex + '][includeInSelect]" value="0">' +
                    '<input type="checkbox" name="licensees[' + newIndex + '][includeInSelect]" value="1" checked>' +
                '</td>' +
                '<td class="checkbox-cell">' +
                    '<input type="hidden" name="licensees[' + newIndex + '][showInTable]" value="0">' +
                    '<input type="checkbox" name="licensees[' + newIndex + '][showInTable]" value="1" checked>' +
                '</td>' +
                '<td><input type="text" class="form-control" name="licensees[' + newIndex + '][transactionalFee]" value="0"></td>' +
                '<td><input type="text" class="form-control" name="licensees[' + newIndex + '][monthlyAmount]" value="0"></td>' +
                '<td><input type="text" class="form-control" name="licensees[' + newIndex + '][licenseeFee]" value="0"></td>' +
                '<td><input type="text" class="form-control" name="licensees[' + newIndex + '][lendingFundsReceived]" value="0"></td>' +
                '<td><input type="text" class="form-control" name="licensees[' + newIndex + '][totalIncome]" value="0"></td>' +
                '<td>' +
                    '<button type="button" class="btn btn-danger btn-sm remove-row" title="Remove this licensee">🗑️</button>' +
                '</td>' +
            '</tr>';
            
            // Insert before the "All" aggregate row if it exists, otherwise append
            var aggregateRow = $('#licenseeTableBody tr').filter(function() {
                return $(this).find('input[name*="[isAggregate]"]').val() === '1';
            });
            
            if (aggregateRow.length > 0) {
                aggregateRow.before(newRow);
            } else {
                $('#licenseeTableBody').append(newRow);
            }
        });
        
        // Remove row
        $(document).on('click', '.remove-row', function() {
            if (confirm('Are you sure you want to remove this licensee? This action cannot be undone.')) {
                $(this).closest('tr').remove();
            }
        });
    });
</script>
    <?php endif; ?>
</div>
</body>
</html>

