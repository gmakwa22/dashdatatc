<?php
/**
 * CrewAI PHP Bridge
 * Provides PHP functions to interact with CrewAI Python service
 */

class CrewAIBridge {
    private $apiUrl;
    private $timeout;
    
    public function __construct($apiUrl = 'http://127.0.0.1:5000', $timeout = 300) {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->timeout = $timeout;
    }
    
    /**
     * Check if CrewAI API is available
     */
    public function isAvailable() {
        $ch = curl_init($this->apiUrl . '/health');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
    }
    
    /**
     * Analyze dashboard data
     * 
     * @param string $analysisType 'full', 'metrics', 'loans', or 'aging'
     * @return array Result with 'success', 'analysis', and 'data_analyzed' keys
     */
    public function analyzeDashboard($analysisType = 'full') {
        $ch = curl_init($this->apiUrl . '/analyze');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'analysis_type' => $analysisType
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'API request failed with HTTP code ' . $httpCode
            ];
        }
        
        $result = json_decode($response, true);
        return $result ?: [
            'success' => false,
            'error' => 'Invalid JSON response'
        ];
    }
    
    /**
     * Analyze a specific data file
     * 
     * @param string $fileName Name of the file in data/ directory
     * @return array Analysis result
     */
    public function analyzeFile($fileName) {
        $ch = curl_init($this->apiUrl . '/analyze-file');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'file_name' => $fileName
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'API request failed with HTTP code ' . $httpCode
            ];
        }
        
        $result = json_decode($response, true);
        return $result ?: [
            'success' => false,
            'error' => 'Invalid JSON response'
        ];
    }
    
    /**
     * Analyze an exported JSON file
     * 
     * @param string $filePath Path to the exported JSON file
     * @return array Analysis result
     */
    public function analyzeExport($filePath) {
        $ch = curl_init($this->apiUrl . '/analyze-export');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'file_path' => $filePath
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode !== 200) {
            return [
                'success' => false,
                'error' => 'API request failed with HTTP code ' . $httpCode
            ];
        }
        
        $result = json_decode($response, true);
        return $result ?: [
            'success' => false,
            'error' => 'Invalid JSON response'
        ];
    }
}

