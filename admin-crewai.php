<?php
/**
 * CrewAI Admin Interface
 * Provides UI to trigger CrewAI analysis on dashboard data
 */

require_once __DIR__ . '/crewai-php-bridge.php';

$bridge = new CrewAIBridge();
$messages = [];
$analysisResult = null;

// Check if CrewAI is available
$isAvailable = $bridge->isAvailable();

// Handle analysis request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['analyze'])) {
    $analysisType = isset($_POST['analysis_type']) ? $_POST['analysis_type'] : 'full';
    
    if (!$isAvailable) {
        $messages[] = [
            'type' => 'error',
            'text' => 'CrewAI API is not available. Please ensure the Python service is running.'
        ];
    } else {
        $result = $bridge->analyzeDashboard($analysisType);
        if ($result['success']) {
            $messages[] = [
                'type' => 'success',
                'text' => 'Analysis completed successfully!'
            ];
            $analysisResult = $result;
        } else {
            $messages[] = [
                'type' => 'error',
                'text' => 'Analysis failed: ' . (isset($result['error']) ? $result['error'] : 'Unknown error')
            ];
        }
    }
}

// Handle file-specific analysis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['analyze_file'])) {
    $fileName = isset($_POST['file_name']) ? $_POST['file_name'] : '';
    
    if (!$isAvailable) {
        $messages[] = [
            'type' => 'error',
            'text' => 'CrewAI API is not available. Please ensure the Python service is running.'
        ];
    } else {
        $result = $bridge->analyzeFile($fileName);
        if ($result['success']) {
            $messages[] = [
                'type' => 'success',
                'text' => 'File analysis completed successfully!'
            ];
            $analysisResult = $result;
        } else {
            $messages[] = [
                'type' => 'error',
                'text' => 'Analysis failed: ' . (isset($result['error']) ? $result['error'] : 'Unknown error')
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CrewAI Dashboard Analyzer</title>
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
            margin-bottom: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .status-available {
            background: #198754;
            color: #fff;
        }
        .status-unavailable {
            background: #dc3545;
            color: #fff;
        }
        .analysis-result {
            background: #12121b;
            border: 1px solid #3a3a4d;
            border-radius: 5px;
            padding: 20px;
            margin-top: 20px;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            max-height: 600px;
            overflow-y: auto;
        }
        .btn-primary {
            background: #5e5efc;
            border-color: #5e5efc;
        }
        .btn-primary:hover {
            background: #4e4edc;
            border-color: #4e4edc;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">CrewAI Dashboard Analyzer</h1>
        <div>
            <a href="admin.php" class="btn btn-outline-light btn-sm">← Back to Admin</a>
            <a href="chat-loan-manager.php" class="btn btn-primary btn-sm ml-2">💬 Chat with Loan Manager</a>
            <a href="index.html" class="btn btn-outline-light btn-sm ml-2">View Dashboard</a>
        </div>
    </div>
    
    <!-- Status Indicator -->
    <div class="card p-3 mb-4">
        <div class="d-flex align-items-center">
            <span class="status-badge <?php echo $isAvailable ? 'status-available' : 'status-unavailable'; ?>">
                <?php echo $isAvailable ? '✓ CrewAI API Available' : '✗ CrewAI API Unavailable'; ?>
            </span>
            <span class="ml-3 text-muted">
                <?php echo $isAvailable 
                    ? 'Python service is running and ready' 
                    : 'Start the Python service: python crewai_api.py'; ?>
            </span>
        </div>
    </div>
    
    <!-- Messages -->
    <?php foreach ($messages as $message): ?>
        <div class="alert alert-<?php echo $message['type'] === 'success' ? 'success' : 'danger'; ?>">
            <?php echo htmlspecialchars($message['text'], ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php endforeach; ?>
    
    <!-- Analysis Forms -->
    <div class="row">
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Analyze Dashboard Data</h5>
                <form method="post">
                    <div class="form-group">
                        <label for="analysis_type">Analysis Type</label>
                        <select class="form-control" id="analysis_type" name="analysis_type" style="background: #12121b; border: 1px solid #3a3a4d; color: #fff;">
                            <option value="full">Full Analysis (All Data)</option>
                            <option value="metrics">Metrics Only</option>
                            <option value="loans">Loans Only</option>
                            <option value="aging">Aging Analysis Only</option>
                        </select>
                    </div>
                    <button type="submit" name="analyze" class="btn btn-primary" <?php echo $isAvailable ? '' : 'disabled'; ?>>
                        Run Analysis
                    </button>
                </form>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card p-4">
                <h5 class="mb-3">Analyze Specific File</h5>
                <form method="post">
                    <div class="form-group">
                        <label for="file_name">Data File</label>
                        <select class="form-control" id="file_name" name="file_name" style="background: #12121b; border: 1px solid #3a3a4d; color: #fff;">
                            <option value="dashboard-metrics.json">Dashboard Metrics</option>
                            <option value="loan-graph.json">Loan Graph</option>
                            <option value="aging-data.json">Aging Data</option>
                            <option value="licensee-income.json">Licensee Income</option>
                        </select>
                    </div>
                    <button type="submit" name="analyze_file" class="btn btn-primary" <?php echo $isAvailable ? '' : 'disabled'; ?>>
                        Analyze File
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Analysis Results -->
    <?php if ($analysisResult): ?>
        <div class="card p-4">
            <h5 class="mb-3">Analysis Results</h5>
            <?php if (isset($analysisResult['data_analyzed'])): ?>
                <p class="text-muted mb-3">
                    Analyzed: <?php echo implode(', ', $analysisResult['data_analyzed']); ?>
                </p>
            <?php endif; ?>
            <div class="analysis-result">
                <?php echo htmlspecialchars($analysisResult['analysis'] ?? 'No analysis output', ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Instructions -->
    <div class="card p-4 mt-4">
        <h5 class="mb-3">How to Use</h5>
        <ol>
            <li>Ensure Python 3.10+ is installed</li>
            <li>Install dependencies: <code>pip install -r requirements.txt</code></li>
            <li>Set up your API key in <code>.env</code> file (copy from <code>.env.example</code>)</li>
            <li>Start the CrewAI API service: <code>python crewai_api.py</code></li>
            <li>Use this interface to trigger analyses</li>
        </ol>
        <p class="text-muted mt-3">
            <strong>Note:</strong> The analysis may take 30-60 seconds depending on the data size and API response time.
        </p>
    </div>
    
    <!-- Chat Interface Instructions -->
    <div class="card p-4 mt-4">
        <h5 class="mb-3">💬 Chat with Loan Projection Manager</h5>
        <p class="mb-3">
            The Loan Projection Manager is an AI agent that can help you with loan portfolio management, 
            financial forecasting, and data analysis. It has <strong>full read and write access</strong> to all JSON files in the data folder.
        </p>
        
        <div class="mb-4">
            <h6 class="text-primary mb-2">What the Agent Can Do:</h6>
            <ul>
                <li><strong>Read Data:</strong> Access all JSON files in the data folder (loan-graph.json, aging-data.json, dashboard-metrics.json, etc.)</li>
                <li><strong>Write/Update Data:</strong> Modify any JSON file in the data folder with proper structure</li>
                <li><strong>Calculate Projections:</strong> Generate loan projections based on growth rates and time periods</li>
                <li><strong>Analyze Metrics:</strong> Provide insights on ROI, collection rates, and financial performance</li>
                <li><strong>Provide Recommendations:</strong> Suggest strategies to optimize loan portfolio</li>
            </ul>
        </div>
        
        <div class="mb-4">
            <h6 class="text-primary mb-2">Example Questions You Can Ask:</h6>
            <div class="bg-dark p-3 rounded mb-2">
                <strong>Data Analysis:</strong>
                <ul class="mb-0 mt-2">
                    <li>"What are the current loan projections for the next 6 months?"</li>
                    <li>"Analyze the collection rates and identify any concerns"</li>
                    <li>"What is the ROI forecast based on current data?"</li>
                    <li>"Show me insights from the aging analysis"</li>
                </ul>
            </div>
            
            <div class="bg-dark p-3 rounded mb-2">
                <strong>Fund Distribution & Projection Dashboards:</strong>
                <ul class="mb-0 mt-2">
                    <li>"Distribute $12 million across 14 existing licensees"</li>
                    <li>"Create 5 new licensees and distribute $8 million equally among them"</li>
                    <li>"Distribute $15 million proportionally based on current lending funds"</li>
                    <li>"Create a projection dashboard with $10 million distributed over 10 licensees"</li>
                </ul>
            </div>
            
            <div class="bg-dark p-3 rounded mb-2">
                <strong>Data Updates:</strong>
                <ul class="mb-0 mt-2">
                    <li>"Update the dashboard metrics with new ROI of 35%"</li>
                    <li>"Increase the available balance in dashboard-metrics.json to $50,000"</li>
                    <li>"Update the loan graph data with new collection amounts"</li>
                    <li>"Modify the aging data for 0-30 days category"</li>
                </ul>
            </div>
            
            <div class="bg-dark p-3 rounded mb-2">
                <strong>Projections & Calculations:</strong>
                <ul class="mb-0 mt-2">
                    <li>"Calculate projections for $100,000 at 5% growth over 12 months"</li>
                    <li>"What would be the projected income if collection rate improves by 10%?"</li>
                    <li>"Generate a 6-month forecast based on current loan data"</li>
                </ul>
            </div>
            
            <div class="bg-dark p-3 rounded">
                <strong>File Operations:</strong>
                <ul class="mb-0 mt-2">
                    <li>"List all available data files"</li>
                    <li>"Read the contents of licensee-income.json"</li>
                    <li>"Update dashboard-metrics.json with new territory data"</li>
                    <li>"Create a backup of the current loan-graph.json structure"</li>
                </ul>
            </div>
        </div>
        
        <div class="mb-4">
            <h6 class="text-primary mb-2">Backend Instructions for the Agent:</h6>
            <div class="bg-dark p-3 rounded">
                <p class="mb-2"><strong>Primary Purpose - Projection Dashboard Creation:</strong></p>
                <p class="mb-3 text-info">
                    This is a <strong>Projection Loan Dashboard</strong> system. You create views that potential buyers will see.
                    When asked to distribute funds (e.g., "$12 million over 14 licensees"), you should:
                </p>
                <ol class="mb-3">
                    <li><strong>Check existing licensees</strong> - Read licensee-income.json to see current licensees</li>
                    <li><strong>Create new licensees if needed</strong> - Use create_new_licensees tool if more licensees are required</li>
                    <li><strong>Distribute funds</strong> - Use distribute_funds_to_licensees to create a distribution plan</li>
                    <li><strong>Apply distribution</strong> - Use apply_fund_distribution to update licensee-income.json</li>
                    <li><strong>Update dashboard metrics</strong> - Update dashboard-metrics.json with total investment and recalculated ROI</li>
                    <li><strong>Update loan graph</strong> - Recalculate loan-graph.json based on new fund distribution</li>
                    <li><strong>Update aging data</strong> - Adjust aging-data.json if needed for the projection</li>
                </ol>
                
                <p class="mb-2"><strong>Data File Structure:</strong></p>
                <ul class="mb-3">
                    <li>All data files are in the <code>data/</code> folder</li>
                    <li>Main files: <code>dashboard-metrics.json</code>, <code>loan-graph.json</code>, <code>aging-data.json</code>, <code>licensee-income.json</code></li>
                    <li>Licensee-specific files: <code>lic-{id}-metrics.json</code>, <code>lic-{id}-loan-graph.json</code>, etc.</li>
                    <li><code>licensee-income.json</code> contains array of licensees with: licenseeId, licenseeName, transactionalFee, monthlyAmount, licenseeFee, lendingFundsReceived, totalIncome</li>
                </ul>
                
                <p class="mb-2"><strong>Fund Distribution Guidelines:</strong></p>
                <ul class="mb-3">
                    <li>Equal distribution: Divide total amount equally among all selected licensees</li>
                    <li>Proportional distribution: Distribute based on current lendingFundsReceived amounts</li>
                    <li>When creating new licensees, assign sequential IDs starting from highest existing ID + 1</li>
                    <li>After distributing funds, recalculate totalIncome for each licensee</li>
                    <li>Update dashboard-metrics.json investment amount to match total distribution</li>
                </ul>
                
                <p class="mb-2"><strong>When Writing Data:</strong></p>
                <ul class="mb-3">
                    <li>Always maintain the existing JSON structure</li>
                    <li>Include all required fields (don't remove fields, only update values)</li>
                    <li>Preserve the <code>meta</code> section with <code>updatedAt</code> timestamp in ISO 8601 format</li>
                    <li>Use proper number formatting (no currency symbols in JSON, just numbers)</li>
                    <li>Validate JSON structure before writing</li>
                </ul>
                
                <p class="mb-2"><strong>File Permissions:</strong></p>
                <ul>
                    <li>You have <strong>FULL WRITE ACCESS</strong> to all JSON files in the data folder</li>
                    <li>You can create, read, update, and modify any JSON file</li>
                    <li>You can create new licensees and distribute funds across them</li>
                    <li>Always validate before making major changes</li>
                </ul>
            </div>
        </div>
        
        <div class="text-center">
            <a href="chat-loan-manager.php" class="btn btn-primary btn-lg">
                <i class="fas fa-comments"></i> Open Chat Interface
            </a>
        </div>
    </div>
</div>

<script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
</body>
</html>

