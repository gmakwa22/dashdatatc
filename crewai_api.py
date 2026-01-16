#!/usr/bin/env python3
"""
CrewAI API Server
Provides HTTP API endpoints for PHP to interact with CrewAI
"""

from flask import Flask, request, jsonify
from flask_cors import CORS
import json
import os
from pathlib import Path
from crewai_analyzer import analyze_dashboard_data, process_exported_data
from loan_projection_manager import chat_with_manager

app = Flask(__name__)
CORS(app)  # Enable CORS for PHP requests

PROJECT_ROOT = Path(__file__).parent
DATA_DIR = PROJECT_ROOT / "data"

@app.route('/health', methods=['GET'])
def health_check():
    """Health check endpoint."""
    return jsonify({
        "status": "ok",
        "service": "CrewAI Dashboard Analyzer"
    })

@app.route('/analyze', methods=['POST'])
def analyze():
    """
    Analyze dashboard data.
    
    Expected JSON body:
    {
        "analysis_type": "full" | "metrics" | "loans" | "aging"
    }
    """
    try:
        data = request.get_json() or {}
        analysis_type = data.get('analysis_type', 'full')
        
        result = analyze_dashboard_data(analysis_type)
        return jsonify(result)
    
    except Exception as e:
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

@app.route('/analyze-export', methods=['POST'])
def analyze_export():
    """
    Analyze an exported JSON file.
    
    Expected JSON body:
    {
        "file_path": "path/to/exported/file.json"
    }
    """
    try:
        data = request.get_json() or {}
        file_path = data.get('file_path')
        
        if not file_path:
            return jsonify({
                "success": False,
                "error": "file_path is required"
            }), 400
        
        # Resolve relative paths
        if not os.path.isabs(file_path):
            file_path = PROJECT_ROOT / file_path
        
        result = process_exported_data(str(file_path))
        return jsonify(result)
    
    except Exception as e:
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

@app.route('/analyze-file', methods=['POST'])
def analyze_file():
    """
    Analyze a specific data file.
    
    Expected JSON body:
    {
        "file_name": "dashboard-metrics.json" | "loan-graph.json" | "aging-data.json"
    }
    """
    try:
        data = request.get_json() or {}
        file_name = data.get('file_name')
        
        if not file_name:
            return jsonify({
                "success": False,
                "error": "file_name is required"
            }), 400
        
        file_path = DATA_DIR / file_name
        if not file_path.exists():
            return jsonify({
                "success": False,
                "error": f"File not found: {file_name}"
            }), 404
        
        # Determine analysis type based on file name
        if 'metrics' in file_name:
            analysis_type = 'metrics'
        elif 'loan' in file_name:
            analysis_type = 'loans'
        elif 'aging' in file_name:
            analysis_type = 'aging'
        else:
            analysis_type = 'full'
        
        result = analyze_dashboard_data(analysis_type)
        return jsonify(result)
    
    except Exception as e:
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

@app.route('/chat', methods=['POST'])
def chat():
    """
    Chat with the Loan Projection Manager agent.
    
    Expected JSON body:
    {
        "message": "User's message",
        "conversation_history": [{"user": "...", "assistant": "..."}] (optional)
    }
    """
    try:
        data = request.get_json() or {}
        message = data.get('message')
        conversation_history = data.get('conversation_history', [])
        
        if not message:
            return jsonify({
                "success": False,
                "error": "message is required"
            }), 400
        
        result = chat_with_manager(message, conversation_history)
        return jsonify(result)
    
    except Exception as e:
        return jsonify({
            "success": False,
            "error": str(e)
        }), 500

if __name__ == '__main__':
    # Run on localhost, port 5000
    # In production, use a proper WSGI server like gunicorn
    app.run(host='127.0.0.1', port=5000, debug=False)

