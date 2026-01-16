<?php
/**
 * Export all licensee dashboard variables for a specific profile
 * This document can be fed to AI tools like Grok for analysis
 */

header('Content-Type: application/json');

$profileId = isset($_GET['id']) ? (int)$_GET['id'] : 1;
if ($profileId < 1) {
    $profileId = 1;
}

header('Content-Disposition: attachment; filename="licensee-profile-' . $profileId . '-export-' . date('Y-m-d-His') . '.json"');

$dataDir = __DIR__ . '/data/';

// Collect all data from JSON files for this profile
$exportData = array(
    'exportInfo' => array(
        'exportedAt' => date(DATE_ATOM),
        'version' => '1.0',
        'profileId' => $profileId,
        'description' => 'Complete licensee dashboard data export for Profile ' . $profileId . '. After analysis, upload this file back to update all variables for this profile only.',
        'instructions' => array(
            '1. Review and analyze the data in this file',
            '2. Make any necessary corrections or updates to the values',
            '3. Ensure the JSON structure remains intact',
            '4. Save the file and upload it back through the admin interface',
            '5. All dashboard variables for Profile ' . $profileId . ' will be updated automatically'
        )
    ),
    'metrics' => null,
    'loanGraph' => null,
    'funding' => null,
    'aging' => null,
    'projections' => null
);

// Load metrics
$metricsPath = $dataDir . 'lic-' . $profileId . '-metrics.json';
if (file_exists($metricsPath)) {
    $content = file_get_contents($metricsPath);
    if ($content !== false) {
        $exportData['metrics'] = json_decode($content, true);
    }
}

// Load loan graph data
$loanGraphPath = $dataDir . 'lic-' . $profileId . '-loan-graph.json';
if (file_exists($loanGraphPath)) {
    $content = file_get_contents($loanGraphPath);
    if ($content !== false) {
        $exportData['loanGraph'] = json_decode($content, true);
    }
}

// Load funding data
$fundingPath = $dataDir . 'lic-' . $profileId . '-funding.json';
if (file_exists($fundingPath)) {
    $content = file_get_contents($fundingPath);
    if ($content !== false) {
        $exportData['funding'] = json_decode($content, true);
    }
}

// Load aging data
$agingPath = $dataDir . 'lic-' . $profileId . '-aging.json';
if (file_exists($agingPath)) {
    $content = file_get_contents($agingPath);
    if ($content !== false) {
        $exportData['aging'] = json_decode($content, true);
    }
}

// Load projections data
$projectionsPath = $dataDir . 'lic-' . $profileId . '-projections.json';
if (file_exists($projectionsPath)) {
    $content = file_get_contents($projectionsPath);
    if ($content !== false) {
        $exportData['projections'] = json_decode($content, true);
    }
}

// Output as pretty JSON for easy reading/editing
echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
exit;
