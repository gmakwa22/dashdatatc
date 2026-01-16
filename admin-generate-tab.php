<div class="card p-4 mb-4">
    <h4 class="mb-3">🚀 Quick Dashboard Generator</h4>
    <p class="text-muted mb-4">
        Enter key metrics below and the system will automatically generate dashboard data for:
        metrics, loan graphs, and aging analysis. <strong>Note:</strong> Licensee income is generated separately in the Licensees → Generate tab.
    </p>
    
    <form method="post" id="generateForm" action="admin.php?tab=generate">
        <input type="hidden" name="generate_dashboard" value="1">
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">💰 Core Investment Metrics</h5>
                    <div class="form-group">
                        <label for="total_investment">
                            <strong>Total Investment Amount</strong>
                            <small class="text-muted d-block">e.g., 2000000 for $2 million</small>
                        </label>
                        <input type="text" class="form-control" id="total_investment" name="total_investment" 
                               value="2000000" placeholder="2000000" required>
                    </div>
                    <div class="form-group">
                        <label for="target_roi">
                            <strong>Target ROI Percentage</strong>
                            <small class="text-muted d-block">Expected return on investment (e.g., 30 for 30%)</small>
                        </label>
                        <input type="text" class="form-control" id="target_roi" name="target_roi" 
                               value="30" placeholder="30" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="collection_rate">
                            <strong>Collection Rate (%)</strong>
                            <small class="text-muted d-block">Percentage of loans successfully collected (e.g., 85)</small>
                        </label>
                        <input type="text" class="form-control" id="collection_rate" name="collection_rate" 
                               value="85" placeholder="85" required>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">🌍 Territory Information</h5>
                    <div class="form-group">
                        <label for="total_territories">
                            <strong>Total Territories</strong>
                            <small class="text-muted d-block">Total number of territories available</small>
                        </label>
                        <input type="text" class="form-control" id="total_territories" name="total_territories" 
                               value="50" placeholder="50" required>
                    </div>
                    <div class="form-group">
                        <label for="territory_price">
                            <strong>Average Territory Price</strong>
                            <small class="text-muted d-block">Average price per territory (e.g., 200000)</small>
                        </label>
                        <input type="text" class="form-control" id="territory_price" name="territory_price" 
                               value="200000" placeholder="200000" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="territories_sold_pct">
                            <strong>Territories Sold (%)</strong>
                            <small class="text-muted d-block">Percentage of territories sold (e.g., 50)</small>
                        </label>
                        <input type="text" class="form-control" id="territories_sold_pct" name="territories_sold_pct" 
                               value="50" placeholder="50" required>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">📊 Loan Distribution (Percentages must total ~100%)</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="loan_paid_pct">Paid Loans (%)</label>
                                <input type="text" class="form-control" id="loan_paid_pct" name="loan_paid_pct" 
                                       value="70" placeholder="70" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="loan_partial_pct">Partial Payment (%)</label>
                                <input type="text" class="form-control" id="loan_partial_pct" name="loan_partial_pct" 
                                       value="15" placeholder="15" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="loan_pending_pct">Pending Paid (%)</label>
                                <input type="text" class="form-control" id="loan_pending_pct" name="loan_pending_pct" 
                                       value="8" placeholder="8" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="loan_failed_pct">Pad Failed (%)</label>
                                <input type="text" class="form-control" id="loan_failed_pct" name="loan_failed_pct" 
                                       value="7" placeholder="7" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">⚙️ Advanced Options (Optional)</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="interest_rate">Average Interest Rate (%)</label>
                                <input type="text" class="form-control" id="interest_rate" name="interest_rate" 
                                       value="25" placeholder="25">
                                <small class="text-muted">Average interest rate on loans</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="avg_loan_amount">Average Loan Amount</label>
                                <input type="text" class="form-control" id="avg_loan_amount" name="avg_loan_amount" 
                                       value="1500" placeholder="1500">
                                <small class="text-muted">Average amount per loan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-info">
            <strong>💡 How it works:</strong> The generator will calculate all related metrics based on your inputs:
            <ul class="mb-0 mt-2">
                <li>Funding amounts based on investment and ROI</li>
                <li>Loan distributions across categories</li>
                <li>Aging analysis based on collection rates</li>
                <li>Territory sales and status breakdowns</li>
            </ul>
            <p class="mb-0 mt-2"><strong>Note:</strong> To generate licensee income data, use the <a href="admin-licensees.php?tab=generate">Licensees → Generate</a> tab.</p>
        </div>
        
        <button type="submit" class="btn btn-primary btn-lg btn-block">
            🚀 Generate Dashboard Data
        </button>
    </form>
</div>

