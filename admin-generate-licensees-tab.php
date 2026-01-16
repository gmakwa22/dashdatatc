<div class="card p-4 mb-4">
    <h4 class="mb-3">💰 Licensee Income Generator</h4>
    <p class="text-muted mb-4">
        Generate licensee income data separately from the main dashboard. This allows you to distribute a different amount (e.g., $8 million) across all licensees with proper fee breakdowns.
    </p>
    
    <form method="post" id="generateLicenseesForm" action="admin.php?tab=generate-licensees">
        <input type="hidden" name="generate_licensees" value="1">
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">💰 Total Distribution</h5>
                    <div class="form-group">
                        <label for="licensee_total_distribution">
                            <strong>Total Amount to Distribute</strong>
                            <small class="text-muted d-block">Total amount to distribute across all licensees (e.g., 8000000 for $8 million)</small>
                        </label>
                        <input type="text" class="form-control" id="licensee_total_distribution" name="licensee_total_distribution" 
                               value="8000000" placeholder="8000000" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="licensee_transactional_fee_pct">
                            <strong>Transactional Fee Percentage</strong>
                            <small class="text-muted d-block">Percentage of total income that goes to transactional fees (e.g., 5 for 5%)</small>
                        </label>
                        <input type="text" class="form-control" id="licensee_transactional_fee_pct" name="licensee_transactional_fee_pct" 
                               value="5" placeholder="5" required>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card h-100 p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">💵 Lending Funds Range</h5>
                    <div class="form-group">
                        <label for="licensee_lending_min">
                            <strong>Minimum Lending Funds</strong>
                            <small class="text-muted d-block">Minimum lending funds per licensee (e.g., 100000 for $100k)</small>
                        </label>
                        <input type="text" class="form-control" id="licensee_lending_min" name="licensee_lending_min" 
                               value="100000" placeholder="100000" required>
                    </div>
                    <div class="form-group mb-0">
                        <label for="licensee_lending_max">
                            <strong>Maximum Lending Funds</strong>
                            <small class="text-muted d-block">Maximum lending funds per licensee (e.g., 1000000 for $1 million)</small>
                        </label>
                        <input type="text" class="form-control" id="licensee_lending_max" name="licensee_lending_max" 
                               value="1000000" placeholder="1000000" required>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">📊 Fee Distribution</h5>
                    <div class="form-group">
                        <label for="licensee_monthly_pct">Monthly Amount Percentage</label>
                        <input type="text" class="form-control" id="licensee_monthly_pct" name="licensee_monthly_pct" 
                               value="60" placeholder="60">
                        <small class="text-muted">Percentage of remaining income (after transactional fee and lending funds) that goes to monthly amount</small>
                    </div>
                    <div class="form-group mb-0">
                        <label for="licensee_fee_pct">Licensee Fee Percentage</label>
                        <input type="text" class="form-control" id="licensee_fee_pct" name="licensee_fee_pct" 
                               value="40" placeholder="40">
                        <small class="text-muted">Percentage of remaining income that goes to licensee fee (if not using Licensee Distribution below)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card p-3" style="background: #12121b; border: 1px solid #3a3a4d;">
                    <h5 class="mb-3">💼 Licensee Fee Distribution</h5>
                    <div class="form-group mb-0">
                        <label for="licensee_distribution">
                            <strong>Licensee Distribution Amount</strong>
                            <small class="text-muted d-block">Total amount to distribute as licensee fees only (e.g., 10000000 for $10 million). This will be distributed between $100k-$350k per licensee.</small>
                        </label>
                        <input type="text" class="form-control" id="licensee_distribution" name="licensee_distribution" 
                               value="" placeholder="10000000">
                        <small class="text-muted mt-2 d-block">Leave empty to use the percentage-based calculation above. If filled, this amount will be distributed as licensee fees only (ranging from $100k to $350k per licensee).</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="alert alert-info">
            <strong>💡 How it works:</strong>
            <ul class="mb-0 mt-2">
                <li><strong>Total Income = Transaction Fee + Monthly Amount + Licensee Fee</strong></li>
                <li>Total distribution is split between Transaction Fee, Monthly Amount, and Licensee Fee (unless Licensee Distribution is used)</li>
                <li>Lending Funds Received is separate and not included in Total Income</li>
                <li>Each licensee gets random lending funds between min and max (rounded to nearest $1,000)</li>
                <li><strong>Licensee Distribution:</strong> If provided, this amount is distributed separately as licensee fees only (ranging from $100k to $350k per licensee). When used, Total Distribution is split only between Transaction Fee and Monthly Amount.</li>
                <li>All values are scaled proportionally to ensure totals equal exactly the specified amounts</li>
            </ul>
        </div>
        
        <button type="submit" class="btn btn-primary btn-lg btn-block">
            🚀 Generate Licensee Income Data
        </button>
    </form>
</div>

