<?php
/**
 * Licensee Income Generator
 * Generates licensee income data separately from main dashboard
 * 
 * Logic:
 * - Total Income = Transaction Fee + Monthly Amount + Licensee Fee
 * - Lending Funds Received is separate (not part of total income)
 */
class LicenseeGenerator {
    
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
            $totalDistribution = $this->cleanInput($inputs['licensee_total_distribution']);
            $transactionalFeePct = $this->cleanInput($inputs['licensee_transactional_fee_pct']);
            $lendingMin = $this->cleanInput($inputs['licensee_lending_min']);
            $lendingMax = $this->cleanInput($inputs['licensee_lending_max']);
            $monthlyPct = $this->cleanInput($inputs['licensee_monthly_pct'] ?? 60);
            $feePct = $this->cleanInput($inputs['licensee_fee_pct'] ?? 40);
            $licenseeDistribution = $this->cleanInput($inputs['licensee_distribution'] ?? '');
            
            // Validate inputs
            if ($totalDistribution <= 0) {
                return ['success' => false, 'message' => 'Total distribution must be greater than 0'];
            }
            if ($lendingMin < 0 || $lendingMax < $lendingMin) {
                return ['success' => false, 'message' => 'Invalid lending funds range'];
            }
            if ($transactionalFeePct < 0 || $transactionalFeePct > 100) {
                return ['success' => false, 'message' => 'Transactional fee percentage must be between 0 and 100'];
            }
            
            // Generate licensee income
            $this->generateLicenseeIncome($totalDistribution, $transactionalFeePct, $lendingMin, $lendingMax, $monthlyPct, $feePct, $licenseeDistribution);
            
            $message = 'Licensee income data generated successfully! All ' . count($this->getLicenseeNames()) . ' licensees have been updated with $' . number_format($totalDistribution, 2) . ' total distribution.';
            if ($licenseeDistribution > 0) {
                $message .= ' Licensee fees distributed separately: $' . number_format($licenseeDistribution, 2) . ' (ranging from $100k to $350k per licensee).';
            }
            
