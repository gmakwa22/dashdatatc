<?php
/**
 * Import analyzed data and update all licensee dashboard variables for a specific profile
 * Accepts the exported JSON file after AI analysis
 */

header('Content-Type: application/json');

$profileId = isset($_POST['profileId']) ? (int)$_POST['profileId'] : (isset($_GET['id']) ? (int)$_GET['id'] : 1);
if ($profileId < 1) {
    $profileId = 1;
}

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
            // Verify profile ID matches if specified in export
            if (isset($importData['exportInfo']['profileId'])) {
                $exportedProfileId = (int)$importData['exportInfo']['profileId'];
                if ($exportedProfileId !== $profileId) {
                    $messages[] = 'Warning: File was exported for Profile ' . $exportedProfileId . ', but you are importing to Profile ' . $profileId . '. Proceeding anyway...';
                }
            }
            
            $success = true;
            $updateCount = 0;
            
            // Update metrics
            if (isset($importData['metrics']) && is_array($importData['metrics'])) {
                $metricsPath = $dataDir . 'lic-' . $profileId . '-metrics.json';
                $json = json_encode($importData['metrics'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($metricsPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Metrics updated successfully';
                } else {
                    $messages[] = 'Failed to update metrics (check file permissions)';
                }
            }
            
            // Update loan graph data
            if (isset($importData['loanGraph']) && is_array($importData['loanGraph'])) {
                $loanGraphPath = $dataDir . 'lic-' . $profileId . '-loan-graph.json';
                $json = json_encode($importData['loanGraph'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($loanGraphPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Loan graph data updated successfully';
                } else {
                    $messages[] = 'Failed to update loan graph data (check file permissions)';
                }
            }
            
            // Update funding data
            if (isset($importData['funding']) && is_array($importData['funding'])) {
                $fundingPath = $dataDir . 'lic-' . $profileId . '-funding.json';
                $json = json_encode($importData['funding'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($fundingPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Funding data updated successfully';
                } else {
                    $messages[] = 'Failed to update funding data (check file permissions)';
                }
            }
            
            // Update aging data
            if (isset($importData['aging']) && is_array($importData['aging'])) {
                $agingPath = $dataDir . 'lic-' . $profileId . '-aging.json';
                $json = json_encode($importData['aging'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($agingPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Aging data updated successfully';
                } else {
                    $messages[] = 'Failed to update aging data (check file permissions)';
                }
            }
            
            // Update projections data
            if (isset($importData['projections']) && is_array($importData['projections'])) {
                $projectionsPath = $dataDir . 'lic-' . $profileId . '-projections.json';
                $json = json_encode($importData['projections'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                if (@file_put_contents($projectionsPath, $json) !== false) {
                    $updateCount++;
                    $messages[] = 'Projections data updated successfully';
                } else {
                    $messages[] = 'Failed to update projections data (check file permissions)';
                }
            }
            
            if ($updateCount === 0) {
                $success = false;
                $messages[] = 'No valid data sections found in the uploaded file';
            } else {
                $messages[] = "Successfully updated {$updateCount} data file(s) for Profile {$profileId}. Please refresh the page to see changes.";
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
    'messages' => $messages,
    'profileId' => $profileId
));
exit;
