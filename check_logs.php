<?php

$logFile = 'storage/logs/laravel.log';

if (file_exists($logFile)) {
    $lines = file($logFile);
    $totalLines = count($lines);
    
    echo "Total lines in log: $totalLines\n\n";
    echo "Last 20 lines of the log:\n";
    echo str_repeat("=", 50) . "\n";
    
    for ($i = max(0, $totalLines - 20); $i < $totalLines; $i++) {
        echo ($i + 1) . ": " . $lines[$i];
    }
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "Looking for RetellAI related entries in last 100 lines:\n";
    
    for ($i = max(0, $totalLines - 100); $i < $totalLines; $i++) {
        if (stripos($lines[$i], 'retellai') !== false || 
            stripos($lines[$i], 'retell') !== false ||
            stripos($lines[$i], 'api key') !== false) {
            echo ($i + 1) . ": " . $lines[$i];
        }
    }
} else {
    echo "Log file not found: $logFile\n";
} 