# CrewAI Quick Start Guide

## 🚀 Quick Setup (5 minutes)

### Step 1: Install Python
- **Required:** Python 3.10, 3.11, 3.12, or 3.13 (3.11 or 3.12 recommended)
- Download from [python.org](https://www.python.org/downloads/)
- **Important:** Check "Add Python to PATH" during installation

### Step 2: Install Dependencies
```bash
pip install -r requirements.txt
```

### Step 3: Configure API Key
1. Copy `.env.example` to `.env`
2. Add your Grok/xAI API key:
   ```
   GROK_API_KEY=your-key-here
   ```
   Get a key at: https://console.x.ai
   
   **Alternative:** You can use OpenAI by setting `OPENAI_API_KEY` instead

### Step 4: Start the Service

**Windows:**
```bash
start-crewai.bat
```

**Mac/Linux:**
```bash
chmod +x start-crewai.sh
./start-crewai.sh
```

**Or manually:**
```bash
python crewai_api.py
```

### Step 5: Use It!
1. Open: `http://localhost/dashdata/admin-crewai.php`
2. Click "Run Analysis"
3. Wait 30-60 seconds for results

## 📁 Files Created

- `crewai_analyzer.py` - Main analysis script
- `crewai_api.py` - API server (Flask)
- `crewai-php-bridge.php` - PHP integration class
- `admin-crewai.php` - Web interface
- `agents.yaml` - Agent configurations
- `tasks.yaml` - Task definitions
- `requirements.txt` - Python dependencies
- `.env.example` - Environment template

## 🔧 Troubleshooting

**"CrewAI API Unavailable"**
- Make sure `crewai_api.py` is running
- Check terminal for error messages

**"Module not found"**
- Run: `pip install -r requirements.txt`

**"Invalid API key"**
- Check your `.env` file has the correct key
- No spaces around the `=` sign

## 📖 Full Documentation

See `CREWAI_SETUP.md` for detailed instructions.

