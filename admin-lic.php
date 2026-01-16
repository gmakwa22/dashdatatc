<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors, but log them
ini_set('log_errors', 1);

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'metrics';
$profileId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
if ($profileId < 1) {
    $profileId = 1;
}

$metricsPath = __DIR__ . '/data/lic-' . $profileId . '-metrics.json';
$loanGraphPath = __DIR__ . '/data/lic-' . $profileId . '-loan-graph.json';
$fundingPath = __DIR__ . '/data/lic-' . $profileId . '-funding.json';
$agingPath = __DIR__ . '/data/lic-' . $profileId . '-aging.json';
$projectionsPath = __DIR__ . '/data/lic-' . $profileId . '-projections.json';

// Initialize messages array
$messages = array();

// Handle profile creation (copy from profile 1)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_profile'])) {
    $newProfileId = isset($_POST['new_profile_id']) ? (int)$_POST['new_profile_id'] : 0;
    if ($newProfileId > 0 && $newProfileId != 1) {
        $sourceFiles = array(
            'metrics' => __DIR__ . '/data/lic-1-metrics.json',
            'loan-graph' => __DIR__ . '/data/lic-1-loan-graph.json',
            'funding' => __DIR__ . '/data/lic-1-funding.json',
            'aging' => __DIR__ . '/data/lic-1-aging.json',
            'projections' => __DIR__ . '/data/lic-1-projections.json',
        );
        $targetFiles = array(
            'metrics' => __DIR__ . '/data/lic-' . $newProfileId . '-metrics.json',
            'loan-graph' => __DIR__ . '/data/lic-' . $newProfileId . '-loan-graph.json',
            'funding' => __DIR__ . '/data/lic-' . $newProfileId . '-funding.json',
            'aging' => __DIR__ . '/data/lic-' . $newProfileId . '-aging.json',
            'projections' => __DIR__ . '/data/lic-' . $newProfileId . '-projections.json',
        );
        $copied = 0;
        $errors = array();
        foreach ($sourceFiles as $key => $source) {
            if (file_exists($source)) {
                // Read the source file content
                $content = @file_get_contents($source);
                if ($content !== false && $content !== '') {
                    // Ensure target directory exists
                    $targetDir = dirname($targetFiles[$key]);
                    if (!is_dir($targetDir)) {
                        @mkdir($targetDir, 0775, true);
                    }
                    // Write to target file (force overwrite if exists)
                    $bytesWritten = @file_put_contents($targetFiles[$key], $content);
                    if ($bytesWritten !== false && $bytesWritten > 0) {
                        $copied++;
                    } else {
                        $errors[] = 'Failed to write ' . basename($targetFiles[$key]) . ' (wrote ' . $bytesWritten . ' bytes)';
                    }
                } else {
                    $errors[] = 'Failed to read or empty content from ' . basename($source);
                }
            } else {
                $errors[] = 'Source file not found: ' . basename($source);
            }
        }
        if ($copied > 0) {
            $messages[] = array('type' => 'success', 'text' => 'Profile ' . $newProfileId . ' created successfully with ' . $copied . ' data files copied from profile 1.');
            if (count($errors) > 0) {
                $messages[] = array('type' => 'warning', 'text' => 'Some files had issues: ' . implode(', ', $errors));
            }
            $profileId = $newProfileId;
            $metricsPath = $targetFiles['metrics'];
            $loanGraphPath = $targetFiles['loan-graph'];
            $fundingPath = $targetFiles['funding'];
            $agingPath = $targetFiles['aging'];
            $projectionsPath = $targetFiles['projections'];
        } else {
            $errorMsg = 'Failed to create profile. ';
            if (count($errors) > 0) {
                $errorMsg .= implode(', ', $errors);
            } else {
                $errorMsg .= 'Make sure profile 1 data files exist.';
            }
            $messages[] = array('type' => 'error', 'text' => $errorMsg);
        }
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Invalid profile ID. Must be greater than 0 and not 1.');
    }
}

// Get list of existing profiles
function getExistingProfiles() {
    $profiles = array();
    $dataDir = __DIR__ . '/data';
    if (is_dir($dataDir)) {
        $files = scandir($dataDir);
        foreach ($files as $file) {
            if (preg_match('/^lic-(\d+)-metrics\.json$/', $file, $matches)) {
                $profiles[] = (int)$matches[1];
            }
        }
        sort($profiles);
    }
    return $profiles;
}
$existingProfiles = getExistingProfiles();

// Data is now loaded only from external JSON files - no hardcoded defaults
// This ensures code changes don't affect the data

