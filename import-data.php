<?php
/**
 * Import analyzed data and update all dashboard variables
 * Accepts the exported JSON file after AI analysis
 */

header('Content-Type: application/json');

$dataDir = __DIR__ . '/data/';
$messages = array();
$success = false;

// Ensure data directory exists
if (!is_dir($dataDir)) {
    if (!@mkdir($dataDir, 0775, true)) {
        echo json_encode(array(
            'success' => false,
            'message' => 'Failed to create data directory'
        ));
        exit;
    }
}

// Check if file was uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['dataFile']) && $_FILES['dataFile']['error'] === UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['dataFile']['tmp_name'];
    $fileContent = file_get_contents($uploadedFile);
    
    if ($fileContent === false) {
        $messages[] = 'Failed to read uploaded file';
    } else {
        $importData = json_decode($fileContent, true);
        
        if ($importData === null && json_last_error() !== JSON_ERROR_NONE) {
            $errorMsg = function_exists('json_last_error_msg') ? json_last_error_msg() : 'JSON decode error';
            $messages[] = 'Invalid JSON file: ' . $errorMsg;
        } else {
            $success = true;
            $updateCount = 0;
            
            // Update dashboard metrics
            if (isset($importData['dashboardMetrics']) && is_array($importData['dashboardMetrics'])) {
                $metricsPath = $dataDir . 'dashboard-metrics.json';
                $json = json_encode($importData['dashboardMetrics'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($metricsPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Dashboard metrics updated successfully';
                } else {
                    $messages[] = 'Failed to update dashboard metrics (check file permissions)';
                }
            }
            
            // Update loan graph data
            if (isset($importData['loanGraph']) && is_array($importData['loanGraph'])) {
                $loanGraphPath = $dataDir . 'loan-graph.json';
                $json = json_encode($importData['loanGraph'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($loanGraphPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Loan graph data updated successfully';
                } else {
                    $messages[] = 'Failed to update loan graph data (check file permissions)';
                }
            }
            
            // Update aging data
            if (isset($importData['agingData']) && is_array($importData['agingData'])) {
                $agingPath = $dataDir . 'aging-data.json';
                $json = json_encode($importData['agingData'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($agingPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Aging data updated successfully';
                } else {
                    $messages[] = 'Failed to update aging data (check file permissions)';
                }
            }
            
            // Update licensee income data
            if (isset($importData['licenseeIncome']) && is_array($importData['licenseeIncome'])) {
                $licenseeIncomePath = $dataDir . 'licensee-income.json';
                $json = json_encode($importData['licenseeIncome'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($licenseeIncomePath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Licensee income data updated successfully';
                } else {
                    $messages[] = 'Failed to update licensee income data (check file permissions)';
                }
            }
            
            if ($updateCount === 0) {
                $success = false;
                $messages[] = 'No valid data sections found in the uploaded file';
            } else {
                $messages[] = "Successfully updated {$updateCount} data file(s). Please refresh the page to see changes.";
            }
        }
    }
} else {
    $errorCode = isset($_FILES['dataFile']['error']) ? $_FILES['dataFile']['error'] : 'No file uploaded';
    $errorMessages = array(
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
    );
    $messages[] = isset($errorMessages[$errorCode]) ? $errorMessages[$errorCode] : 'Upload error: ' . $errorCode;
}

echo json_encode(array(
    'success' => $success,
    'messages' => $messages
));
exit;
