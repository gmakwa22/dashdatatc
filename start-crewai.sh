#!/bin/bash
echo "Starting CrewAI API Service..."
echo ""
echo "Make sure you have:"
echo "1. Python 3.10+ installed"
echo "2. Dependencies installed: pip install -r requirements.txt"
echo "3. .env file configured with OPENAI_API_KEY"
echo ""
echo "Press Ctrl+C to stop the service"
echo ""
python3 crewai_api.py

