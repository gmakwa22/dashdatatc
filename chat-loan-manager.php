<?php
/**
 * Loan Projection Manager Chat Interface
 * Interactive chat with the CrewAI Loan Projection Manager agent
 */

require_once __DIR__ . '/crewai-php-bridge.php';

$bridge = new CrewAIBridge();
$isAvailable = $bridge->isAvailable();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loan Projection Manager - Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="dist/vendors/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="dist/vendors/fontawesome/css/all.min.css">
    <style>
        body {
            background: #181825;
            color: #f5f5f5;
            min-height: 100vh;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        .chat-container {
            max-width: 1200px;
            margin: 0 auto;
            height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
            background: #1f1f2e;
            border: 1px solid #2c2c3d;
            border-radius: 10px;
            overflow: hidden;
        }
        .chat-header {
            background: #12121b;
            border-bottom: 1px solid #2c2c3d;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .chat-header h3 {
            margin: 0;
            color: #5e5efc;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-available {
            background: #198754;
            color: #fff;
        }
        .status-unavailable {
            background: #dc3545;
            color: #fff;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #181825;
        }
        .message {
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
        }
        .message.user {
            justify-content: flex-end;
        }
        .message-content {
            max-width: 70%;
            padding: 12px 16px;
            border-radius: 12px;
            word-wrap: break-word;
        }
        .message.user .message-content {
            background: #5e5efc;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .message.assistant .message-content {
            background: #2c2c3d;
            color: #f5f5f5;
            border-bottom-left-radius: 4px;
        }
        .message.assistant .message-content pre {
            background: #12121b;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 10px 0;
        }
        .message-time {
            font-size: 11px;
            color: #888;
            margin-top: 5px;
        }
        .chat-input-container {
            background: #12121b;
            border-top: 1px solid #2c2c3d;
            padding: 20px;
        }
        .chat-input-wrapper {
            display: flex;
            gap: 10px;
        }
        .chat-input {
            flex: 1;
            background: #1f1f2e;
            border: 1px solid #3a3a4d;
            color: #fff;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
        }
        .chat-input:focus {
            outline: none;
            border-color: #5e5efc;
        }
        .btn-send {
            background: #5e5efc;
            border: none;
            color: #fff;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-send:hover:not(:disabled) {
            background: #4e4edc;
        }
        .btn-send:disabled {
            background: #3a3a4d;
            cursor: not-allowed;
        }
        .typing-indicator {
            display: none;
            padding: 12px 16px;
            color: #888;
            font-style: italic;
        }
        .typing-indicator.active {
            display: block;
        }
        .suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }
        .suggestion-btn {
            background: #2c2c3d;
            border: 1px solid #3a3a4d;
            color: #d0d0f5;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .suggestion-btn:hover {
            background: #3a3a4d;
            border-color: #5e5efc;
        }
        .back-link {
            color: #d0d0f5;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <div>
                <h3><i class="fas fa-chart-line"></i> Loan Projection Manager</h3>
                <span class="status-badge <?php echo $isAvailable ? 'status-available' : 'status-unavailable'; ?>">
                    <?php echo $isAvailable ? '✓ Online' : '✗ Offline'; ?>
                </span>
            </div>
            <a href="admin.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Admin</a>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="message assistant">
                <div class="message-content">
                    <p>Hello! I'm your Loan Projection Manager. I can help you with:</p>
                    <ul>
                        <li>Loan projections and financial forecasts</li>
                        <li>Analyzing current loan portfolio data</li>
                        <li>ROI and collection rate insights</li>
                        <li>Aging analysis and recovery strategies</li>
                        <li>Territory and licensee performance</li>
                    </ul>
                    <p>What would you like to know?</p>
                </div>
            </div>
        </div>
        
        <div class="chat-input-container">
            <div class="suggestions" id="suggestions">
                <button class="suggestion-btn" onclick="sendSuggestion('What are the current loan projections?')">Current Projections</button>
                <button class="suggestion-btn" onclick="sendSuggestion('Analyze the collection rates')">Collection Rates</button>
                <button class="suggestion-btn" onclick="sendSuggestion('What is the ROI forecast?')">ROI Forecast</button>
                <button class="suggestion-btn" onclick="sendSuggestion('Show aging analysis insights')">Aging Analysis</button>
            </div>
            <div class="typing-indicator" id="typingIndicator">
                <i class="fas fa-circle-notch fa-spin"></i> Loan Projection Manager is thinking...
            </div>
            <div class="chat-input-wrapper">
                <input 
                    type="text" 
                    class="chat-input" 
                    id="chatInput" 
                    placeholder="Ask about loan projections, metrics, or analysis..."
                    <?php echo $isAvailable ? '' : 'disabled'; ?>
                >
                <button class="btn-send" id="sendBtn" onclick="sendMessage()" <?php echo $isAvailable ? '' : 'disabled'; ?>>
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
        </div>
    </div>

    <script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
    <script>
        const conversationHistory = [];
        const apiUrl = 'http://127.0.0.1:5000/chat';
        
        function addMessage(content, isUser = false) {
            const messagesDiv = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'assistant'}`;
            
            const time = new Date().toLocaleTimeString();
            const contentDiv = document.createElement('div');
            contentDiv.className = 'message-content';
            
            // Format the content (preserve line breaks and code blocks)
            let formattedContent = content.replace(/\n/g, '<br>');
            // Simple code block detection
            formattedContent = formattedContent.replace(/```([\s\S]*?)```/g, '<pre>$1</pre>');
            
            contentDiv.innerHTML = formattedContent;
            
            const timeDiv = document.createElement('div');
            timeDiv.className = 'message-time';
            timeDiv.textContent = time;
            
            messageDiv.appendChild(contentDiv);
            messageDiv.appendChild(timeDiv);
            messagesDiv.appendChild(messageDiv);
            
            // Scroll to bottom
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }
        
        function sendSuggestion(text) {
            document.getElementById('chatInput').value = text;
            sendMessage();
        }
        
        function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            
            if (!message) return;
            
            // Clear input
            input.value = '';
            
            // Add user message to chat
            addMessage(message, true);
            
            // Show typing indicator
            document.getElementById('typingIndicator').classList.add('active');
            document.getElementById('sendBtn').disabled = true;
            document.getElementById('chatInput').disabled = true;
            
            // Hide suggestions after first message
            document.getElementById('suggestions').style.display = 'none';
            
            // Send to API
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    conversation_history: conversationHistory
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('typingIndicator').classList.remove('active');
                document.getElementById('sendBtn').disabled = false;
                document.getElementById('chatInput').disabled = false;
                
                if (data.success) {
                    addMessage(data.response, false);
                    // Update conversation history
                    conversationHistory.push({
                        user: message,
                        assistant: data.response
                    });
                } else {
                    addMessage('Sorry, I encountered an error: ' + (data.error || 'Unknown error'), false);
                }
                
                // Focus input
                input.focus();
            })
            .catch(error => {
                document.getElementById('typingIndicator').classList.remove('active');
                document.getElementById('sendBtn').disabled = false;
                document.getElementById('chatInput').disabled = false;
                
                addMessage('Error connecting to Loan Projection Manager. Make sure the CrewAI API service is running.', false);
                console.error('Error:', error);
                input.focus();
            });
        }
        
        // Allow Enter key to send
        document.getElementById('chatInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Focus input on load
        document.getElementById('chatInput').focus();
    </script>
</body>
</html>