            return [
                'success' => true,
                'message' => $message
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error generating licensee data: ' . $e->getMessage()
            ];
        }
    }
    
    private function getLicenseeNames() {
        return [
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
    }
    
    private function getShowInNavMap() {
        return [
            1 => true, 2 => true, 3 => true, 4 => false, 5 => true,
            6 => false, 7 => true, 8 => true, 9 => true, 10 => false,
            11 => true, 12 => true, 13 => true, 14 => true, 15 => true,
            16 => true, 17 => true, 18 => true, 19 => true, 20 => true,
            21 => true, 22 => true, 23 => true, 24 => true, 25 => true,
            26 => true, 27 => true, 28 => true, 29 => true, 30 => true,
            31 => true, 32 => true, 33 => true, 34 => true, 35 => true, 36 => true,
        ];
    }
    
    private function generateLicenseeIncome($totalDistribution, $transactionalFeePct, $lendingMin, $lendingMax, $monthlyPct, $feePct, $licenseeDistribution = 0) {
        $licensees = [];
        $licenseeNames = $this->getLicenseeNames();
        $showInNavMap = $this->getShowInNavMap();
        
        // Read existing licensee data to preserve structure (in case some were deleted)
        $existingLicensees = [];
        $jsonPath = $this->dataDir . '/licensee-income.json';
        if (file_exists($jsonPath)) {
            $existingData = json_decode(file_get_contents($jsonPath), true);
            if (isset($existingData['licensees']) && is_array($existingData['licensees'])) {
                foreach ($existingData['licensees'] as $existing) {
                    if (isset($existing['licenseeId']) && $existing['licenseeId'] != 999 && !$existing['isAggregate']) {
                        $existingLicensees[$existing['licenseeId']] = $existing;
                    }
                }
            }
        }
        
        // Use existing licensees if available, otherwise use all 36
        if (!empty($existingLicensees)) {
            $licenseeIds = array_keys($existingLicensees);
            sort($licenseeIds);
        } else {
            $licenseeIds = range(1, 36);
        }
        $maxLicensees = count($licenseeIds);
        
        // Check if we're using separate licensee distribution
        $useSeparateLicenseeDistribution = $licenseeDistribution > 0;
        
        // Generate lending funds separately (not part of total income)
        $lendingFundsArray = [];
        foreach ($licenseeIds as $licenseeId) {
            // Random lending funds between min and max, rounded to nearest 1000
            $range = $lendingMax - $lendingMin;
            $randomAmount = $lendingMin + ($range > 0 ? rand(0, $range) : 0);
            // Round to nearest 1000 for cleaner numbers
            $lendingFunds = round($randomAmount / 1000) * 1000;
            $lendingFundsArray[$licenseeId] = $lendingFunds;
        }
        
        // Generate licensee fees separately if licensee distribution is provided
        $licenseeFeeArray = [];
        if ($useSeparateLicenseeDistribution) {
            // Distribute licensee distribution amount as licensee fees (ranging from $100k to $350k)
            $licenseeFeeMin = 100000;
            $licenseeFeeMax = 350000;
            $licenseeFeeRange = $licenseeFeeMax - $licenseeFeeMin;
            
            // First pass: assign random licensee fees
            $totalLicenseeFees = 0;
            foreach ($licenseeIds as $licenseeId) {
                $randomAmount = $licenseeFeeMin + ($licenseeFeeRange > 0 ? rand(0, $licenseeFeeRange) : 0);
                // Round to nearest 1000 for cleaner numbers
                $licenseeFee = round($randomAmount / 1000) * 1000;
                $licenseeFeeArray[$licenseeId] = $licenseeFee;
                $totalLicenseeFees += $licenseeFee;
            }
            
            // Scale to match the exact licensee distribution amount
            if ($totalLicenseeFees > 0) {
                $scaleFactor = $licenseeDistribution / $totalLicenseeFees;
                foreach ($licenseeFeeArray as $key => $value) {
                    $licenseeFeeArray[$key] = round(($value * $scaleFactor) / 1000) * 1000;
                }
            }
        }
        
        // Normalize percentages for total distribution
        // Total Income = Transaction Fee + Monthly Amount + Licensee Fee
        if (!$useSeparateLicenseeDistribution) {
            // Split total distribution between transactional fee, monthly amount, and licensee fee
            $totalPct = $transactionalFeePct + $monthlyPct + $feePct;
            if ($totalPct > 0) {
                $transactionalFeePct = ($transactionalFeePct / $totalPct) * 100;
                $monthlyPct = ($monthlyPct / $totalPct) * 100;
                $feePct = ($feePct / $totalPct) * 100;
            } else {
                $transactionalFeePct = 5;
                $monthlyPct = 60;
                $feePct = 35;
            }
        } else {
            // If using separate licensee distribution, total distribution is split between transactional fee and monthly amount only
            $totalPct = $transactionalFeePct + $monthlyPct;
            if ($totalPct > 0) {
                $transactionalFeePct = ($transactionalFeePct / $totalPct) * 100;
                $monthlyPct = ($monthlyPct / $totalPct) * 100;
            } else {
                $transactionalFeePct = 5;
                $monthlyPct = 95;
            }
        }
        
        // Generate initial values for total distribution components
        $totalIncomeSum = 0;
        $licenseeData = [];
        
        foreach ($licenseeIds as $licenseeId) {
            // Distribute total distribution with variation
            $baseShare = $totalDistribution / $maxLicensees;
            $variation = 0.7 + (rand(0, 60) / 100); // 0.7x to 1.3x variation
            
            // Calculate components from total distribution
            $transactionalFee = $baseShare * ($transactionalFeePct / 100) * $variation;
            $monthlyAmount = $baseShare * ($monthlyPct / 100) * $variation;
            
            // Licensee fee: use separate distribution if provided, otherwise from total distribution
            if ($useSeparateLicenseeDistribution) {
                $licenseeFee = isset($licenseeFeeArray[$licenseeId]) ? $licenseeFeeArray[$licenseeId] : 0;
            } else {
                $licenseeFee = $baseShare * ($feePct / 100) * $variation;
            }
            
            // Total income = Transaction fee + Monthly amount + Licensee fee
            $totalIncome = $transactionalFee + $monthlyAmount + $licenseeFee;
            
            $licenseeData[$licenseeId] = [
                'lendingFunds' => $lendingFundsArray[$licenseeId],
                'transactionalFee' => $transactionalFee,
                'monthlyAmount' => $monthlyAmount,
                'licenseeFee' => $licenseeFee,
                'totalIncome' => $totalIncome
            ];
            
            $totalIncomeSum += $totalIncome;
        }
        
        // Scale to ensure total equals exactly the specified distribution
        if ($useSeparateLicenseeDistribution) {
            // Scale only transactional fee and monthly amount (licensee fees are already set)
            $totalForScaling = 0;
            foreach ($licenseeData as $data) {
                $totalForScaling += $data['transactionalFee'] + $data['monthlyAmount'];
            }
            if ($totalForScaling > 0) {
                $scaleFactor = $totalDistribution / $totalForScaling;
            } else {
                $scaleFactor = 1;
            }
        } else {
            $scaleFactor = $totalDistribution / $totalIncomeSum;
        }
        
        // Generate final values with scaling
        foreach ($licenseeIds as $licenseeId) {
            $data = $licenseeData[$licenseeId];
            
            // Lending funds are separate (not scaled)
            $lendingFunds = $data['lendingFunds'];
            
            // Scale transactional fee and monthly amount
            $transactionalFee = $data['transactionalFee'] * $scaleFactor;
            $monthlyAmount = $data['monthlyAmount'] * $scaleFactor;
            
            // Licensee fee: use separate distribution if provided, otherwise scale
            if ($useSeparateLicenseeDistribution) {
                $licenseeFee = isset($licenseeFeeArray[$licenseeId]) ? $licenseeFeeArray[$licenseeId] : 0;
            } else {
                $licenseeFee = $data['licenseeFee'] * $scaleFactor;
                // Round to nearest 1000 for cleaner numbers (like lending funds)
                $licenseeFee = round($licenseeFee / 1000) * 1000;
            }
            
            // Total income = Transaction fee + Monthly amount + Licensee fee
            $totalIncome = $transactionalFee + $monthlyAmount + $licenseeFee;
            
            // Preserve existing licensee properties if available
            $existing = isset($existingLicensees[$licenseeId]) ? $existingLicensees[$licenseeId] : [];
            
            $licensees[] = [
                'licenseeId' => $licenseeId,
                'licenseeName' => isset($licenseeNames[$licenseeId]) ? $licenseeNames[$licenseeId] : (isset($existing['licenseeName']) ? $existing['licenseeName'] : 'Licensee ' . $licenseeId),
                'transactionalFee' => round($transactionalFee, 2),
                'monthlyAmount' => round($monthlyAmount, 2),
                'licenseeFee' => round($licenseeFee, 0), // Round to whole number (like lending funds)
                'lendingFundsReceived' => round($lendingFunds, 0), // Round to whole number
                'totalIncome' => round($totalIncome, 2),
                'showInNav' => isset($existing['showInNav']) ? $existing['showInNav'] : (isset($showInNavMap[$licenseeId]) ? $showInNavMap[$licenseeId] : ($licenseeId <= 15)),
                'includeInSelect' => isset($existing['includeInSelect']) ? $existing['includeInSelect'] : true,
                'showInTable' => isset($existing['showInTable']) ? $existing['showInTable'] : true,
                'isAggregate' => false,
            ];
        }
        
        // Add aggregate row
        $totalTransactionalFee = array_sum(array_column($licensees, 'transactionalFee'));
        $totalMonthlyAmount = array_sum(array_column($licensees, 'monthlyAmount'));
        $totalLicenseeFee = array_sum(array_column($licensees, 'licenseeFee'));
        $totalLendingFundsReceived = array_sum(array_column($licensees, 'lendingFundsReceived'));
        $totalTotalIncome = array_sum(array_column($licensees, 'totalIncome'));
        
        $licensees[] = [
            'licenseeId' => 999,
            'licenseeName' => 'All',
            'transactionalFee' => round($totalTransactionalFee, 2),
            'monthlyAmount' => round($totalMonthlyAmount, 2),
            'licenseeFee' => round($totalLicenseeFee, 0), // Round to whole number (like lending funds)
            'lendingFundsReceived' => round($totalLendingFundsReceived, 0), // Round to whole number
            'totalIncome' => round($totalTotalIncome, 2),
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
