<?php
/**
 * Advanced Analytics & Visitor Tracking System
 * Tracks page views, unique visitors, countries, devices, and more
 */

// Analytics data file
$analyticsFile = __DIR__ . '/analytics_data.json';

// Initialize analytics data
function getAnalyticsData() {
    global $analyticsFile;
    
    if (file_exists($analyticsFile)) {
        $data = json_decode(file_get_contents($analyticsFile), true);
    } else {
        $data = [
            'total_views' => 0,
            'unique_visitors' => 0,
            'visitors' => [],
            'countries' => [],
            'devices' => [],
            'browsers' => [],
            'daily_views' => [],
            'last_updated' => date('Y-m-d H:i:s')
        ];
    }
    
    return $data;
}

// Save analytics data
function saveAnalyticsData($data) {
    global $analyticsFile;
    $data['last_updated'] = date('Y-m-d H:i:s');
    file_put_contents($analyticsFile, json_encode($data, JSON_PRETTY_PRINT));
}

// Get visitor IP
function getVisitorIP() {
    $ip = '';
    
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    
    return $ip;
}

// Get visitor country using IP (simple method)
function getVisitorCountry($ip) {
    // Simple country detection based on IP
    // In production, you'd use a proper GeoIP service
    
    // For demo purposes, using a simple API call
    $country = 'Unknown';
    
    try {
        $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=country,countryCode");
        if ($response) {
            $data = json_decode($response, true);
            $country = $data['country'] ?? 'Unknown';
        }
    } catch (Exception $e) {
        $country = 'Unknown';
    }
    
    return $country;
}

// Get device type
function getDeviceType() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    if (preg_match('/mobile|android|iphone|ipad|tablet/i', $userAgent)) {
        return 'Mobile';
    } elseif (preg_match('/tablet|ipad/i', $userAgent)) {
        return 'Tablet';
    } else {
        return 'Desktop';
    }
}

// Get browser
function getBrowser() {
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    
    if (strpos($userAgent, 'Firefox') !== false) {
        return 'Firefox';
    } elseif (strpos($userAgent, 'Chrome') !== false) {
        return 'Chrome';
    } elseif (strpos($userAgent, 'Safari') !== false) {
        return 'Safari';
    } elseif (strpos($userAgent, 'Edge') !== false) {
        return 'Edge';
    } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
        return 'Opera';
    } else {
        return 'Other';
    }
}

// Track visitor
function trackVisitor() {
    $data = getAnalyticsData();
    
    // Get visitor info
    $ip = getVisitorIP();
    $device = getDeviceType();
    $browser = getBrowser();
    $country = getVisitorCountry($ip);
    $today = date('Y-m-d');
    $timestamp = date('Y-m-d H:i:s');
    
    // Increment total views
    $data['total_views']++;
    
    // Track unique visitors (based on IP)
    if (!in_array($ip, $data['visitors'])) {
        $data['visitors'][] = $ip;
        $data['unique_visitors'] = count($data['visitors']);
    }
    
    // Track countries
    if (!isset($data['countries'][$country])) {
        $data['countries'][$country] = 0;
    }
    $data['countries'][$country]++;
    
    // Track devices
    if (!isset($data['devices'][$device])) {
        $data['devices'][$device] = 0;
    }
    $data['devices'][$device]++;
    
    // Track browsers
    if (!isset($data['browsers'][$browser])) {
        $data['browsers'][$browser] = 0;
    }
    $data['browsers'][$browser]++;
    
    // Track daily views
    if (!isset($data['daily_views'][$today])) {
        $data['daily_views'][$today] = 0;
    }
    $data['daily_views'][$today]++;
    
    // Keep only last 30 days of daily views
    if (count($data['daily_views']) > 30) {
        $data['daily_views'] = array_slice($data['daily_views'], -30, 30, true);
    }
    
    saveAnalyticsData($data);
}

// Get live visitors (last 5 minutes)
function getLiveVisitors() {
    // This is a simple implementation
    // In production, you'd use a proper session tracking system
    $data = getAnalyticsData();
    
    // For demo, return a number between 1-5
    return rand(1, 5);
}

// Get analytics summary
function getAnalyticsSummary() {
    $data = getAnalyticsData();
    
    return [
        'total_views' => $data['total_views'],
        'unique_visitors' => $data['unique_visitors'],
        'live_visitors' => getLiveVisitors(),
        'top_countries' => getTopCountries($data),
        'device_breakdown' => $data['devices'],
        'browser_breakdown' => $data['browsers'],
        'daily_views' => array_slice($data['daily_views'], -7, 7, true), // Last 7 days
        'last_updated' => $data['last_updated']
    ];
}

// Get top countries
function getTopCountries($data, $limit = 5) {
    $countries = $data['countries'] ?? [];
    arsort($countries);
    return array_slice($countries, 0, $limit, true);
}

// Reset analytics (admin only)
function resetAnalytics() {
    global $analyticsFile;
    
    $data = [
        'total_views' => 0,
        'unique_visitors' => 0,
        'visitors' => [],
        'countries' => [],
        'devices' => [],
        'browsers' => [],
        'daily_views' => [],
        'last_updated' => date('Y-m-d H:i:s')
    ];
    
    saveAnalyticsData($data);
}

// Export analytics data
function exportAnalytics() {
    $data = getAnalyticsData();
    
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="analytics-export-' . date('Y-m-d') . '.json"');
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}
?>
