#!/usr/bin/env python3
"""
CrewAI Dashboard Data Analyzer
Analyzes dashboard JSON data using CrewAI agents and provides insights/recommendations
"""

import json
import os
import sys
from pathlib import Path
from dotenv import load_dotenv
from crewai import Agent, Task, Crew, Process
from crewai.tools import tool

# Try to import CrewAI's LLM class (preferred method)
try:
    from crewai.llm import LLM as CrewAILLM
    USE_CREWAI_LLM = True
except ImportError:
    USE_CREWAI_LLM = False
    CrewAILLM = None
    from langchain_openai import ChatOpenAI

# Load environment variables
load_dotenv()

# Configure LLM - Use Grok/xAI if available, otherwise fall back to OpenAI
grok_api_key = os.getenv('GROK_API_KEY')
openai_api_key = os.getenv('OPENAI_API_KEY')

llm = None

def get_grok_llm(temperature: float = 0.7):
    """Get a Grok LLM instance configured for CrewAI."""
    if not grok_api_key:
        return None
    
    base_url = "https://api.x.ai/v1"
    grok_model = "grok-3"
    
    # Set environment variables for CrewAI compatibility
    # This is the key - set these so CrewAI uses Grok's endpoint
    os.environ["OPENAI_API_KEY"] = grok_api_key
    os.environ["OPENAI_API_BASE"] = base_url
    os.environ["OPENAI_BASE_URL"] = base_url
    
    try:
        # Use CrewAI's LLM class directly (preferred)
        if USE_CREWAI_LLM and CrewAILLM:
            llm = CrewAILLM(
                model=grok_model,
                base_url=base_url,
                api_key=grok_api_key,
                temperature=temperature,
                max_tokens=2048,
                timeout=60,
            )
            return llm
        else:
            # Fallback to ChatOpenAI
            llm = ChatOpenAI(
                model=grok_model,
                openai_api_base=base_url,
                openai_api_key=grok_api_key,
                temperature=temperature,
                max_tokens=4096,
                timeout=60,
                max_retries=3,
            )
            return llm
    except Exception as e:
        print(f"Warning: Failed to configure Grok API: {e}")
        return None

if grok_api_key:
    llm = get_grok_llm(temperature=0.7)
    if llm:
        print("[OK] Configured to use Grok/xAI API")

if not llm and openai_api_key:
    try:
        # Reset environment variables for OpenAI
        os.environ["OPENAI_API_KEY"] = openai_api_key
        if "OPENAI_API_BASE" in os.environ:
            del os.environ["OPENAI_API_BASE"]
        if "OPENAI_BASE_URL" in os.environ:
            del os.environ["OPENAI_BASE_URL"]
        
        if USE_CREWAI_LLM and CrewAILLM:
            llm = CrewAILLM(
                model="gpt-4",
                api_key=openai_api_key,
                temperature=0.7,
                max_tokens=2048,
                timeout=60,
            )
        else:
            llm = ChatOpenAI(
                model="gpt-4",
                api_key=openai_api_key,
                temperature=0.7
            )
        print("[OK] Configured to use OpenAI API")
    except Exception as e:
        print(f"Warning: Failed to configure OpenAI API: {e}")
        llm = None

if not llm:
    print("Warning: No API key found. Please set GROK_API_KEY or OPENAI_API_KEY in .env file")

# Get the project root directory
PROJECT_ROOT = Path(__file__).parent
DATA_DIR = PROJECT_ROOT / "data"

