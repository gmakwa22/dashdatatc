#!/usr/bin/env python3
"""
Loan Projection Manager Agent
Specialized CrewAI agent for loan projections and financial forecasting
"""

import json
import os
from pathlib import Path
from datetime import datetime
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

# Get the project root directory
PROJECT_ROOT = Path(__file__).parent
DATA_DIR = PROJECT_ROOT / "data"

def read_current_licensee_file() -> dict:
    """
    Read the current licensee-income.json file to get the baseline.
    This is used to prevent accidental deletions.
    """
    licensee_file = DATA_DIR / "licensee-income.json"
    if not licensee_file.exists():
        return None
    try:
        with open(licensee_file, 'r', encoding='utf-8') as f:
            return json.load(f)
    except:
        return None

def merge_licensee_updates(original_data: dict, new_data: dict) -> dict:
    """
    Merge new data with original data, ensuring no licensees are lost.
    Updates existing licensees, adds new ones, but never deletes.
    
    Args:
        original_data: The original licensee data from file
        new_data: New data that may contain updates
        
    Returns:
        Merged data with all original licensees preserved
    """
    if not original_data or 'licensees' not in original_data:
        return new_data
    
    if 'licensees' not in new_data:
        return original_data
    
    original_licensees = original_data.get('licensees', [])
    new_licensees = new_data.get('licensees', [])
    
    # Create maps by licenseeId
    original_map = {l.get('licenseeId'): l for l in original_licensees if l.get('licenseeId') is not None}
    new_map = {l.get('licenseeId'): l for l in new_licensees if l.get('licenseeId') is not None}
    
    # Start with all original licensees
    merged_licensees = []
    seen_ids = set()
    
    # First, preserve all original licensees (merge updates if they exist)
    for orig in original_licensees:
        lid = orig.get('licenseeId')
        if lid is not None:
            seen_ids.add(lid)
            if lid in new_map:
                # Merge: update with new data but keep all original fields
                merged = orig.copy()
                merged.update(new_map[lid])
                merged_licensees.append(merged)
            else:
                # Keep original unchanged
                merged_licensees.append(orig)
    
    # Add any completely new licensees
    for new_lic in new_licensees:
        lid = new_lic.get('licenseeId')
        if lid is not None and lid not in seen_ids:
            merged_licensees.append(new_lic)
            seen_ids.add(lid)
    
    # Build result
    result = new_data.copy()
    result['licensees'] = merged_licensees
    
    return result

def preserve_all_licensees(licensee_data: dict, original_licensees: list = None) -> dict:
    """
    Safety function to ensure all existing licensees are preserved when updating the file.
    This prevents accidental deletion of licensees while still allowing updates.
    
    Args:
        licensee_data: The licensee data dictionary to validate
        original_licensees: Original list of licensees to merge with (prevents deletions)
        
    Returns:
        Validated licensee_data with all existing licensees preserved
    """
    if 'licensees' not in licensee_data:
        return licensee_data
    
    new_licensees = licensee_data.get('licensees', [])
    
    # If we have original licensees, merge them to prevent deletions
    if original_licensees:
        # Create a map of existing licensees by ID
        existing_map = {l.get('licenseeId'): l for l in original_licensees if l.get('licenseeId')}
        
        # Create a map of new/updated licensees by ID
        new_map = {l.get('licenseeId'): l for l in new_licensees if l.get('licenseeId')}
        
        # Merge: keep all existing, update with new data, add any new ones
        merged_licensees = []
        seen_ids = set()
        
        # First, add all existing licensees (preserving them)
        for orig in original_licensees:
            lid = orig.get('licenseeId')
            if lid is not None:
                seen_ids.add(lid)
                # If there's an update for this licensee, merge it
                if lid in new_map:
                    # Merge: update fields from new_map but keep all original fields
                    merged = orig.copy()
                    merged.update(new_map[lid])
                    merged_licensees.append(merged)
                else:
                    # Keep original as-is
                    merged_licensees.append(orig)
        
        # Add any completely new licensees
        for new_lic in new_licensees:
            lid = new_lic.get('licenseeId')
            if lid is not None and lid not in seen_ids:
                merged_licensees.append(new_lic)
                seen_ids.add(lid)
        
        licensee_data['licensees'] = merged_licensees
    else:
        # No original to compare, just ensure all required fields exist
        preserved_licensees = []
        for licensee in new_licensees:
            if 'licenseeId' not in licensee:
                continue
            
            # Ensure all required fields exist
            preserved = {
                'licenseeId': licensee.get('licenseeId'),
                'licenseeName': licensee.get('licenseeName', ''),
                'transactionalFee': licensee.get('transactionalFee', 0),
                'monthlyAmount': licensee.get('monthlyAmount', 0),
                'licenseeFee': licensee.get('licenseeFee', 0),
                'lendingFundsReceived': licensee.get('lendingFundsReceived', 0),
                'lendingFundsShort': licensee.get('lendingFundsShort', 0),
                'totalIncome': licensee.get('totalIncome', 0),
                'showInNav': licensee.get('showInNav', True),
                'includeInSelect': licensee.get('includeInSelect', True),
                'showInTable': licensee.get('showInTable', True),
                'isAggregate': licensee.get('isAggregate', False)
            }
            
            # Preserve any other fields
            for key, value in licensee.items():
                if key not in preserved:
                    preserved[key] = value
            
            preserved_licensees.append(preserved)
        
        licensee_data['licensees'] = preserved_licensees
    
    return licensee_data

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