function ensureLicDataFileExists($path)
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
        $default = array(
            'licensee' => array(
                'websiteName' => '',
                'licenseeName' => '',
            ),
            'availableBalance' => array(
                'available' => 0,
                'paymentInProgress' => 0,
                'accountBalance' => 0,
                'transfersBalance' => 0,
                'cumulativeBalance' => 0,
            ),
            'capitalAccount' => array(
                'capitalDisbursed' => 0,
                'totalCapital' => 0,
                'capitalBalance' => 0,
                'transfers' => 0,
            ),
            'roi' => array(
                'percentage' => 0,
                'interestEarned' => 0,
                'lateInterestFeesEarned' => 0,
            ),
            'projectedRoi' => array(
                'projected' => 0,
                'annualized' => 0,
            ),
            'meta' => array(
                'updatedAt' => date(DATE_ATOM),
            ),
        );
        @file_put_contents($path, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

function cleanCurrencyInput($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = str_replace(array(',', '$', ' '), '', $value);
    $numeric = preg_replace('/[^0-9.\-]/', '', $numeric);
    return $numeric === '' ? 0 : (float)$numeric;
}

function cleanFloat($value)
{
    if ($value === null || $value === '') {
        return 0;
    }
    $numeric = str_replace(array(',', '$', ' '), '', $value);
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

function ensureLicLoanDataFileExists($path)
{
    if (!file_exists($path)) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true)) {
                error_log("Warning: Could not create directory: " . $dir);
            }
        }
        $payload = array(
            'loans' => array(),
            'meta' => array('updatedAt' => date(DATE_ATOM)),
        );
        @file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

