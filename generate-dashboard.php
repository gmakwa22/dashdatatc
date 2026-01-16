<?php
/**
 * Dashboard Generator
 * Generates all dashboard data files based on key input metrics
 */
class DashboardGenerator {
    
    private $dataDir;
    
    public function __construct() {
        $this->dataDir = __DIR__ . '/data';
        if (!is_dir($this->dataDir)) {
            @mkdir($this->dataDir, 0775, true);
        }
    }
    
    private function cleanInput($value, $type = 'float') {
        if ($value === null || $value === '') {
            return 0;
        }
        $numeric = str_replace([',', '$', ' '], '', $value);
        $numeric = preg_replace('/[^0-9.\-]/', '', $numeric);
        if ($type === 'int') {
            return $numeric === '' ? 0 : (int)$numeric;
        }
        return $numeric === '' ? 0 : (float)$numeric;
    }
    
    public function generate($inputs) {
        try {
            // Extract and clean inputs
            $totalInvestment = $this->cleanInput($inputs['total_investment']);
            $targetROI = $this->cleanInput($inputs['target_roi']);
            $collectionRate = $this->cleanInput($inputs['collection_rate']);
            $totalTerritories = $this->cleanInput($inputs['total_territories'], 'int');
            $territoryPrice = $this->cleanInput($inputs['territory_price']);
            $territoriesSoldPct = $this->cleanInput($inputs['territories_sold_pct']);
            $loanPaidPct = $this->cleanInput($inputs['loan_paid_pct']);
            $loanPartialPct = $this->cleanInput($inputs['loan_partial_pct']);
            $loanPendingPct = $this->cleanInput($inputs['loan_pending_pct']);
            $loanFailedPct = $this->cleanInput($inputs['loan_failed_pct']);
            $interestRate = $this->cleanInput($inputs['interest_rate'] ?? 25);
            $avgLoanAmount = $this->cleanInput($inputs['avg_loan_amount'] ?? 1500);
            
            // Validate inputs
            if ($totalInvestment <= 0) {
                return ['success' => false, 'message' => 'Total investment must be greater than 0'];
            }
            
            // Calculate derived metrics
            $totalIncome = $totalInvestment * ($targetROI / 100);
            $totalLoanDisbursement = $totalInvestment * 1.15; // Assume 15% overhead/rotation
            $totalInterest = $totalLoanDisbursement * ($interestRate / 100);
            $totalAmount = $totalLoanDisbursement + $totalInterest;
            $totalCollected = $totalAmount * ($collectionRate / 100);
            
            // Generate Dashboard Metrics
            $this->generateDashboardMetrics($totalInvestment, $targetROI, $totalIncome, $totalTerritories, $territoryPrice, $territoriesSoldPct);
            
            // Generate Loan Graph Data
            $this->generateLoanGraph($totalLoanDisbursement, $totalInterest, $totalAmount, $totalCollected, 
                                    $loanPaidPct, $loanPartialPct, $loanPendingPct, $loanFailedPct, $avgLoanAmount);
            
            // Generate Aging Data
            $this->generateAgingData($totalLoanDisbursement, $totalInterest, $totalCollected, $collectionRate);
            
            // Licensee income is now generated separately - not included in main dashboard generator
            
            return [
                'success' => true,
                'message' => 'Dashboard data generated successfully! Metrics, loan graphs, and aging data have been updated. To generate licensee income, use the Licensees → Generate tab.'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error generating dashboard: ' . $e->getMessage()
            ];
        }
    }
    
