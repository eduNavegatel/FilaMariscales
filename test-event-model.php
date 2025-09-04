<?php
require_once 'src/config/config.php';
require_once 'src/models/Database.php';
require_once 'src/models/Event.php';

echo "Testing Event model...\n";

try {
    $event = new Event();
    echo "Event model created successfully\n";
    
    echo "Testing getRecentEvents(3)...\n";
    $events = $event->getRecentEvents(3);
    echo "Recent events count: " . count($events) . "\n";
    
    if (!empty($events)) {
        echo "First event: " . print_r($events[0], true) . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

