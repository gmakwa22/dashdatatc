<?php
/**
 * Export all dashboard variables to a comprehensive document
 * This document can be fed to AI tools like Grok for analysis
 */

header('Content-Type: application/json');
header('Content-Disposition: attachment; filename="dashboard-data-export-' . date('Y-m-d-His') . '.json"');

$dataDir = __DIR__ . '/data/';

// Collect all data from JSON files
$exportData = array(
    'exportInfo' => array(
        'exportedAt' => date(DATE_ATOM),
        'version' => '1.0',
        'description' => 'Complete dashboard data export for AI analysis. After analysis, upload this file back to update all variables.',
        'instructions' => array(
            '1. Review and analyze the data in this file',
            '2. Make any necessary corrections or updates to the values',
            '3. Ensure the JSON structure remains intact',
            '4. Save the file and upload it back through the admin interface',
            '5. All dashboard variables will be updated automatically'
        )
    ),
    'dashboardMetrics' => null,
    'loanGraph' => null,
    'agingData' => null,
    'licenseeIncome' => null
);

// Load dashboard metrics
$metricsPath = $dataDir . 'dashboard-metrics.json';
if (file_exists($metricsPath)) {
    $content = file_get_contents($metricsPath);
    if ($content !== false) {
        $exportData['dashboardMetrics'] = json_decode($content, true);
    }
}

// Load loan graph data
$loanGraphPath = $dataDir . 'loan-graph.json';
if (file_exists($loanGraphPath)) {
    $content = file_get_contents($loanGraphPath);
    if ($content !== false) {
        $exportData['loanGraph'] = json_decode($content, true);
    }
}

// Load aging data
$agingPath = $dataDir . 'aging-data.json';
if (file_exists($agingPath)) {
    $content = file_get_contents($agingPath);
    if ($content !== false) {
        $exportData['agingData'] = json_decode($content, true);
    }
}

// Load licensee income data
$licenseeIncomePath = $dataDir . 'licensee-income.json';
if (file_exists($licenseeIncomePath)) {
    $content = file_get_contents($licenseeIncomePath);
    if ($content !== false) {
        $exportData['licenseeIncome'] = json_decode($content, true);
    }
}

// Output as pretty JSON for easy reading/editing
echo json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
exit;