@tool
def read_loan_data() -> dict:
    """Read current loan graph data."""
    file_path = DATA_DIR / "loan-graph.json"
    if not file_path.exists():
        return {"error": "Loan data file not found"}
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception as e:
        return {"error": f"Failed to read loan data: {str(e)}"}

@tool
def read_aging_data() -> dict:
    """Read current aging analysis data."""
    file_path = DATA_DIR / "aging-data.json"
    if not file_path.exists():
        return {"error": "Aging data file not found"}
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception as e:
        return {"error": f"Failed to read aging data: {str(e)}"}

@tool
def read_metrics() -> dict:
    """Read current dashboard metrics."""
    file_path = DATA_DIR / "dashboard-metrics.json"
    if not file_path.exists():
        return {"error": "Metrics file not found"}
    try:
        with open(file_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception as e:
        return {"error": f"Failed to read metrics: {str(e)}"}

@tool
def calculate_projection(current_amount: float, growth_rate: float, periods: int) -> dict:
    """Calculate loan projection based on current amount, growth rate, and number of periods."""
    projections = []
    amount = current_amount
    
    for period in range(1, periods + 1):
        amount = amount * (1 + growth_rate / 100)
        projections.append({
            "period": period,
            "projected_amount": round(amount, 2),
            "growth": round(amount - current_amount, 2)
        })
    
    return {
        "current_amount": current_amount,
        "growth_rate": growth_rate,
        "periods": periods,
        "projections": projections,
        "final_amount": round(amount, 2),
        "total_growth": round(amount - current_amount, 2)
    }

@tool
def write_json_file(filepath: str, data: dict) -> str:
    """Write data to a JSON file in the data directory. Has full write access to all JSON files in the data folder."""
    # Ensure filepath is relative to data directory
    if not filepath.startswith('data/'):
        if filepath.startswith('/'):
            filepath = filepath[1:]
        if not filepath.startswith('data/'):
            filepath = 'data/' + filepath
    
    # Remove 'data/' prefix for path construction
    relative_path = filepath.replace('data/', '') if filepath.startswith('data/') else filepath
    full_path = DATA_DIR / relative_path
    
    try:
        # CRITICAL SAFEGUARD: If writing to licensee-income.json, merge with existing to prevent deletions
        if 'licensee-income' in relative_path.lower() or 'licensee-income' in filepath.lower():
            # Read current file to merge with existing data
            original_data = read_current_licensee_file()
            if original_data and isinstance(data, dict) and 'licensees' in data:
                original_count = len([l for l in original_data.get('licensees', []) if not l.get('isAggregate', False)])
                # Merge: preserve all original, update with new data
                data = merge_licensee_updates(original_data, data)
                preserved_count = len([l for l in data.get('licensees', []) if not l.get('isAggregate', False)])
                
                if preserved_count < original_count:
                    return f"Error: Would lose licensees! Original: {original_count}, After write: {preserved_count}. Write cancelled to prevent data loss."
        
        # Ensure directory exists
        full_path.parent.mkdir(parents=True, exist_ok=True)
        
        # Ensure meta section exists with updatedAt timestamp
        if isinstance(data, dict):
            if 'meta' not in data:
                data['meta'] = {}
            data['meta']['updatedAt'] = datetime.now().isoformat() + 'Z'
        
        # Write JSON file with proper formatting
        with open(full_path, 'w', encoding='utf-8') as f:
            json.dump(data, f, indent=2, ensure_ascii=False)
        
        # Verify the write was successful
        try:
            with open(full_path, 'r', encoding='utf-8') as f:
                verify_data = json.load(f)
            # Basic verification - file is readable JSON
            if not isinstance(verify_data, dict) and not isinstance(verify_data, list):
                return f"Warning: File written but verification failed - unexpected data type"
        except Exception as verify_error:
            return f"Warning: File written but verification failed: {str(verify_error)}"
        
        return f"Successfully wrote to {filepath} and verified"
    except Exception as e:
        return f"Error writing file: {str(e)}"

@tool
def read_json_file(filepath: str) -> dict:
    """Read and parse any JSON file from the data directory."""
    # Ensure filepath is relative to data directory
    if not filepath.startswith('data/'):
        if filepath.startswith('/'):
            filepath = filepath[1:]
        if not filepath.startswith('data/'):
            filepath = 'data/' + filepath
    
    # Remove 'data/' prefix for path construction
    relative_path = filepath.replace('data/', '') if filepath.startswith('data/') else filepath
    full_path = DATA_DIR / relative_path
    
    if not full_path.exists():
        return {"error": f"File not found: {filepath}"}
    try:
        with open(full_path, 'r', encoding='utf-8') as f:
            return json.load(f)
    except Exception as e:
        return {"error": f"Failed to read file: {str(e)}"}

@tool
def list_data_files() -> dict:
    """List all JSON files available in the data directory."""
    try:
        json_files = []
        if DATA_DIR.exists():
            for file_path in DATA_DIR.glob("*.json"):
                json_files.append({
                    "filename": file_path.name,
                    "path": f"data/{file_path.name}",
                    "size": file_path.stat().st_size
                })
        return {
            "files": json_files,
            "count": len(json_files)
        }
    except Exception as e:
        return {"error": f"Failed to list files: {str(e)}"}

@tool
def read_licensee_fee_manual() -> str:
    """Read the Licensee Fee Calculation Manual to understand how Transactional Fee and Monthly Amount are calculated based on licensee age, lending funds, and loan portfolio data."""
    manual_path = PROJECT_ROOT / "LICENSEE_FEE_CALCULATION_MANUAL.md"
    if not manual_path.exists():
        return "Error: LICENSEE_FEE_CALCULATION_MANUAL.md not found"
    try:
        with open(manual_path, 'r', encoding='utf-8') as f:
            return f.read()
    except Exception as e:
        return f"Error reading manual: {str(e)}"

@tool
def distribute_funds_to_licensees(total_amount: float, licensee_ids: list = None, distribution_method: str = "equal") -> dict:
    """
    Distribute funds across licensees for projection dashboard creation.
    
    Args:
        total_amount: Total amount to distribute (e.g., 12000000 for $12 million)
        licensee_ids: List of licensee IDs to distribute to (if None, uses all existing licensees)
        distribution_method: "equal" (equal distribution) or "proportional" (based on current lendingFundsReceived)
    
    Returns:
        dict with distribution plan showing amount per licensee
    """
    try:
        # Read current licensee data
        licensee_file = DATA_DIR / "licensee-income.json"
        if not licensee_file.exists():
            return {"error": "licensee-income.json not found"}
        
        with open(licensee_file, 'r', encoding='utf-8') as f:
            licensee_data = json.load(f)
        
        licensees = licensee_data.get('licensees', [])
        # Filter out aggregate row
        active_licensees = [l for l in licensees if not l.get('isAggregate', False)]
        
        # Filter by licensee_ids if provided
        if licensee_ids:
            active_licensees = [l for l in active_licensees if l.get('licenseeId') in licensee_ids]
        
        if not active_licensees:
            return {"error": "No licensees found to distribute funds to"}
        
        num_licensees = len(active_licensees)
        distribution = []
        
        if distribution_method == "equal":
            # Equal distribution
            amount_per_licensee = total_amount / num_licensees
            for licensee in active_licensees:
                distribution.append({
                    "licenseeId": licensee.get('licenseeId'),
                    "licenseeName": licensee.get('licenseeName'),
                    "currentLendingFunds": licensee.get('lendingFundsReceived', 0),
                    "newLendingFunds": round(amount_per_licensee, 2),
                    "additionalFunds": round(amount_per_licensee, 2)
                })
        else:
            # Proportional distribution based on current lending funds
            total_current = sum(l.get('lendingFundsReceived', 0) for l in active_licensees)
            if total_current == 0:
                # Fall back to equal if no current funds
                amount_per_licensee = total_amount / num_licensees
                for licensee in active_licensees:
                    distribution.append({
                        "licenseeId": licensee.get('licenseeId'),
                        "licenseeName": licensee.get('licenseeName'),
                        "currentLendingFunds": licensee.get('lendingFundsReceived', 0),
                        "newLendingFunds": round(amount_per_licensee, 2),
                        "additionalFunds": round(amount_per_licensee, 2)
                    })
            else:
                for licensee in active_licensees:
                    current = licensee.get('lendingFundsReceived', 0)
                    proportion = current / total_current
                    new_amount = total_amount * proportion
                    distribution.append({
                        "licenseeId": licensee.get('licenseeId'),
                        "licenseeName": licensee.get('licenseeName'),
                        "currentLendingFunds": current,
                        "newLendingFunds": round(new_amount, 2),
                        "additionalFunds": round(new_amount - current, 2)
                    })
        
        return {
            "total_amount": total_amount,
            "distribution_method": distribution_method,
            "num_licensees": num_licensees,
            "distribution": distribution,
            "total_distributed": round(sum(d['newLendingFunds'] for d in distribution), 2)
        }
    except Exception as e:
        return {"error": f"Failed to distribute funds: {str(e)}"}

@tool
def create_new_licensees(num_licensees: int = 1, licensee_name: str = None, website_name: str = None, 
                        transactional_fee: float = 0, monthly_amount: float = 0, 
                        licensee_fee: float = 0, lending_funds_received: float = 0) -> dict:
    """
    Create a new licensee entry with custom data and automatically add it to licensee-income.json.
    This tool can create a single licensee with specific data, or multiple licensees.
    
    Args:
        num_licensees: Number of new licensees to create (default: 1)
        licensee_name: Name for the licensee (e.g., "ABC Loans" or "PremiumCashLoans.Ca")
        website_name: Website/URL name for the licensee (e.g., "PremiumCashLoans.Ca" or "https://premiumcashloans.ca")
        transactional_fee: Initial transactional fee amount
        monthly_amount: Initial monthly amount
        licensee_fee: Initial licensee fee
        lending_funds_received: Initial lending funds received
    
    Returns:
        dict with created licensee information and confirmation message
    
    Example usage:
    - create_new_licensees(1, "ABC Loans", "ABCLoans.Com", 1000, 5000, 2000, 100000)
    - create_new_licensees(1, "PremiumCashLoans.Ca")  # Creates with just a name
    """
    try:
        # Read current licensee data
        licensee_file = DATA_DIR / "licensee-income.json"
        if not licensee_file.exists():
            return {"error": "licensee-income.json not found"}
        
        with open(licensee_file, 'r', encoding='utf-8') as f:
            licensee_data = json.load(f)
        
        licensees = licensee_data.get('licensees', [])
        
        # Find highest licensee ID
        max_id = 0
        for licensee in licensees:
            lid = licensee.get('licenseeId', 0)
            if isinstance(lid, int) and lid > max_id and lid != 999:  # Exclude aggregate
                max_id = lid
        
        # Generate new licensees
        new_licensees = []
        default_names = [
            "PremiumCashLoans.Ca", "ElitePaydayLoans.Com", "PrimeLoanSolutions.Ca",
            "ApexFinancialServices.Com", "CapitalFlowLoans.Ca", "SwiftCashAdvance.Com",
            "TrustedLoanPartners.Ca", "MaxFinanceSolutions.Com", "QuickFundLoans.Ca",
            "SecureLoanServices.Ca", "ProLoanGroup.Com", "FastTrackLoans.Ca",
            "PrimeCapitalLoans.Ca", "EliteFinancialGroup.Com", "PremiumLoanServices.Ca"
        ]
        
        for i in range(num_licensees):
            new_id = max_id + 1 + i
            
            # Determine name - use provided name, or generate one
            if i == 0 and licensee_name:
                name = licensee_name
            elif i == 0:
                # Generate a unique name
                name = f"NewLicensee{new_id}.Com"
                if i < len(default_names):
                    name = default_names[i]
            else:
                # For multiple licensees, generate sequential names
                name = f"NewLicensee{new_id}.Com"
                if i < len(default_names):
                    name = default_names[i]
            
            # Calculate total income
            total_income = transactional_fee + monthly_amount + licensee_fee + lending_funds_received
            
            new_licensees.append({
                "licenseeId": new_id,
                "licenseeName": name,
                "transactionalFee": round(transactional_fee, 2),
                "monthlyAmount": round(monthly_amount, 2),
                "licenseeFee": round(licensee_fee, 2),
                "lendingFundsReceived": round(lending_funds_received, 2),
                "totalIncome": round(total_income, 2),
                "showInNav": True,
                "includeInSelect": True,
                "showInTable": True,
                "isAggregate": False
            })
        
        # Add new licensees to the existing list (before aggregate if it exists)
        aggregate_index = None
        for idx, lic in enumerate(licensees):
            if lic.get('isAggregate', False):
                aggregate_index = idx
                break
        
        if aggregate_index is not None:
            # Insert before aggregate
            licensees[aggregate_index:aggregate_index] = new_licensees
        else:
            # Append to end
            licensees.extend(new_licensees)
        
        # CRITICAL: Read current file and merge to prevent deletions
        original_data = read_current_licensee_file()
        if original_data:
            original_count = len([l for l in original_data.get('licensees', []) if not l.get('isAggregate', False)])
            # Merge new licensees with existing ones
            licensee_data['licensees'] = licensees
            licensee_data = merge_licensee_updates(original_data, licensee_data)
            preserved_count = len([l for l in licensee_data['licensees'] if not l.get('isAggregate', False)])
            if preserved_count < original_count:
                return {
                    "error": f"Would lose licensees! Original: {original_count}, After create: {preserved_count}. Operation cancelled.",
                    "created": 0
                }
        else:
            # No original file, just use what we have
            licensee_data['licensees'] = licensees
        
        # Update meta
        licensee_data['meta'] = {
            'updatedAt': datetime.now().isoformat() + 'Z'
        }
        
        # Write back to file
        try:
            with open(licensee_file, 'w', encoding='utf-8') as f:
                json.dump(licensee_data, f, indent=2, ensure_ascii=False)
            
            # Verify the write was successful by reading it back
            with open(licensee_file, 'r', encoding='utf-8') as f:
                verify_data = json.load(f)
                verify_licensees = verify_data.get('licensees', [])
                # Check if our new licensees are actually in the file
                new_ids = [l['licenseeId'] for l in new_licensees]
                found_ids = [l.get('licenseeId') for l in verify_licensees if l.get('licenseeId') in new_ids]
                if len(found_ids) != len(new_ids):
                    return {
                        "error": f"Write verification failed. Expected {len(new_ids)} new licensees, found {len(found_ids)}",
                        "created": 0
                    }
        except Exception as write_error:
            return {
                "error": f"Failed to write licensee-income.json: {str(write_error)}",
                "created": 0
            }
        
        # If website_name was provided, create the licensee metrics file
        if website_name and len(new_licensees) > 0:
            first_licensee = new_licensees[0]
            licensee_id = first_licensee['licenseeId']
            create_licensee_metrics_file(licensee_id, website_name, licensee_name or first_licensee['licenseeName'])
        
        return {
            "created": len(new_licensees),
            "new_licensees": new_licensees,
            "next_licensee_id": max_id + 1,
            "message": f"Successfully created {len(new_licensees)} new licensee(s) and updated licensee-income.json. New licensee IDs: {', '.join(str(l['licenseeId']) for l in new_licensees)}",
            "verification": "File write verified successfully"
        }
    except Exception as e:
        return {"error": f"Failed to create licensees: {str(e)}"}

@tool
def update_licensee_data(licensee_id: int, updates: dict) -> str:
    """
    Update existing licensee data in licensee-income.json.
    
    Args:
        licensee_id: The ID of the licensee to update
        updates: Dictionary of fields to update (e.g., {'licenseeFee': 200000, 'lendingFundsReceived': 260000, 'lendingFundsShort': 50000})
    
    Returns:
        Success message with details of what was updated
    """
    try:
        # Read current licensee data
        licensee_file = DATA_DIR / "licensee-income.json"
        if not licensee_file.exists():
            return "Error: licensee-income.json not found"
        
        with open(licensee_file, 'r', encoding='utf-8') as f:
            licensee_data = json.load(f)
        
        licensees = licensee_data.get('licensees', [])
        
        # Find the licensee
        licensee = None
        for l in licensees:
            if l.get('licenseeId') == licensee_id and not l.get('isAggregate', False):
                licensee = l
                break
        
        if not licensee:
            return f"Error: Licensee with ID {licensee_id} not found"
        
        # Update fields (only allow updating specific fields, don't allow deleting fields)
        allowed_fields = ['transactionalFee', 'monthlyAmount', 'licenseeFee', 'lendingFundsReceived', 'lendingFundsShort', 'showInNav', 'includeInSelect', 'showInTable']
        updated_fields = []
        for key, value in updates.items():
            if key in allowed_fields and key in licensee:
                old_value = licensee[key]
                licensee[key] = value
                updated_fields.append(f"{key}: {old_value} -> {value}")
            elif key not in allowed_fields:
                updated_fields.append(f"Warning: {key} is not an allowed field to update")
        
        # Recalculate total income if relevant fields were updated
        if any(k in updates for k in ['transactionalFee', 'monthlyAmount', 'licenseeFee', 'lendingFundsReceived']):
            transactional = licensee.get('transactionalFee', 0)
            monthly = licensee.get('monthlyAmount', 0)
            fee = licensee.get('licenseeFee', 0)
            funds = licensee.get('lendingFundsReceived', 0)
            licensee['totalIncome'] = round(transactional + monthly + fee + funds, 2)
            updated_fields.append(f"totalIncome: recalculated to {licensee['totalIncome']}")
        
        # Update meta
        licensee_data['meta'] = {
            'updatedAt': datetime.now().isoformat() + 'Z'
        }
        
        # Ensure all required fields are preserved
        if 'licenseeName' not in licensee or not licensee['licenseeName']:
            return f"Error: Cannot update licensee {licensee_id} - licenseeName is required and cannot be empty"
        
        # Preserve required fields
        required_fields = {
            'licenseeId': licensee_id,
            'licenseeName': licensee.get('licenseeName', ''),
            'showInNav': licensee.get('showInNav', True),
            'includeInSelect': licensee.get('includeInSelect', True),
            'showInTable': licensee.get('showInTable', True),
            'isAggregate': licensee.get('isAggregate', False)
        }
        for key, value in required_fields.items():
            licensee[key] = value
        
        # CRITICAL: Read current file and merge to prevent deletions
        original_data = read_current_licensee_file()
        if original_data:
            original_count = len([l for l in original_data.get('licensees', []) if not l.get('isAggregate', False)])
            # Merge updates with original to preserve all licensees
            licensee_data = merge_licensee_updates(original_data, licensee_data)
            preserved_count = len([l for l in licensee_data['licensees'] if not l.get('isAggregate', False)])
            if preserved_count < original_count:
                return f"Error: Would lose licensees! Original: {original_count}, After update: {preserved_count}. Update cancelled to prevent data loss."
        
        # Write back to file
        with open(licensee_file, 'w', encoding='utf-8') as f:
            json.dump(licensee_data, f, indent=2, ensure_ascii=False)
        
        return f"Successfully updated licensee {licensee_id} ({licensee.get('licenseeName', 'Unknown')}). Changes: {', '.join(updated_fields)}"
    except Exception as e:
        return f"Error updating licensee: {str(e)}"

@tool
def apply_fund_distribution(distribution_plan: dict) -> str:
    """
    Apply a fund distribution plan to update licensee-income.json.
    This updates the lendingFundsReceived for each licensee based on the distribution plan.
    
    Args:
        distribution_plan: Distribution plan from distribute_funds_to_licensees tool
    
    Returns:
        Success message with details
    """
    try:
        if "error" in distribution_plan:
            return f"Error: {distribution_plan['error']}"
        
        # Read current licensee data
        licensee_file = DATA_DIR / "licensee-income.json"
        if not licensee_file.exists():
            return "Error: licensee-income.json not found"
        
        with open(licensee_file, 'r', encoding='utf-8') as f:
            licensee_data = json.load(f)
        
        licensees = licensee_data.get('licensees', [])
        distribution = distribution_plan.get('distribution', [])
        
        # Create a map of licensee ID to distribution amount
        dist_map = {d['licenseeId']: d['newLendingFunds'] for d in distribution}
        
        # Update licensees
        updated_count = 0
        for licensee in licensees:
            lid = licensee.get('licenseeId')
            if lid in dist_map and not licensee.get('isAggregate', False):
                # Update lending funds and recalculate total income
                old_funds = licensee.get('lendingFundsReceived', 0)
                new_funds = dist_map[lid]
                licensee['lendingFundsReceived'] = new_funds
                
                # Recalculate total income (transactionalFee + monthlyAmount + licenseeFee + lendingFundsReceived)
                # Or maintain existing calculation
                transactional = licensee.get('transactionalFee', 0)
                monthly = licensee.get('monthlyAmount', 0)
                fee = licensee.get('licenseeFee', 0)
                licensee['totalIncome'] = round(transactional + monthly + fee + new_funds, 2)
                
                updated_count += 1
        
        # CRITICAL: Read current file and merge to prevent deletions
        original_data = read_current_licensee_file()
        if original_data:
            original_count = len([l for l in original_data.get('licensees', []) if not l.get('isAggregate', False)])
            # Merge updates with original to preserve all licensees
            licensee_data = merge_licensee_updates(original_data, licensee_data)
            preserved_count = len([l for l in licensee_data['licensees'] if not l.get('isAggregate', False)])
            if preserved_count < original_count:
                return f"Error: Would lose licensees! Original: {original_count}, After update: {preserved_count}. Update cancelled to prevent data loss."
        
        # Update meta
        licensee_data['meta'] = {
            'updatedAt': datetime.now().isoformat() + 'Z'
        }
        
        # Write back to file
        with open(licensee_file, 'w', encoding='utf-8') as f:
            json.dump(licensee_data, f, indent=2, ensure_ascii=False)
        
        return f"Successfully updated {updated_count} licensees with new fund distribution. Total distributed: ${distribution_plan.get('total_distributed', 0):,.2f}"
    except Exception as e:
        return f"Error applying distribution: {str(e)}"

def create_loan_projection_manager():
    """Create the Loan Projection Manager agent."""
    agent_kwargs = {
        'role': 'Loan Projection Manager',
        'goal': 'Provide accurate loan projections, financial forecasts, and strategic insights for loan portfolio management',
        'backstory': """You are an expert Loan Projection Manager specializing in creating projection dashboards 
        for potential buyers. Your primary role is to:
        
        1. **Fund Distribution**: Distribute investment funds (e.g., $12 million) across multiple licensees
        2. **Licensee Management**: Create new licensees and manage existing ones
        3. **Projection Calculations**: Generate realistic financial projections based on fund distribution
        4. **Dashboard Updates**: Update all JSON files to reflect the projection scenario
        
        You excel at:
        - Creating new licensee entries when asked (use create_new_licensees tool - it automatically saves to file)
        - Distributing funds across licensees (equal or proportional distribution)
        - Calculating realistic income projections based on fund distribution
        - Updating dashboard metrics, loan graphs, and aging data to match projections
        - Creating "what-if" scenarios for potential buyers
        - Understanding licensee fee calculations (use read_licensee_fee_manual tool for detailed formulas)
        
        When users ask to "create new licensees", "add licensees", or "make X new licensees":
        IMMEDIATELY use the create_new_licensees tool. It will automatically:
        - Create the licensee entries with proper structure
        - Assign sequential IDs
        - Add them to licensee-income.json
        - You don't need to manually write the file - the tool does it automatically
        
        IMPORTANT: You have FULL READ AND WRITE ACCESS to all JSON files in the data/ folder.
        You can read, modify, and update any data file including:
        - dashboard-metrics.json (balances, funding, ROI, territories)
        - loan-graph.json (loan categories, disbursements, collections)
        - aging-data.json (aging analysis by time periods)
        - licensee-income.json (licensee financial data - you can create new licensees here)
        - All licensee-specific files (lic-{id}-*.json)
        
        When creating projection dashboards:
        - Use distribute_funds_to_licensees to plan fund distribution
        - Use create_new_licensees to add new licensees if needed
        - Use apply_fund_distribution to update licensee-income.json
        - Update dashboard-metrics.json to reflect total investment and ROI
        - Update loan-graph.json based on projected loan activity
        - Always maintain proper JSON structure and include meta.updatedAt timestamp""",
        'tools': [read_loan_data, read_aging_data, read_metrics, calculate_projection, write_json_file, read_json_file, list_data_files, distribute_funds_to_licensees, create_new_licensees, update_licensee_data, apply_fund_distribution, read_licensee_fee_manual],
        'verbose': True,
        'allow_delegation': False
    }
    
    # Add LLM if configured
    if llm:
        agent_kwargs['llm'] = llm
    
    return Agent(**agent_kwargs)

def chat_with_manager(user_message: str, conversation_history: list = None) -> dict:
    """
    Chat with the Loan Projection Manager agent.
    
    Args:
        user_message: The user's message
        conversation_history: Previous conversation messages (optional)
    
    Returns:
        dict with agent response
    """
    if not llm:
        return {
            "success": False,
            "error": "No API key configured. Please set GROK_API_KEY or OPENAI_API_KEY in .env file"
        }
    
    try:
        # Create the agent
        manager = create_loan_projection_manager()
        
        # Prepare context from conversation history
        context = ""
        if conversation_history:
            context = "\n\nPrevious conversation:\n"
            for msg in conversation_history[-5:]:  # Last 5 messages for context
                context += f"User: {msg.get('user', '')}\n"
                context += f"Assistant: {msg.get('assistant', '')}\n"
        
        # Create a task for the chat interaction
        task = Task(
            description=f"""{context}
            
Current user question: {user_message}

You are the Loan Projection Manager with FULL READ AND WRITE ACCESS to all JSON files in the data/ folder.

Available Tools:
- read_loan_data: Read loan graph data
- read_aging_data: Read aging analysis data
- read_metrics: Read dashboard metrics
- read_json_file: Read any JSON file from data folder
- write_json_file: Write/update any JSON file in data folder (FULL WRITE ACCESS)
- list_data_files: List all available JSON files
- calculate_projection: Calculate loan projections
- distribute_funds_to_licensees: Distribute funds across licensees (for projection dashboards)
- create_new_licensees: Create new licensee entries
- apply_fund_distribution: Apply fund distribution plan to update licensee-income.json
- read_licensee_fee_manual: Read the Licensee Fee Calculation Manual (explains Transactional Fee and Monthly Amount calculations)

PRIMARY USE CASE - Projection Dashboard Creation:
When the user wants to create a projection dashboard (e.g., "distribute $12 million over 14 licensees"):
1. FIRST: Check current licensees by reading licensee-income.json
2. If user asks to "create X new licensees" or "add X licensees": IMMEDIATELY use create_new_licensees tool
   - This tool automatically adds them to licensee-income.json - no need to manually update
3. Use distribute_funds_to_licensees to create a distribution plan
4. Use apply_fund_distribution to update licensee-income.json with the new funds
5. Update dashboard-metrics.json to reflect the total investment
6. Recalculate and update loan-graph.json based on projected activity
7. Update aging-data.json if needed

IMPORTANT: You have TWO ways to create new licensees:

METHOD 1: Use create_new_licensees tool (automated - handles ID generation and file creation)
METHOD 2: Direct JSON manipulation using write_json_file (more flexible - you control everything):
  1. Read licensee-income.json using read_json_file("licensee-income.json")
  2. Find the highest licenseeId in the licensees array
  3. Create a new licensee object with:
     - licenseeId: highest_id + 1
     - licenseeName: [unique name from user or generate one]
     - transactionalFee, monthlyAmount, licenseeFee, lendingFundsReceived: [values from user or 0]
     - totalIncome: sum of the above
     - showInNav, includeInSelect, showInTable: true
     - isAggregate: false
  4. Add it to the licensees array (before aggregate if exists)
  5. Update meta.updatedAt timestamp
  6. Write back using write_json_file("licensee-income.json", updated_data)
  7. If website/URL provided, create lic-{id}-metrics.json file using write_json_file

If the create_new_licensees tool isn't working, use METHOD 2 (direct JSON manipulation).

When the user asks you to:
- CREATE NEW LICENSEES: When user asks to "add a new licensee", "create a licensee", or similar:
  
  OPTION 1 (Recommended): Use create_new_licensees tool - it handles ID generation and file creation automatically
  
  OPTION 2 (Alternative): You can also do it manually:
  1. Read licensee-income.json using read_json_file
  2. Find the highest licenseeId
  3. Add a new entry to the licensees array with:
     - licenseeId: highest_id + 1
     - licenseeName: [name from user]
     - transactionalFee, monthlyAmount, licenseeFee, lendingFundsReceived: [data from user or 0]
     - totalIncome: sum of the above
     - showInNav, includeInSelect, showInTable: true
     - isAggregate: false
  4. Write back using write_json_file("licensee-income.json", updated_data)
  5. If website/URL provided, create lic-{id}-metrics.json using write_json_file
  
  IMMEDIATELY use create_new_licensees tool with these parameters:
  * num_licensees: Always 1 for a single licensee
  * licensee_name: REQUIRED - Extract the name from user's request, or generate a unique one
  * website_name: REQUIRED - Extract URL/website from user's request, or use licensee_name as website
  * transactional_fee: Optional financial data (default 0)
  * monthly_amount: Optional financial data (default 0)
  * licensee_fee: Optional financial data (default 0)
  * lending_funds_received: Optional financial data (default 0)
  
  The tool will:
  - Create the licensee entry with proper ID
  - Add it to licensee-income.json automatically
  - Create lic-{id}-metrics.json file if website_name is provided
  - Calculate totalIncome automatically
  
  Example: "add a new licensee give it a Unique name and url put some data into it"
    → Generate unique name and URL, add some financial data
    → Use create_new_licensees(1, "UniqueLicenseeName", "UniqueLicenseeURL.com", 1000, 5000, 2000, 50000)
  
  Example: "Create a new licensee named ABC Loans with website ABCLoans.com" 
    → Use create_new_licensees(1, "ABC Loans", "ABCLoans.com", 0, 0, 0, 0)
  
  Example: "Add a licensee with name XYZ Financial, website xyzfinancial.ca, and $100,000 in lending funds"
    → Use create_new_licensees(1, "XYZ Financial", "xyzfinancial.ca", 0, 0, 0, 100000)
  
- UPDATE LICENSEE DATA: Use update_licensee_data tool to modify existing licensee information
  
- DISTRIBUTE FUNDS: Use distribute_funds_to_licensees, then apply_fund_distribution
- READ data: Use read_json_file, read_loan_data, read_aging_data, or read_metrics
- UPDATE/MODIFY data: Use write_json_file to update the JSON file
- CALCULATE projections: Use calculate_projection tool
- LIST files: Use list_data_files

IMPORTANT FOR WRITING DATA:
- Always read the file first to see its current structure
- Maintain the exact JSON structure (don't remove fields, only update values)
- Include the 'meta' section with 'updatedAt' timestamp in ISO 8601 format (e.g., "2025-01-15T10:30:00Z")
- Use numbers only (no currency symbols like $ in JSON)
- Validate the structure matches the original file format
- You can update: dashboard-metrics.json, loan-graph.json, aging-data.json, licensee-income.json, and all lic-*.json files

Be conversational, helpful, and provide specific insights. When creating projection dashboards, work step-by-step and confirm each update.

VERIFICATION - After creating/updating licensees:
- Check the tool's return value - if it contains "error" or "failed", the operation did NOT work
- If the tool returns "Successfully created" or "Successfully wrote", the operation worked
- The create_new_licensees tool now verifies writes automatically - trust its return value
- If you're unsure, you can read the file back using read_json_file("licensee-income.json") to verify""",
            agent=manager,
            expected_output="A clear, helpful response. If data was updated, confirm what was changed."
        )
        
        # Create crew and execute
        # Explicitly set the LLM for the crew to ensure it uses our custom Grok configuration
        crew_kwargs = {
            'agents': [manager],
            'tasks': [task],
            'process': Process.sequential,
            'verbose': False  # Less verbose for chat interface
        }
        
        # If we have a custom LLM (Grok), ensure Crew uses it
        if llm:
            crew_kwargs['llm'] = llm
        
        crew = Crew(**crew_kwargs)
        
        result = crew.kickoff()
        
        return {
            "success": True,
            "response": str(result)
        }
    
    except Exception as e:
        return {
            "success": False,
            "error": str(e)
        }

if __name__ == "__main__":
    # Test the chat function
    import sys
    if len(sys.argv) > 1:
        message = sys.argv[1]
        result = chat_with_manager(message)
        print(json.dumps(result, indent=2))
    else:
        print("Usage: python loan_projection_manager.py 'Your question here'")

