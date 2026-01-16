# Data Files

This folder contains all data files used by the dashboard. **All data is separated from code** to ensure that code changes do not affect the data.

## Files

### `loan-graph.json`
Contains loan graph and gauge chart data.

**Structure:**
```json
{
  "loans": [
    {
      "id": 1,
      "particularTitle": "Total Loans",
      "itemCount": 78907,
      "disbursedAmount": 13592123.92,
      "interestAmount": 4312865.04,
      "totalAmount": 32904988.96,
      "collectAmount": 28590410.37,
      "varietyPercent": 100,
      "totalTfpPaid": 1239607.78,
      "totalTfpCollected": 491164.61,
      "displayPercent": 100
    }
  ],
  "gauges": [
    {
      "id": "chartdiv1",
      "title": "Total Collected",
      "value": 42.32
    }
  ]
}
```

**Fields:**
- `loans`: Array of loan category data
  - `varietyPercent`: The "% of Total (Auto)" percentage used in the table and graph
  - `itemCount`: Number of loans in this category
  - Other fields: financial amounts
- `gauges`: Array of gauge chart data (optional - if not provided, first 3 gauges are calculated from loan data)

### `aging-data.json`
Contains aging analysis table data.

**Structure:**
```json
{
  "aging": [
    {
      "label": "0 - 30 Days",
      "loanDisbursement": 33600.05,
      "interest": 7084.05,
      "partialPayment": 2240.81,
      "totalDebt": 57793.03,
      "totalCollected": 2240.81,
      "tfpPayment": 21345,
      "tfpCollected": 0,
      "tfpNet": 0
    }
  ]
}
```

### `dashboard-metrics.json`
Contains dashboard card metrics (balances, funding, ROI, territories).

**Structure:**
```json
{
  "cards": {
    "balances": { "available": 67477, "pending": 106103, ... },
    "funding": { "total": 8475646, ... },
    "roi": { "percentage": 29.57, ... },
    "territories": { "totalAmount": 8620000, ... }
  }
}
```

### `licensee-income.json`
Contains licensee income data for the income table.

## Important Notes

1. **All data must be in JSON format** - Invalid JSON will cause errors
2. **Data files are required** - The dashboard will show error messages if files are missing
3. **No hardcoded defaults** - All data comes from these files
4. **Code changes won't affect data** - You can modify the code without worrying about changing the numbers

## Editing Data

You can edit these JSON files directly, or use the admin interfaces:
- `admin-graphs.php` - Edit loan graph and gauge data
- `admin-aging.php` - Edit aging data
- Other admin pages for other data types

## File Locations

All data files are in: `data/` folder relative to `index.html`

