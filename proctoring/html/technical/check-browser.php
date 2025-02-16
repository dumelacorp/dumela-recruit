<?php
// List of supported browsers and minimum versions
$supportedBrowsers = [
    'Chrome' => 80,
    'Firefox' => 75,
    'Safari' => 13
];

// Get user agent from POST request
$userAgent = $_POST['userAgent'] ?? '';
if (!$userAgent) {
    echo "Error: No user agent provided.";
    exit;
}

// Parse the user agent
$browser = 'Unknown';
$version = 0;

if (preg_match('/Chrome\/([0-9]+)/', $userAgent, $matches)) {
    $browser = 'Chrome';
    $version = (int)$matches[1];
} elseif (preg_match('/Firefox\/([0-9]+)/', $userAgent, $matches)) {
    $browser = 'Firefox';
    $version = (int)$matches[1];
} elseif (preg_match('/Version\/([0-9]+).*Safari/', $userAgent, $matches)) {
    $browser = 'Safari';
    $version = (int)$matches[1];
}

// Check compatibility
if (array_key_exists($browser, $supportedBrowsers) && $version >= $supportedBrowsers[$browser]) {
    echo "Your browser is supported: $browser $version.";
} else {
    echo "Your browser is not supported. Please use one of the supported browsers.";
}
?>