    private function generateDashboardMetrics($investment, $roi, $income, $totalTerritories, $territoryPrice, $soldPct) {
        $totalTerritoryValue = $totalTerritories * $territoryPrice;
        $soldTerritories = (int)($totalTerritories * ($soldPct / 100));
        $soldAmount = $soldTerritories * $territoryPrice;
        $inProgressCount = (int)($totalTerritories * 0.15);
        $inProgressAmount = $inProgressCount * $territoryPrice;
        $onHoldCount = (int)($totalTerritories * 0.20);
        $onHoldAmount = $onHoldCount * $territoryPrice;
        
        $available = $investment * 0.03; // 3% available
        $pending = $investment * 0.07; // 7% pending
        $account = $available + $pending;
        $cumulative = $investment * 1.5; // Historical cumulative
        
        $fundingTotal = $investment;
        $fundingEtransfer = $investment * 0.46; // 46% e-transfer
        $fundingReceived = $investment * 1.15; // 15% more received
        $fundingBalance = $fundingReceived - $fundingTotal;
        
        $data = [
            'cards' => [
                'balances' => [
                    'available' => round($available, 2),
                    'pending' => round($pending, 2),
                    'account' => round($account, 2),
                    'cumulative' => round($cumulative, 2),
                ],
                'funding' => [
                    'total' => round($fundingTotal, 2),
                    'etransfer' => round($fundingEtransfer, 2),
                    'received' => round($fundingReceived, 2),
                    'balance' => round($fundingBalance, 2),
                ],
                'roi' => [
                    'percentage' => round($roi, 2),
                    'investment' => round($investment, 2),
                    'income' => round($income, 2),
                ],
                'territories' => [
                    'totalAmount' => round($totalTerritoryValue, 2),
                    'totalCount' => $totalTerritories,
                    'soldAmount' => round($soldAmount, 2),
                    'soldCount' => $soldTerritories,
                    'inProgressAmount' => round($inProgressAmount, 2),
                    'inProgressCount' => $inProgressCount,
                    'onHoldAmount' => round($onHoldAmount, 2),
                    'onHoldCount' => $onHoldCount,
                ],
            ],
            'meta' => [
                'updatedAt' => date(DATE_ATOM),
            ],
        ];
        
        file_put_contents($this->dataDir . '/dashboard-metrics.json', 
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    
    private function generateLoanGraph($disbursement, $interest, $total, $collected, 
                                      $paidPct, $partialPct, $pendingPct, $failedPct, $avgLoan) {
        // Calculate loan counts
        $totalLoans = (int)($disbursement / $avgLoan);
        $paidLoans = (int)($totalLoans * ($paidPct / 100));
        $partialLoans = (int)($totalLoans * ($partialPct / 100));
        $pendingLoans = (int)($totalLoans * ($pendingPct / 100));
        $failedLoans = (int)($totalLoans * ($failedPct / 100));
        
        // Calculate amounts per category
        $paidDisbursed = $disbursement * ($paidPct / 100);
        $paidInterest = $interest * ($paidPct / 100);
        $paidTotal = $total * ($paidPct / 100);
        $paidCollected = $collected * ($paidPct / 100);
        
        $partialDisbursed = $disbursement * ($partialPct / 100);
        $partialInterest = $interest * ($partialPct / 100);
        $partialTotal = $total * ($partialPct / 100);
        $partialCollected = $collected * ($partialPct / 100) * 0.5; // Partial payments
        
        $pendingDisbursed = $disbursement * ($pendingPct / 100);
        $pendingInterest = $interest * ($pendingPct / 100);
        $pendingTotal = $total * ($pendingPct / 100);
        $pendingCollected = $collected * ($pendingPct / 100) * 0.2; // Minimal collection
        
        $failedDisbursed = $disbursement * ($failedPct / 100);
        $failedInterest = $interest * ($failedPct / 100);
        $failedTotal = $total * ($failedPct / 100);
        $failedCollected = $collected * ($failedPct / 100) * 0.01; // Almost nothing
        
        // TFP calculations (simplified)
        $totalTfpPaid = $disbursement * 0.05; // 5% TFP
        $totalTfpCollected = $collected * 0.02; // 2% collected
        
        $loans = [
            [
                'id' => 1,
                'particularTitle' => 'Total Loans',
                'itemCount' => $totalLoans,
                'disbursedAmount' => round($disbursement, 2),
                'interestAmount' => round($interest, 2),
                'totalAmount' => round($total, 2),
                'collectAmount' => round($collected, 2),
                'varietyPercent' => 100,
                'totalTfpPaid' => round($totalTfpPaid, 2),
                'totalTfpCollected' => round($totalTfpCollected, 2),
                'displayPercent' => 100,
            ],
            [
                'id' => 2,
                'particularTitle' => 'Pending Paid',
                'itemCount' => $pendingLoans,
                'disbursedAmount' => round($pendingDisbursed, 2),
                'interestAmount' => round($pendingInterest, 2),
                'totalAmount' => round($pendingTotal, 2),
                'collectAmount' => round($pendingCollected, 2),
                'varietyPercent' => round($pendingPct, 2),
                'totalTfpPaid' => 0,
                'totalTfpCollected' => round($pendingCollected * 0.5, 2),
                'displayPercent' => 0,
            ],
            [
                'id' => 3,
                'particularTitle' => 'Pad Failed',
                'itemCount' => $failedLoans,
                'disbursedAmount' => round($failedDisbursed, 2),
                'interestAmount' => round($failedInterest, 2),
                'totalAmount' => round($failedTotal, 2),
                'collectAmount' => round($failedCollected, 2),
                'varietyPercent' => round($failedPct, 2),
                'totalTfpPaid' => round($failedDisbursed * 0.05, 2),
                'totalTfpCollected' => round($failedCollected * 0.1, 2),
                'displayPercent' => 0,
            ],
            [
                'id' => 4,
                'particularTitle' => 'Paid',
                'itemCount' => $paidLoans,
                'disbursedAmount' => round($paidDisbursed, 2),
                'interestAmount' => round($paidInterest, 2),
                'totalAmount' => round($paidTotal, 2),
                'collectAmount' => round($paidCollected, 2),
                'varietyPercent' => round($paidPct, 2),
                'totalTfpPaid' => round($paidDisbursed * 0.05, 2),
                'totalTfpCollected' => round($paidCollected * 0.02, 2),
                'displayPercent' => 0,
            ],
            [
                'id' => 5,
                'particularTitle' => 'Partial',
                'itemCount' => $partialLoans,
                'disbursedAmount' => round($partialDisbursed, 2),
                'interestAmount' => round($partialInterest, 2),
                'totalAmount' => round($partialTotal, 2),
                'collectAmount' => round($partialCollected, 2),
                'varietyPercent' => round($partialPct, 2),
                'totalTfpPaid' => round($partialDisbursed * 0.05, 2),
                'totalTfpCollected' => round($partialCollected * 0.02, 2),
                'displayPercent' => 0,
            ],
        ];
        
        // Calculate gauge values
        $totalCollectedPct = ($collected / $total) * 100;
        $tfpCollectedPct = ($totalTfpCollected / $totalTfpPaid) * 100;
        $nonTfpCollected = $collected - $totalTfpCollected;
        $nonTfpCollectedPct = ($nonTfpCollected / $total) * 100;
        
        $gauges = [
            ['id' => 'chartdiv1', 'title' => 'Total Collected', 'value' => round($totalCollectedPct, 2)],
            ['id' => 'chartdiv2', 'title' => 'TFP Collected', 'value' => round($tfpCollectedPct, 2)],
            ['id' => 'chartdiv3', 'title' => 'Non TFP Collected', 'value' => round($nonTfpCollectedPct, 2)],
            ['id' => 'chartdiv4', 'title' => 'Sold Territory', 'value' => 57],
            ['id' => 'chartdiv5', 'title' => 'Funding', 'value' => 92.39],
            ['id' => 'chartdiv6', 'title' => 'Total Territories Available', 'value' => 780],
        ];
        
        $data = [
            'loans' => $loans,
            'gauges' => $gauges,
            'meta' => ['updatedAt' => date(DATE_ATOM)],
        ];
        
        file_put_contents($this->dataDir . '/loan-graph.json', 
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    
    private function generateAgingData($disbursement, $interest, $collected, $collectionRate) {
        $aging = [
            [
                'label' => '0 - 30 Days',
                'loanDisbursement' => round($disbursement * 0.25, 2),
                'interest' => round($interest * 0.25, 2),
                'partialPayment' => round($collected * 0.10, 2),
                'totalDebt' => round(($disbursement + $interest) * 0.25, 2),
                'totalCollected' => round($collected * 0.35, 2),
                'tfpPayment' => round($disbursement * 0.25 * 0.05, 2),
                'tfpCollected' => round($collected * 0.35 * 0.02, 2),
                'tfpNet' => round(($collected * 0.35 * 0.02) - ($disbursement * 0.25 * 0.05), 2),
            ],
            [
                'label' => '31 - 60 Days',
                'loanDisbursement' => round($disbursement * 0.30, 2),
                'interest' => round($interest * 0.30, 2),
                'partialPayment' => round($collected * 0.20, 2),
                'totalDebt' => round(($disbursement + $interest) * 0.30, 2),
                'totalCollected' => round($collected * 0.30, 2),
                'tfpPayment' => round($disbursement * 0.30 * 0.05, 2),
                'tfpCollected' => round($collected * 0.30 * 0.02, 2),
                'tfpNet' => round(($collected * 0.30 * 0.02) - ($disbursement * 0.30 * 0.05), 2),
            ],
            [
                'label' => '61 - 90 Days',
                'loanDisbursement' => round($disbursement * 0.25, 2),
                'interest' => round($interest * 0.25, 2),
                'partialPayment' => round($collected * 0.25, 2),
                'totalDebt' => round(($disbursement + $interest) * 0.25, 2),
                'totalCollected' => round($collected * 0.20, 2),
                'tfpPayment' => round($disbursement * 0.25 * 0.05, 2),
                'tfpCollected' => round($collected * 0.20 * 0.02, 2),
                'tfpNet' => round(($collected * 0.20 * 0.02) - ($disbursement * 0.25 * 0.05), 2),
            ],
            [
                'label' => '91+ Days',
                'loanDisbursement' => round($disbursement * 0.20, 2),
                'interest' => round($interest * 0.20, 2),
                'partialPayment' => round($collected * 0.15, 2),
                'totalDebt' => round(($disbursement + $interest) * 0.20, 2),
                'totalCollected' => round($collected * 0.15, 2),
                'tfpPayment' => round($disbursement * 0.20 * 0.05, 2),
                'tfpCollected' => round($collected * 0.15 * 0.02, 2),
                'tfpNet' => round(($collected * 0.15 * 0.02) - ($disbursement * 0.20 * 0.05), 2),
            ],
        ];
        
        $data = [
            'aging' => $aging,
            'meta' => ['updatedAt' => date(DATE_ATOM)],
        ];
        
        file_put_contents($this->dataDir . '/aging-data.json', 
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
    
    private function generateLicenseeIncome($totalIncome, $numLicensees) {
        $licensees = [];
        // Use $4 million as the total to distribute
        $totalToDistribute = 4000000;
        
        // Real licensee names mapping
        $licenseeNames = [
            1 => 'MoolahCashLoans.Ca',
            2 => 'QualityCashLoans.Com',
            3 => 'PaisaCashLoans.Com',
            4 => 'CashBucksLoan.Com',
            5 => 'ReliableSpeedyLoans.Com',
            6 => 'MyPaydayLoan.Ca',
            7 => 'KwartaLoans.Com',
            8 => '6hFinancialServices.Com',
            9 => 'PrairieSkyLoans.com',
            10 => 'MegaCashBucks.Ca',
            11 => 'MyOnlineCash.Ca',
            12 => 'PaydayCashLoans.Ca',
            13 => 'SwiftOnlineCash.Com',
            14 => 'GoGreenOnline.Ca',
            15 => 'SpeedyPay.Ca',
            16 => 'GetCashFast.Ca',
            17 => 'DeltaCash.Ca',
            18 => 'CloudNineLoans.Com',
            19 => 'EasyBuckOnline.Ca',
            20 => 'MadDashLoans.Com',
            21 => 'TideWaterFinancial.Ca',
            22 => 'MaxCapSolutions.Ca',
            23 => 'Sundog Financial Solutions',
            24 => 'CashCartLoans.Com',
            25 => 'NorthRidgePaydayCash.Com',
            26 => 'GreenTreeCash.Ca',
            27 => 'RapidCashToday.Ca',
            28 => 'BrexPrime.Com',
            29 => 'BlackIrishCapital',
            30 => 'TitanEdgeUSA.Com',
            31 => 'BrattsLakeSolutions.Com',
            32 => 'SageEndeavours.Com',
            33 => 'PrestoVenturesGroup',
            34 => 'BlackSilverCapital.Com',
            35 => 'SwiftCashToday.Com',
            36 => 'TompkinsFinance',
        ];
        
        // Show in nav settings (matching index.html)
        $showInNavMap = [
            1 => true, 2 => true, 3 => true, 4 => false, 5 => true,
            6 => false, 7 => true, 8 => true, 9 => true, 10 => false,
            11 => true, 12 => true, 13 => true, 14 => true, 15 => true,
            16 => true, 17 => true, 18 => true, 19 => true, 20 => true,
            21 => true, 22 => true, 23 => true, 24 => true, 25 => true,
            26 => true, 27 => true, 28 => true, 29 => true, 30 => true,
            31 => true, 32 => true, 33 => true, 34 => true, 35 => true, 36 => true,
        ];
        
        // Generate varied licensee income - always generate all 36 licensees
        $maxLicensees = max($numLicensees, 36);
        
        // First pass: assign lending funds ($200k-$2M per licensee)
        $lendingFundsArray = [];
        $totalLendingFunds = 0;
        for ($i = 1; $i <= $maxLicensees; $i++) {
            // Random lending funds between $200,000 and $2,000,000
            $lendingFunds = 200000 + rand(0, 1800000); // Random between 200k and 2M
            $lendingFundsArray[$i] = $lendingFunds;
            $totalLendingFunds += $lendingFunds;
        }
        
        // Calculate remaining amount to distribute (after lending funds)
        $remainingAmount = $totalToDistribute - $totalLendingFunds;
        
        // If remaining is negative, scale down lending funds proportionally
        if ($remainingAmount < 0) {
            $scaleFactor = $totalToDistribute / $totalLendingFunds;
            foreach ($lendingFundsArray as $key => $value) {
                $lendingFundsArray[$key] = $value * $scaleFactor;
            }
            $totalLendingFunds = $totalToDistribute;
            $remainingAmount = 0;
        }
        
        // Distribute remaining amount across transactional fees, monthly amounts, and licensee fees
        // Transactional fees = 5% of total income per licensee
        // Formula: total income = (monthly + licensee fee + lending funds) / 0.95
        // Then: transactional fee = total income * 0.05
        
        // Generate initial values
        $totalIncomeSum = 0;
        $licenseeData = [];
        
        for ($i = 1; $i <= $maxLicensees; $i++) {
            $lendingFunds = $lendingFundsArray[$i];
            
            // Distribute remaining amount with variation
            $baseShare = $remainingAmount / $maxLicensees;
            $variation = 0.7 + (rand(0, 60) / 100); // 0.7x to 1.3x variation
            
            // Allocate remaining: 60% monthly, 40% licensee fee (before transactional fee calculation)
            $monthlyAmount = $baseShare * 0.60 * $variation;
            $licenseeFee = $baseShare * 0.40 * $variation;
            
            // Calculate total income: (monthly + licensee fee + lending funds) / 0.95
            // This ensures transactional fee will be 5% of total income
            $totalIncome = ($monthlyAmount + $licenseeFee + $lendingFunds) / 0.95;
            
            // Transactional fee is 5% of total income
            $transactionalFee = $totalIncome * 0.05;
            
            // Adjust monthly amount and licensee fee to maintain the distribution
            $remainingAfterTxnFee = $totalIncome - $transactionalFee - $lendingFunds;
            $monthlyAmount = $remainingAfterTxnFee * 0.60; // 60% of remaining
            $licenseeFee = $remainingAfterTxnFee * 0.40; // 40% of remaining
            
            $licenseeData[$i] = [
                'lendingFunds' => $lendingFunds,
                'transactionalFee' => $transactionalFee,
                'monthlyAmount' => $monthlyAmount,
                'licenseeFee' => $licenseeFee,
                'totalIncome' => $totalIncome
            ];
            
            $totalIncomeSum += $totalIncome;
        }
        
        // Scale all values to ensure total equals $4 million
        $scaleFactor = $totalToDistribute / $totalIncomeSum;
        
        // Generate final values with scaling
        for ($i = 1; $i <= $maxLicensees; $i++) {
            $data = $licenseeData[$i];
            
            // Scale all values proportionally
            $lendingFunds = $data['lendingFunds'] * $scaleFactor;
            $totalIncome = $data['totalIncome'] * $scaleFactor;
            $transactionalFee = $totalIncome * 0.05; // Ensure it's exactly 5%
            $remainingAfterTxnFee = $totalIncome - $transactionalFee - $lendingFunds;
            $monthlyAmount = $remainingAfterTxnFee * 0.60;
            $licenseeFee = $remainingAfterTxnFee * 0.40;
            
            $licensees[] = [
                'licenseeId' => $i,
                'licenseeName' => isset($licenseeNames[$i]) ? $licenseeNames[$i] : 'Licensee ' . $i,
                'transactionalFee' => round($transactionalFee, 2),
                'monthlyAmount' => round($monthlyAmount, 2),
                'licenseeFee' => round($licenseeFee, 2),
                'lendingFundsReceived' => round($lendingFunds, 2),
                'totalIncome' => round($totalIncome, 2),
                'showInNav' => isset($showInNavMap[$i]) ? $showInNavMap[$i] : ($i <= 15),
                'includeInSelect' => true,
                'showInTable' => true,
                'isAggregate' => false,
            ];
        }
        
        // Add aggregate row
        $licensees[] = [
            'licenseeId' => 999,
            'licenseeName' => 'All',
            'transactionalFee' => round($totalIncome * 0.3, 2),
            'monthlyAmount' => round($totalIncome * 0.4, 2),
            'licenseeFee' => round($totalIncome * 0.2, 2),
            'lendingFundsReceived' => round($totalIncome * 0.1, 2),
            'totalIncome' => round($totalIncome, 2),
            'showInNav' => false,
            'includeInSelect' => false,
            'showInTable' => true,
            'isAggregate' => true,
        ];
        
        $data = [
            'licensees' => $licensees,
            'meta' => ['updatedAt' => date(DATE_ATOM)],
        ];
        
        file_put_contents($this->dataDir . '/licensee-income.json', 
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}

