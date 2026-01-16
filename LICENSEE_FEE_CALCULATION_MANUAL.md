# Licensee Fee Calculation Manual

## Overview
This manual explains how Transactional Fee and Monthly Amount are calculated for licensees based on their lending funds, loan portfolio data, and licensee age/maturity.

## Calculation Formula

### Transactional Fee
**Formula:** `(Licensee Lending Funds / Total Lending Funds) × Total Loan Value × Transactional Fee Rate × Age Multiplier × Random Variation`

**Components:**
- **Licensee Lending Funds**: Individual licensee's `lendingFundsReceived` value
- **Total Lending Funds**: Sum of all licensees' lending funds (currently $13,810,000)
- **Total Loan Value**: From dashboard metrics (currently $35,802,000,000)
- **Transactional Fee Rate**: Base rate (currently 0.12% = 0.0012)
- **Age Multiplier**: Based on licensee maturity (see Age Categories below)
- **Random Variation**: ±8% (0.92 to 1.08) for realism

### Monthly Amount
**Formula:** `Licensee Lending Funds × Monthly Return Rate × Age Multiplier × Random Variation`

**Components:**
- **Licensee Lending Funds**: Individual licensee's `lendingFundsReceived` value
- **Monthly Return Rate**: Base rate (currently 2% = 0.02)
- **Age Multiplier**: Based on licensee maturity (see Age Categories below)
- **Random Variation**: ±8% (0.92 to 1.08) for realism

### Total Income
**Formula:** `Transactional Fee + Monthly Amount + Licensee Fee + Lending Funds Received`

## Age Categories & Multipliers

### NEW Licensees (Recently Started)
- **Multiplier Range**: 0.15x to 0.3x (15-30% of base)
- **Current Licensees**: 
  - SageEndeavours
  - PrestoVenturesGroup
  - SwiftCashToday

**Example Calculation:**
- PrestoVenturesGroup: $160,000 lending funds
- Monthly Amount = $160,000 × 0.02 × 0.25 (avg) × 1.0 = ~$800/month
- Transactional Fee = (share) × $35.8B × 0.0012 × 0.25 = ~$100,000-150,000

### MEDIUM Age Licensees (Established but not mature)
- **Multiplier Range**: 0.5x to 0.8x (50-80% of base)
- **Current Licensees**:
  - BrattsLakeSolutions
  - TitanEdgeUSA
  - BlackSilverCapital
  - TompkinsFinance
  - ApexLendingGroup
  - VelocityMoneyServices
  - PrimeCashAdvance
  - EliteFinancialPartners
  - SummitLendingSolutions
  - NexusCashFlow
  - CrownCapitalLending
  - ThunderboltLoans
  - PhoenixRiseFinancial
  - SilverStreamCapital
  - BlueHorizonLending
  - GoldenGateFinance
  - StellarCreditSolutions
  - AuroraLendingGroup

**Example Calculation:**
- TitanEdgeUSA: $230,000 lending funds
- Monthly Amount = $230,000 × 0.02 × 0.65 (avg) × 1.0 = ~$3,000/month
- Transactional Fee = (share) × $35.8B × 0.0012 × 0.65 = ~$400,000-500,000

### OLD Licensees (Mature/Established)
- **Multiplier Range**: 0.9x to 1.3x (90-130% of base)
- **All other licensees not listed above**

**Example Calculation:**
- MoolahCashLoans: $260,000 lending funds
- Monthly Amount = $260,000 × 0.02 × 1.1 (avg) × 1.0 = ~$5,700/month
- Transactional Fee = (share) × $35.8B × 0.0012 × 1.1 = ~$900,000-1,000,000

## Base Rates (Adjustable)

### Current Settings:
- **Transactional Fee Rate**: 0.12% (0.0012) of loan value handled
- **Monthly Return Rate**: 2% (0.02) of lending capital per month

### To Adjust Overall Scale:
- **Increase all fees**: Raise `transactional_fee_rate` and/or `monthly_return_rate`
- **Decrease all fees**: Lower `transactional_fee_rate` and/or `monthly_return_rate`

## Age Multiplier Ranges (Adjustable)

### To Make New Licensees Even Lower:
- Change NEW multiplier range from `0.15-0.3` to `0.1-0.2` (10-20%)

### To Make New Licensees Higher:
- Change NEW multiplier range from `0.15-0.3` to `0.3-0.5` (30-50%)

### To Adjust Medium Age:
- Change MEDIUM multiplier range from `0.5-0.8` to desired range

### To Adjust Old Licensees:
- Change OLD multiplier range from `0.9-1.3` to desired range

## How to Update Licensees

### Option 1: Use Python Script
1. Create/update a Python script similar to `update_licensee_fees_final.py`
2. Modify the age categories, multipliers, or base rates
3. Run the script to update `data/licensee-income.json`

### Option 2: Manual Calculation
For a single licensee:
1. Determine their age category (NEW/MEDIUM/OLD)
2. Get their lending funds from `lendingFundsReceived`
3. Calculate:
   - Share = `lendingFundsReceived / 13,810,000`
   - Transactional Fee = `share × 35,802,000,000 × 0.0012 × age_multiplier`
   - Monthly Amount = `lendingFundsReceived × 0.02 × age_multiplier`
   - Total Income = `Transactional Fee + Monthly Amount + Licensee Fee + Lending Funds Received`

## Current Data Sources

### Loan Data (from dashboard-metrics.json):
- Total Loans: 60,264,000
- Total Loan Value: $35,802,000,000
- Average Loan: $220,320 (min: $50, max: $5,000)

### Total Lending Funds:
- Sum of all licensees: $13,810,000

## File Location
- **Licensee Data**: `data/licensee-income.json`
- **Dashboard Metrics**: `data/dashboard-metrics.json`

## Notes
- Random variation (±8%) is applied to make numbers more realistic
- Total Income is automatically recalculated when fees are updated
- Age multipliers are randomly selected within their ranges for each licensee
- Higher lending funds = higher fees (proportionally)
- Older licensees = higher fees (due to age multiplier)

## Example: Adjusting SageEndeavours (New Licensee with High Capital)

SageEndeavours has $720,000 in lending funds but is NEW, so:
- Current: Fee = $673,599, Monthly = $4,126
- To lower further: Reduce NEW multiplier to 0.1-0.2 range
- Or: Add a cap for new licensees regardless of capital

## Future Adjustments

When adjusting for different aging:
1. Identify which licensees should move between categories
2. Update the category lists in the script
3. Adjust multiplier ranges if needed
4. Run the update script
5. Verify results match expectations