@tool
def read_json_file(filepath: str) -> dict:
    """Read and parse a JSON file from the data directory."""
    full_path = DATA_DIR / filepath
    if not full_path.exists():
        return {"error": f"File not found: {filepath}"}
    try:
        with open(full_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception as e:
        return {"error": f"Failed to read file: {str(e)}"}

@tool
def write_json_file(filepath: str, data: dict) -> str:
    """Write data to a JSON file in the data directory."""
    full_path = DATA_DIR / filepath
    try:
        # Ensure directory exists
        full_path.parent.mkdir(parents=True, exist_ok=True)
        with open(full_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
        return f"Successfully wrote to {filepath}"
    except Exception as e:
        return f"Error writing file: {str(e)}"

@tool
def calculate_metrics(data: dict) -> dict:
    """Calculate financial metrics from dashboard data."""
    metrics = {}
    
    # Extract loan data if available
    if 'loans' in data:
        loans = data['loans']
        total_disbursed = sum(loan.get('disbursedAmount', 0) for loan in loans)
        total_collected = sum(loan.get('collectAmount', 0) for loan in loans)
        metrics['collection_rate'] = (total_collected / total_disbursed * 100) if total_disbursed > 0 else 0
        metrics['total_disbursed'] = total_disbursed
        metrics['total_collected'] = total_collected
    
    # Extract ROI data if available
    if 'cards' in data and 'roi' in data['cards']:
        roi = data['cards']['roi']
        metrics['roi_percentage'] = roi.get('percentage', 0)
        metrics['investment'] = roi.get('investment', 0)
        metrics['income'] = roi.get('income', 0)
    
    return metrics

def create_analyst_agent():
    """Create a financial data analyst agent."""
    agent_kwargs = {
        'role': 'Financial Data Analyst',
        'goal': 'Analyze dashboard financial data and identify trends, anomalies, and opportunities',
        'backstory': """You are an experienced financial analyst specializing in loan portfolios, 
        ROI analysis, and financial dashboard metrics. You excel at identifying patterns, 
        calculating key performance indicators, and providing actionable insights.""",
        'tools': [read_json_file, calculate_metrics],
        'verbose': True,
        'allow_delegation': False
    }
    
    # Add LLM if configured
    if llm:
        agent_kwargs['llm'] = llm
    
    return Agent(**agent_kwargs)

def create_recommendation_agent():
    """Create a strategic recommendation agent."""
    agent_kwargs = {
        'role': 'Strategic Advisor',
        'goal': 'Provide actionable recommendations based on financial analysis',
        'backstory': """You are a strategic financial advisor with expertise in optimizing 
        loan portfolios, improving collection rates, and maximizing ROI. You provide 
        clear, actionable recommendations based on data analysis.""",
        'tools': [write_json_file],
        'verbose': True,
        'allow_delegation': False
    }
    
    # Add LLM if configured
    if llm:
        agent_kwargs['llm'] = llm
    
    return Agent(**agent_kwargs)

def analyze_dashboard_data(analysis_type='full'):
    """
    Analyze dashboard data using CrewAI agents.
    
    Args:
        analysis_type: 'full' (all data), 'metrics' (dashboard-metrics.json only), 
                      'loans' (loan-graph.json only), 'aging' (aging-data.json only)
    
    Returns:
        dict with analysis results and recommendations
    """
    
    # Load data based on analysis type
    data_files = {}
    
    if analysis_type in ['full', 'metrics']:
        metrics_path = DATA_DIR / 'dashboard-metrics.json'
        if metrics_path.exists():
            with open(metrics_path, 'r') as f:
                data_files['metrics'] = json.load(f)
    
    if analysis_type in ['full', 'loans']:
        loans_path = DATA_DIR / 'loan-graph.json'
        if loans_path.exists():
            with open(loans_path, 'r') as f:
                data_files['loans'] = json.load(f)
    
    if analysis_type in ['full', 'aging']:
        aging_path = DATA_DIR / 'aging-data.json'
        if aging_path.exists():
            with open(aging_path, 'r') as f:
                data_files['aging'] = json.load(f)
    
    if not data_files:
        return {
            "success": False,
            "error": "No data files found to analyze"
        }
    
    # Create agents
    analyst = create_analyst_agent()
    advisor = create_recommendation_agent()
    
    # Prepare data summary for analysis
    data_summary = json.dumps(data_files, indent=2)
    
    # Create tasks
    analysis_task = Task(
        description=f"""
        Analyze the following dashboard financial data:
        
        {data_summary}
        
        Please:
        1. Calculate key financial metrics (collection rates, ROI, trends)
        2. Identify any anomalies or concerning patterns
        3. Highlight positive trends and opportunities
        4. Provide a comprehensive analysis summary
        """,
        agent=analyst,
        expected_output="A detailed financial analysis with metrics, trends, and identified patterns"
    )
    
    recommendation_task = Task(
        description="""
        Based on the financial analysis provided, create actionable recommendations:
        
        1. Specific actions to improve collection rates
        2. Strategies to optimize ROI
        3. Risk mitigation suggestions
        4. Opportunities for growth
        
        Format the recommendations clearly and prioritize them by impact.
        """,
        agent=advisor,
        expected_output="Prioritized list of actionable recommendations with expected impact"
    )
    
    # Create and run crew
    crew = Crew(
        agents=[analyst, advisor],
        tasks=[analysis_task, recommendation_task],
        process=Process.sequential,
        verbose=True
    )
    
    try:
        result = crew.kickoff()
        
        return {
            "success": True,
            "analysis": str(result),
            "data_analyzed": list(data_files.keys())
        }
    except Exception as e:
        return {
            "success": False,
            "error": str(e)
        }

def process_exported_data(export_file_path):
    """
    Process an exported JSON file and provide AI-enhanced analysis.
    This can be used to analyze exported data before re-importing.
    """
    if not os.path.exists(export_file_path):
        return {
            "success": False,
            "error": f"Export file not found: {export_file_path}"
        }
    
    with open(export_file_path, 'r') as f:
        exported_data = json.load(f)
    
    # Create analysis agent
    analyst = create_analyst_agent()
    
    analysis_task = Task(
        description=f"""
        Analyze this exported dashboard data and provide insights:
        
        {json.dumps(exported_data, indent=2)}
        
        Focus on:
        1. Data consistency and accuracy
        2. Recommended adjustments
        3. Potential improvements
        """,
        agent=analyst,
        expected_output="Analysis and recommendations for the exported data"
    )
    
    crew = Crew(
        agents=[analyst],
        tasks=[analysis_task],
        process=Process.sequential,
        verbose=True
    )
    
    try:
        result = crew.kickoff()
        return {
            "success": True,
            "analysis": str(result)
        }
    except Exception as e:
        return {
            "success": False,
            "error": str(e)
        }

if __name__ == "__main__":
    # Command line interface
    if len(sys.argv) > 1:
        command = sys.argv[1]
        
        if command == "analyze":
            analysis_type = sys.argv[2] if len(sys.argv) > 2 else 'full'
            result = analyze_dashboard_data(analysis_type)
            print(json.dumps(result, indent=2))
        
        elif command == "process-export":
            if len(sys.argv) < 3:
                print("Usage: python crewai_analyzer.py process-export <export_file_path>")
                sys.exit(1)
            export_path = sys.argv[2]
            result = process_exported_data(export_path)
            print(json.dumps(result, indent=2))
        
        else:
            print("Usage:")
            print("  python crewai_analyzer.py analyze [full|metrics|loans|aging]")
            print("  python crewai_analyzer.py process-export <export_file_path>")
    else:
        # Default: run full analysis
        result = analyze_dashboard_data('full')
        print(json.dumps(result, indent=2))

