# CrewAI Integration Setup Guide

This guide will help you install and configure CrewAI for your dashboard project.

## Prerequisites

1. **Python 3.10, 3.11, 3.12, or 3.13** - Required for CrewAI (3.11 or 3.12 recommended)
2. **pip** - Python package manager (usually comes with Python)
3. **Grok/xAI API Key** - Required for CrewAI agents (or OpenAI API key as fallback)

## Installation Steps

### 1. Install Python

**Windows:**
- Download Python from [python.org](https://www.python.org/downloads/)
- During installation, check "Add Python to PATH"
- Verify installation:
  ```powershell
  python --version
  ```

**macOS/Linux:**
- Usually pre-installed, or install via:
  ```bash
  brew install python3  # macOS
  sudo apt install python3 python3-pip  # Linux
  ```

### 2. Install Python Dependencies

Navigate to your project directory and install the required packages:

```bash
cd e:\xampp\htdocs\dashdata
pip install -r requirements.txt
```

Or if you need to use `pip3`:

```bash
pip3 install -r requirements.txt
```

### 3. Set Up Environment Variables

1. Copy the example environment file:
   ```bash
   copy .env.example .env
   ```
   (On Linux/Mac: `cp .env.example .env`)

2. Edit `.env` and add your Grok/xAI API key:
   ```
   GROK_API_KEY=your-actual-grok-api-key-here
   ```

   **Getting a Grok/xAI API Key:**
   - Go to [console.x.ai](https://console.x.ai)
   - Sign up or log in
   - Navigate to API Keys section
   - Create a new API key
   - Copy it to your `.env` file
   
   **Alternative:** You can also use OpenAI by setting `OPENAI_API_KEY` instead

### 4. Start the CrewAI API Service

In a terminal/command prompt, run:

```bash
python crewai_api.py
```

You should see:
```
 * Running on http://127.0.0.1:5000
```

**Keep this terminal window open** - the service needs to be running for PHP to communicate with it.

### 5. Access the CrewAI Admin Interface

1. Open your browser and navigate to:
   ```
   http://localhost/dashdata/admin-crewai.php
   ```

2. You should see a green "✓ CrewAI API Available" status badge

3. Use the interface to run analyses on your dashboard data

## Usage

### Via Web Interface

1. Go to `admin-crewai.php`
2. Select analysis type (Full, Metrics, Loans, or Aging)
3. Click "Run Analysis"
4. Wait 30-60 seconds for results
5. Review the analysis output

### Via Command Line

You can also run analyses directly from the command line:

```bash
# Full analysis
python crewai_analyzer.py analyze full

# Analyze specific data type
python crewai_analyzer.py analyze metrics
python crewai_analyzer.py analyze loans
python crewai_analyzer.py analyze aging

# Analyze an exported file
python crewai_analyzer.py process-export path/to/exported-file.json
```

### Via PHP Code

You can integrate CrewAI analysis into your PHP code:

```php
require_once 'crewai-php-bridge.php';

$bridge = new CrewAIBridge();

// Check if service is available
if ($bridge->isAvailable()) {
    // Run full analysis
    $result = $bridge->analyzeDashboard('full');
    
    if ($result['success']) {
        echo "Analysis: " . $result['analysis'];
    } else {
        echo "Error: " . $result['error'];
    }
}
```

## Troubleshooting

### "CrewAI API Unavailable"

**Problem:** The status shows as unavailable

**Solutions:**
1. Make sure `crewai_api.py` is running
2. Check if port 5000 is already in use
3. Verify Python is installed: `python --version`
4. Check for errors in the terminal where you started the service

### "Module not found" errors

**Problem:** Python can't find required modules

**Solution:**
```bash
pip install --upgrade -r requirements.txt
```

### API Key errors

**Problem:** "Invalid API key" or authentication errors

**Solutions:**
1. Verify your `.env` file exists and has the correct key
2. Make sure the key starts with `sk-` (OpenAI) or appropriate prefix
3. Check that the key is active in your OpenAI account
4. Ensure no extra spaces in the `.env` file

### Timeout errors

**Problem:** Analysis takes too long or times out

**Solutions:**
1. Increase timeout in `crewai-php-bridge.php` (default is 300 seconds)
2. Try analyzing smaller datasets first
3. Check your internet connection (API calls are made to OpenAI)

### Port already in use

**Problem:** "Address already in use" when starting the API

**Solutions:**
1. Change the port in `crewai_api.py` (last line):
   ```python
   app.run(host='127.0.0.1', port=5001, debug=False)
   ```
2. Update the API URL in `crewai-php-bridge.php`:
   ```php
   $bridge = new CrewAIBridge('http://127.0.0.1:5001');
   ```

## File Structure

```
dashdata/
├── crewai_analyzer.py      # Main CrewAI analysis script
├── crewai_api.py           # Flask API server
├── crewai-php-bridge.php   # PHP bridge class
├── admin-crewai.php        # Web interface
├── agents.yaml             # Agent configurations
├── tasks.yaml              # Task configurations
├── requirements.txt        # Python dependencies
├── .env                    # Environment variables (create from .env.example)
└── .env.example            # Example environment file
```

## Next Steps

1. **Customize Agents:** Edit `agents.yaml` to modify agent roles and behaviors
2. **Customize Tasks:** Edit `tasks.yaml` to change analysis tasks
3. **Add More Tools:** Extend `crewai_analyzer.py` with additional analysis tools
4. **Integrate Results:** Use analysis results to automatically update dashboard data

## Support

For issues or questions:
- Check CrewAI documentation: https://docs.crewai.com
- Review error messages in the terminal/console
- Check PHP error logs for API communication issues