function ensureLicFundingDataFileExists($path)
{
    if (!file_exists($path)) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true)) {
                error_log("Warning: Could not create directory: " . $dir);
            }
        }
        $default = array(
            'funding' => array(
                'capitalProvided' => 0,
                'minimumCapitalRequired' => 0,
                'excessShort' => 0,
            ),
            'meta' => array('updatedAt' => date(DATE_ATOM)),
        );
        @file_put_contents($path, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

function ensureLicAgingDataFileExists($path)
{
    if (!file_exists($path)) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true)) {
                error_log("Warning: Could not create directory: " . $dir);
            }
        }
        $payload = array(
            'aging' => array(),
            'meta' => array('updatedAt' => date(DATE_ATOM)),
        );
        @file_put_contents($path, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

function ensureLicProjectionsDataFileExists($path)
{
    if (!file_exists($path)) {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            if (!@mkdir($dir, 0775, true)) {
                error_log("Warning: Could not create directory: " . $dir);
            }
        }
        $default = array(
            'projections' => array(
                'investment' => 0,
                'turnoverOfMoney' => 0,
                'moneyAvailableForLoanDisbursement' => 0,
                'estimatedEarningOfInterest' => 0,
                'estimatedBadDebts' => 0,
                'estimatedInterest' => 0,
                'projectedRoi' => 0,
            ),
            'meta' => array('updatedAt' => date(DATE_ATOM)),
        );
        @file_put_contents($path, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
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

// Initialize variables
$data = array();
$licensee = array();
$availableBalance = array();
$capitalAccount = array();
$roi = array();
$projectedRoi = array();
$updatedAt = null;

// Loan graph variables
$loans = array();
$loanGraphUpdatedAt = null;

// Funding variables
$funding = array();
$fundingUpdatedAt = null;

// Aging variables
$agingRows = array();
$agingUpdatedAt = null;

// Projections variables
$projections = array();
$projectionsUpdatedAt = null;

// Load data based on active tab
if ($tab === 'metrics') {
    ensureLicDataFileExists($metricsPath);

    // Try to load metrics data
    if (file_exists($metricsPath)) {
        $dataContent = file_get_contents($metricsPath);
        if ($dataContent !== false) {
            $data = json_decode($dataContent, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
                $messages[] = array('type' => 'error', 'text' => 'Error reading license metrics file: ' . $errorMsg);
                $data = array();
            } else {
                $licensee = isset($data['licensee']) ? $data['licensee'] : array();
                $availableBalance = isset($data['availableBalance']) ? $data['availableBalance'] : array();
                $capitalAccount = isset($data['capitalAccount']) ? $data['capitalAccount'] : array();
                $roi = isset($data['roi']) ? $data['roi'] : array();
                $projectedRoi = isset($data['projectedRoi']) ? $data['projectedRoi'] : array();
                $updatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
            }
        } else {
            $messages[] = array('type' => 'error', 'text' => 'Unable to read license metrics file. Check file permissions.');
        }
    } else {
        $messages[] = array('type' => 'warning', 'text' => 'License metrics file not found. It will be created when you save.');
    }
} elseif ($tab === 'loangraph') {
    ensureLicLoanDataFileExists($loanGraphPath);
    // Try to load loan graph data
    if (file_exists($loanGraphPath)) {
        $dataContent = file_get_contents($loanGraphPath);
        if ($dataContent !== false) {
            $data = json_decode($dataContent, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
                $messages[] = array('type' => 'error', 'text' => 'Error reading loan graph file: ' . $errorMsg);
                $data = array();
            } else {
                $loans = isset($data['loans']) ? $data['loans'] : array();
                $loanGraphUpdatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
            }
        } else {
            $messages[] = array('type' => 'error', 'text' => 'Unable to read loan graph file. Check file permissions.');
        }
    } else {
        $messages[] = array('type' => 'warning', 'text' => 'Loan graph file not found. It will be created when you save.');
    }
    if (empty($loans)) {
        $messages[] = array('type' => 'warning', 'text' => 'No loan data found. Please add loan entries below and save.');
    }
} elseif ($tab === 'funding') {
    ensureLicFundingDataFileExists($fundingPath);
    if (file_exists($fundingPath)) {
        $dataContent = file_get_contents($fundingPath);
        if ($dataContent !== false) {
            $data = json_decode($dataContent, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
                $messages[] = array('type' => 'error', 'text' => 'Error reading funding file: ' . $errorMsg);
                $data = array();
            } else {
                $funding = isset($data['funding']) ? $data['funding'] : array();
                $fundingUpdatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
            }
        } else {
            $messages[] = array('type' => 'error', 'text' => 'Unable to read funding file. Check file permissions.');
        }
    } else {
        $messages[] = array('type' => 'warning', 'text' => 'Funding file not found. It will be created when you save.');
    }
} elseif ($tab === 'aging') {
    ensureLicAgingDataFileExists($agingPath);
    if (file_exists($agingPath)) {
        $dataContent = file_get_contents($agingPath);
        if ($dataContent !== false) {
            $data = json_decode($dataContent, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
                $messages[] = array('type' => 'error', 'text' => 'Error reading aging file: ' . $errorMsg);
                $data = array();
            } else {
                $agingRows = isset($data['aging']) ? $data['aging'] : array();
                $agingUpdatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
            }
        } else {
            $messages[] = array('type' => 'error', 'text' => 'Unable to read aging file. Check file permissions.');
        }
    } else {
        $messages[] = array('type' => 'warning', 'text' => 'Aging file not found. It will be created when you save.');
    }
    if (empty($agingRows)) {
        $messages[] = array('type' => 'warning', 'text' => 'No aging data found. Please add aging entries below and save.');
    }
} elseif ($tab === 'projections') {
    ensureLicProjectionsDataFileExists($projectionsPath);
    if (file_exists($projectionsPath)) {
        $dataContent = file_get_contents($projectionsPath);
        if ($dataContent !== false) {
            $data = json_decode($dataContent, true);
            if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
                $messages[] = array('type' => 'error', 'text' => 'Error reading projections file: ' . $errorMsg);
                $data = array();
            } else {
                $projections = isset($data['projections']) ? $data['projections'] : array();
                $projectionsUpdatedAt = isset($data['meta']['updatedAt']) ? $data['meta']['updatedAt'] : null;
            }
        } else {
            $messages[] = array('type' => 'error', 'text' => 'Unable to read projections file. Check file permissions.');
        }
    } else {
        $messages[] = array('type' => 'warning', 'text' => 'Projections file not found. It will be created when you save.');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $tab === 'metrics') {
    $payload = array(
        'licensee' => array(
            'websiteName' => cleanString(getValue($_POST, 'licensee_websiteName', '')),
            'licenseeName' => cleanString(getValue($_POST, 'licensee_licenseeName', '')),
        ),
        'availableBalance' => array(
            'available' => cleanCurrencyInput(getValue($_POST, 'availableBalance_available', '')),
            'paymentInProgress' => cleanCurrencyInput(getValue($_POST, 'availableBalance_paymentInProgress', '')),
            'accountBalance' => cleanCurrencyInput(getValue($_POST, 'availableBalance_accountBalance', '')),
            'transfersBalance' => cleanCurrencyInput(getValue($_POST, 'availableBalance_transfersBalance', '')),
            'cumulativeBalance' => cleanCurrencyInput(getValue($_POST, 'availableBalance_cumulativeBalance', '')),
        ),
        'capitalAccount' => array(
            'capitalDisbursed' => cleanCurrencyInput(getValue($_POST, 'capitalAccount_capitalDisbursed', '')),
            'totalCapital' => cleanCurrencyInput(getValue($_POST, 'capitalAccount_totalCapital', '')),
            'capitalBalance' => cleanCurrencyInput(getValue($_POST, 'capitalAccount_capitalBalance', '')),
            'transfers' => cleanCurrencyInput(getValue($_POST, 'capitalAccount_transfers', '')),
        ),
        'roi' => array(
            'percentage' => cleanCurrencyInput(getValue($_POST, 'roi_percentage', '')),
            'interestEarned' => cleanCurrencyInput(getValue($_POST, 'roi_interestEarned', '')),
            'lateInterestFeesEarned' => cleanCurrencyInput(getValue($_POST, 'roi_lateInterestFeesEarned', '')),
        ),
        'projectedRoi' => array(
            'projected' => cleanCurrencyInput(getValue($_POST, 'projectedRoi_projected', '')),
            'annualized' => cleanCurrencyInput(getValue($_POST, 'projectedRoi_annualized', '')),
        ),
        'meta' => array(
            'updatedAt' => date(DATE_ATOM),
        ),
    );

    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if (@file_put_contents($metricsPath, $json)) {
        $messages[] = array('type' => 'success', 'text' => 'License metrics updated.');
        // Reload data
        $licensee = $payload['licensee'];
        $availableBalance = $payload['availableBalance'];
        $capitalAccount = $payload['capitalAccount'];
        $roi = $payload['roi'];
        $projectedRoi = $payload['projectedRoi'];
        $updatedAt = $payload['meta']['updatedAt'];
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save license metrics.');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $tab === 'loangraph') {
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
            'activeCollection' => cleanFloat(getValue($loan, 'activeCollection', 0)),
        );
    }

    // Require at least one loan entry
    if (!count($normalized)) {
        $messages[] = array('type' => 'error', 'text' => 'At least one loan entry is required.');
        $normalized = $loans;
    }

    $payload = array(
        'loans' => array_values($normalized),
        'meta' => array('updatedAt' => date(DATE_ATOM)),
    );

    if (@file_put_contents($loanGraphPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        $loans = $payload['loans'];
        $loanGraphUpdatedAt = $payload['meta']['updatedAt'];
        $messages[] = array('type' => 'success', 'text' => 'Loan graph totals updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save loan graph data.');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $tab === 'funding') {
    $payload = array(
        'funding' => array(
            'capitalProvided' => cleanCurrencyInput(getValue($_POST, 'funding_capitalProvided', '')),
            'minimumCapitalRequired' => cleanCurrencyInput(getValue($_POST, 'funding_minimumCapitalRequired', '')),
            'excessShort' => cleanCurrencyInput(getValue($_POST, 'funding_excessShort', '')),
        ),
        'meta' => array('updatedAt' => date(DATE_ATOM)),
    );
    if (@file_put_contents($fundingPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        $funding = $payload['funding'];
        $fundingUpdatedAt = $payload['meta']['updatedAt'];
        $messages[] = array('type' => 'success', 'text' => 'Funding data updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save funding data.');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $tab === 'aging') {
    $inputRows = isset($_POST['aging']) ? $_POST['aging'] : array();
    $normalized = array();
    foreach ($inputRows as $index => $row) {
        $normalized[] = array(
            'label' => cleanString(getValue($row, 'label', 'Row ' . ($index + 1))),
            'loanDisbursement' => cleanFloat(getValue($row, 'loanDisbursement', 0)),
            'interest' => cleanFloat(getValue($row, 'interest', 0)),
            'partialPayment' => cleanFloat(getValue($row, 'partialPayment', 0)),
            'totalDebt' => cleanFloat(getValue($row, 'totalDebt', 0)),
            'percent' => cleanFloat(getValue($row, 'percent', 0)),
        );
    }
    if (!count($normalized)) {
        $messages[] = array('type' => 'error', 'text' => 'At least one aging entry is required.');
        $normalized = $agingRows;
    }
    $payload = array(
        'aging' => array_values($normalized),
        'meta' => array('updatedAt' => date(DATE_ATOM)),
    );
    if (@file_put_contents($agingPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        $agingRows = $payload['aging'];
        $agingUpdatedAt = $payload['meta']['updatedAt'];
        $messages[] = array('type' => 'success', 'text' => 'Aging analysis updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save aging data.');
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && $tab === 'projections') {
    $payload = array(
        'projections' => array(
            'investment' => cleanCurrencyInput(getValue($_POST, 'projections_investment', '')),
            'turnoverOfMoney' => cleanCurrencyInput(getValue($_POST, 'projections_turnoverOfMoney', '')),
            'moneyAvailableForLoanDisbursement' => cleanCurrencyInput(getValue($_POST, 'projections_moneyAvailableForLoanDisbursement', '')),
            'estimatedEarningOfInterest' => cleanCurrencyInput(getValue($_POST, 'projections_estimatedEarningOfInterest', '')),
            'estimatedBadDebts' => cleanCurrencyInput(getValue($_POST, 'projections_estimatedBadDebts', '')),
            'estimatedInterest' => cleanCurrencyInput(getValue($_POST, 'projections_estimatedInterest', '')),
            'projectedRoi' => cleanCurrencyInput(getValue($_POST, 'projections_projectedRoi', '')),
        ),
        'meta' => array('updatedAt' => date(DATE_ATOM)),
    );
    if (@file_put_contents($projectionsPath, json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        $projections = $payload['projections'];
        $projectionsUpdatedAt = $payload['meta']['updatedAt'];
        $messages[] = array('type' => 'success', 'text' => 'Projections updated.');
    } else {
        $messages[] = array('type' => 'error', 'text' => 'Unable to save projections data.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Metrics Admin</title>
    <link rel="stylesheet" href="dist/vendors/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background: #1a1a2e;
            color: #e0e0e0;
            padding: 20px;
        }
        .card {
            background: #16213e;
            border: 1px solid #2c2c3d;
            color: #e0e0e0;
        }
        .form-control {
            background: #0f1419;
            border: 1px solid #2c2c3d;
            color: #e0e0e0;
        }
        .form-control:focus {
            background: #0f1419;
            border-color: #5e5efc;
            color: #e0e0e0;
            box-shadow: none;
        }
        .btn-primary {
            background: #5e5efc;
            border-color: #5e5efc;
        }
        .badge-success {
            background-color: #198754;
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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">License Page Admin - Profile <?php echo $profileId; ?></h1>
        <div>
            <a href="lic.php?id=<?php echo $profileId; ?>" class="btn btn-outline-light btn-sm">View License Page</a>
            <a href="admin.php" class="btn btn-outline-light btn-sm ml-2">Main Dashboard</a>
        </div>
    </div>
    <div class="card p-3 mb-4">
        <div class="row align-items-end">
            <div class="col-md-4">
                <label for="profile_selector">Select Profile:</label>
                <select id="profile_selector" class="form-control" onchange="window.location.href='?id=' + this.value + '&tab=<?php echo $tab; ?>'">
                    <?php foreach ($existingProfiles as $pid): ?>
                        <option value="<?php echo $pid; ?>" <?php echo $pid == $profileId ? 'selected' : ''; ?>>Profile <?php echo $pid; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <form method="post" class="d-inline">
                    <input type="hidden" name="create_profile" value="1">
                    <div class="input-group">
                        <input type="number" name="new_profile_id" class="form-control" placeholder="New Profile ID" min="2" required>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-success">Create New Profile</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-right">
                <small class="text-muted">New profiles copy data from Profile 1</small>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs admin-nav mb-4">
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'metrics' ? 'active' : ''; ?>" href="?id=<?php echo $profileId; ?>&tab=metrics">Metrics</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'loangraph' ? 'active' : ''; ?>" href="?id=<?php echo $profileId; ?>&tab=loangraph">Loan Graph</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'funding' ? 'active' : ''; ?>" href="?id=<?php echo $profileId; ?>&tab=funding">Funding</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'aging' ? 'active' : ''; ?>" href="?id=<?php echo $profileId; ?>&tab=aging">Aging Analysis</a></li>
        <li class="nav-item"><a class="nav-link <?php echo $tab === 'projections' ? 'active' : ''; ?>" href="?id=<?php echo $profileId; ?>&tab=projections">Projections</a></li>
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
    <?php if ($tab === 'metrics'): ?>
        <?php if ($updatedAt): ?>
            <p><span class="badge badge-success">Last updated: <?php echo htmlspecialchars($updatedAt, ENT_QUOTES, 'UTF-8'); ?></span></p>
        <?php endif; ?>
        <form method="post">
            <div class="card h-100 p-3 mb-4">
                <h5>Licensee Section</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="licensee_websiteName">Website Name</label>
                            <input type="text" class="form-control" id="licensee_websiteName" name="licensee_websiteName" value="<?php echo htmlspecialchars(isset($licensee['websiteName']) ? $licensee['websiteName'] : '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="licensee_licenseeName">Licensee Name</label>
                            <input type="text" class="form-control" id="licensee_licenseeName" name="licensee_licenseeName" value="<?php echo htmlspecialchars(isset($licensee['licenseeName']) ? $licensee['licenseeName'] : '', ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>Available Balance</h5>
                    <div class="form-group">
                        <label for="availableBalance_available">Available Balance</label>
                        <input type="text" class="form-control" id="availableBalance_available" name="availableBalance_available" value="<?php echo displayNumber(isset($availableBalance['available']) ? $availableBalance['available'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="availableBalance_paymentInProgress">Payment In Progress</label>
                        <input type="text" class="form-control" id="availableBalance_paymentInProgress" name="availableBalance_paymentInProgress" value="<?php echo displayNumber(isset($availableBalance['paymentInProgress']) ? $availableBalance['paymentInProgress'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="availableBalance_accountBalance">Account Balance</label>
                        <input type="text" class="form-control" id="availableBalance_accountBalance" name="availableBalance_accountBalance" value="<?php echo displayNumber(isset($availableBalance['accountBalance']) ? $availableBalance['accountBalance'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="availableBalance_transfersBalance">Transfers Balance</label>
                        <input type="text" class="form-control" id="availableBalance_transfersBalance" name="availableBalance_transfersBalance" value="<?php echo displayNumber(isset($availableBalance['transfersBalance']) ? $availableBalance['transfersBalance'] : ''); ?>" />
                    </div>
                    <div class="form-group mb-0">
                        <label for="availableBalance_cumulativeBalance">Cumulative Balance</label>
                        <input type="text" class="form-control" id="availableBalance_cumulativeBalance" name="availableBalance_cumulativeBalance" value="<?php echo displayNumber(isset($availableBalance['cumulativeBalance']) ? $availableBalance['cumulativeBalance'] : ''); ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>Capital Account</h5>
                    <div class="form-group">
                        <label for="capitalAccount_capitalDisbursed">Capital Disbursed</label>
                        <input type="text" class="form-control" id="capitalAccount_capitalDisbursed" name="capitalAccount_capitalDisbursed" value="<?php echo displayNumber(isset($capitalAccount['capitalDisbursed']) ? $capitalAccount['capitalDisbursed'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="capitalAccount_totalCapital">Total Capital</label>
                        <input type="text" class="form-control" id="capitalAccount_totalCapital" name="capitalAccount_totalCapital" value="<?php echo displayNumber(isset($capitalAccount['totalCapital']) ? $capitalAccount['totalCapital'] : ''); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="capitalAccount_capitalBalance">Capital Balance</label>
                        <input type="text" class="form-control" id="capitalAccount_capitalBalance" name="capitalAccount_capitalBalance" value="<?php echo displayNumber(isset($capitalAccount['capitalBalance']) ? $capitalAccount['capitalBalance'] : ''); ?>" />
                    </div>
                    <div class="form-group mb-0">
                        <label for="capitalAccount_transfers">Transfers</label>
                        <input type="text" class="form-control" id="capitalAccount_transfers" name="capitalAccount_transfers" value="<?php echo displayNumber(isset($capitalAccount['transfers']) ? $capitalAccount['transfers'] : ''); ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>ROI</h5>
                    <div class="form-group">
                        <label for="roi_percentage">ROI Percentage</label>
                        <input type="text" class="form-control" id="roi_percentage" name="roi_percentage" value="<?php echo displayNumber(isset($roi['percentage']) ? $roi['percentage'] : '', 2); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="roi_interestEarned">Interest Earned</label>
                        <input type="text" class="form-control" id="roi_interestEarned" name="roi_interestEarned" value="<?php echo displayNumber(isset($roi['interestEarned']) ? $roi['interestEarned'] : ''); ?>" />
                    </div>
                    <div class="form-group mb-0">
                        <label for="roi_lateInterestFeesEarned">Late Interest & Late Fees Earned</label>
                        <input type="text" class="form-control" id="roi_lateInterestFeesEarned" name="roi_lateInterestFeesEarned" value="<?php echo displayNumber(isset($roi['lateInterestFeesEarned']) ? $roi['lateInterestFeesEarned'] : ''); ?>" />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3">
                    <h5>Projected ROI</h5>
                    <div class="form-group">
                        <label for="projectedRoi_projected">Projected ROI %</label>
                        <input type="text" class="form-control" id="projectedRoi_projected" name="projectedRoi_projected" value="<?php echo displayNumber(isset($projectedRoi['projected']) ? $projectedRoi['projected'] : '', 2); ?>" />
                    </div>
                    <div class="form-group mb-0">
                        <label for="projectedRoi_annualized">Annualized ROI %</label>
                        <input type="text" class="form-control" id="projectedRoi_annualized" name="projectedRoi_annualized" value="<?php echo displayNumber(isset($projectedRoi['annualized']) ? $projectedRoi['annualized'] : '', 2); ?>" />
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-lg mt-3">Save License Metrics</button>
        </form>
    <?php elseif ($tab === 'loangraph'): ?>
        <?php if ($loanGraphUpdatedAt): ?>
            <p><span class="badge badge-success">Last updated: <?php echo htmlspecialchars($loanGraphUpdatedAt, ENT_QUOTES, 'UTF-8'); ?></span></p>
        <?php endif; ?>
        <div class="alert alert-info">
            <strong>Tip:</strong> The license page pie chart recalculates automatically from the loan data. Update the values and click <strong>Save Loan Totals</strong>, then refresh the license page.
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
                        <th>Active Collection</th>
                        <th>% of Total (Auto)</th>
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
                                <input type="text" class="form-control" name="loans[<?php echo $index; ?>][activeCollection]" value="<?php echo displayValue(isset($loan['activeCollection']) ? $loan['activeCollection'] : ''); ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="loans[<?php echo $index; ?>][varietyPercent]" value="<?php echo displayValue(isset($loan['varietyPercent']) ? $loan['varietyPercent'] : '', 2); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary btn-lg mt-3">Save Loan Totals</button>
        </form>
    <?php elseif ($tab === 'funding'): ?>
        <?php if ($fundingUpdatedAt): ?>
            <p><span class="badge badge-success">Last updated: <?php echo htmlspecialchars($fundingUpdatedAt, ENT_QUOTES, 'UTF-8'); ?></span></p>
        <?php endif; ?>
        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 p-3">
                        <h5>Funding</h5>
                        <div class="form-group">
                            <label for="funding_capitalProvided">Capital Provided Up To Today</label>
                            <input type="text" class="form-control" id="funding_capitalProvided" name="funding_capitalProvided" value="<?php echo displayNumber(isset($funding['capitalProvided']) ? $funding['capitalProvided'] : ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="funding_minimumCapitalRequired">Minimum Capital Required For Lending</label>
                            <input type="text" class="form-control" id="funding_minimumCapitalRequired" name="funding_minimumCapitalRequired" value="<?php echo displayNumber(isset($funding['minimumCapitalRequired']) ? $funding['minimumCapitalRequired'] : ''); ?>" />
                        </div>
                        <div class="form-group mb-0">
                            <label for="funding_excessShort">Excess/(Short)</label>
                            <input type="text" class="form-control" id="funding_excessShort" name="funding_excessShort" value="<?php echo displayNumber(isset($funding['excessShort']) ? $funding['excessShort'] : ''); ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg mt-3">Save Funding</button>
        </form>
    <?php elseif ($tab === 'aging'): ?>
        <?php if ($agingUpdatedAt): ?>
            <p><span class="badge badge-success">Last updated: <?php echo htmlspecialchars($agingUpdatedAt, ENT_QUOTES, 'UTF-8'); ?></span></p>
        <?php endif; ?>
        <form method="post">
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead>
                    <tr>
                        <th style="min-width: 150px;">Aging</th>
                        <th>Loan Disbursement</th>
                        <th>Interest</th>
                        <th>Partial Payment</th>
                        <th>Total Debt</th>
                        <th>% of Total Loans</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($agingRows as $index => $row): ?>
                        <tr>
                            <td>
                                <input type="text" class="form-control" name="aging[<?php echo $index; ?>][label]" value="<?php echo htmlspecialchars(isset($row['label']) ? $row['label'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="aging[<?php echo $index; ?>][loanDisbursement]" value="<?php echo displayValue(isset($row['loanDisbursement']) ? $row['loanDisbursement'] : ''); ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="aging[<?php echo $index; ?>][interest]" value="<?php echo displayValue(isset($row['interest']) ? $row['interest'] : ''); ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="aging[<?php echo $index; ?>][partialPayment]" value="<?php echo displayValue(isset($row['partialPayment']) ? $row['partialPayment'] : ''); ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="aging[<?php echo $index; ?>][totalDebt]" value="<?php echo displayValue(isset($row['totalDebt']) ? $row['totalDebt'] : ''); ?>">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="aging[<?php echo $index; ?>][percent]" value="<?php echo displayValue(isset($row['percent']) ? $row['percent'] : '', 2); ?>">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-primary btn-lg mt-3">Save Aging Analysis</button>
        </form>
    <?php elseif ($tab === 'projections'): ?>
        <?php if ($projectionsUpdatedAt): ?>
            <p><span class="badge badge-success">Last updated: <?php echo htmlspecialchars($projectionsUpdatedAt, ENT_QUOTES, 'UTF-8'); ?></span></p>
        <?php endif; ?>
        <form method="post">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 p-3">
                        <h5>Projections</h5>
                        <div class="form-group">
                            <label for="projections_investment">Investment</label>
                            <input type="text" class="form-control" id="projections_investment" name="projections_investment" value="<?php echo displayNumber(isset($projections['investment']) ? $projections['investment'] : ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="projections_turnoverOfMoney">Turnover Of Money</label>
                            <input type="text" class="form-control" id="projections_turnoverOfMoney" name="projections_turnoverOfMoney" value="<?php echo displayNumber(isset($projections['turnoverOfMoney']) ? $projections['turnoverOfMoney'] : '', 2); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="projections_moneyAvailableForLoanDisbursement">Money Available For Loan Disbursement</label>
                            <input type="text" class="form-control" id="projections_moneyAvailableForLoanDisbursement" name="projections_moneyAvailableForLoanDisbursement" value="<?php echo displayNumber(isset($projections['moneyAvailableForLoanDisbursement']) ? $projections['moneyAvailableForLoanDisbursement'] : ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="projections_estimatedEarningOfInterest">Estimated Earning Of Interest</label>
                            <input type="text" class="form-control" id="projections_estimatedEarningOfInterest" name="projections_estimatedEarningOfInterest" value="<?php echo displayNumber(isset($projections['estimatedEarningOfInterest']) ? $projections['estimatedEarningOfInterest'] : ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="projections_estimatedBadDebts">Estimated Bad Debts</label>
                            <input type="text" class="form-control" id="projections_estimatedBadDebts" name="projections_estimatedBadDebts" value="<?php echo displayNumber(isset($projections['estimatedBadDebts']) ? $projections['estimatedBadDebts'] : ''); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="projections_estimatedInterest">Estimated Interest</label>
                            <input type="text" class="form-control" id="projections_estimatedInterest" name="projections_estimatedInterest" value="<?php echo displayNumber(isset($projections['estimatedInterest']) ? $projections['estimatedInterest'] : ''); ?>" />
                        </div>
                        <div class="form-group mb-0">
                            <label for="projections_projectedRoi">Projected ROI %</label>
                            <input type="text" class="form-control" id="projections_projectedRoi" name="projections_projectedRoi" value="<?php echo displayNumber(isset($projections['projectedRoi']) ? $projections['projectedRoi'] : '', 2); ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg mt-3">Save Projections</button>
        </form>
    <?php endif; ?>
    
    <!-- Export/Import Section for AI Analysis -->
    <div class="card p-4 mt-5">
        <h4 class="mb-3">AI Data Analysis Tools - Profile <?php echo $profileId; ?></h4>
        <p class="text-muted mb-4">
            Export all dashboard variables for this profile to a document for AI analysis (e.g., Grok). 
            After analysis, upload the updated file to automatically update all variables for Profile <?php echo $profileId; ?> only.
        </p>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card p-3" style="background: #0f1419; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">Export All Data</h5>
                    <p class="small text-muted mb-3">
                        Download a comprehensive JSON file containing all dashboard variables for Profile <?php echo $profileId; ?>:
                        metrics, loan graphs, funding, aging data, and projections.
                    </p>
                    <a href="export-lic-data.php?id=<?php echo $profileId; ?>" class="btn btn-success" download>
                        📥 Export Profile <?php echo $profileId; ?> Variables
                    </a>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card p-3" style="background: #0f1419; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">Import Analyzed Data</h5>
                    <p class="small text-muted mb-3">
                        Upload the analyzed JSON file to update all dashboard variables for Profile <?php echo $profileId; ?> at once.
                        The file should be the exported file after AI analysis.
                    </p>
                    <form id="importForm" enctype="multipart/form-data" style="margin: 0;">
                        <input type="hidden" name="profileId" value="<?php echo $profileId; ?>">
                        <div class="form-group mb-2">
                            <input type="file" class="form-control-file" id="dataFile" name="dataFile" accept=".json" required>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            📤 Upload & Update Profile <?php echo $profileId; ?> Variables
                        </button>
                    </form>
                    <div id="importMessages" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>
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
                url: 'import-lic-data.php',
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

