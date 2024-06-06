<?php

require_once 'RateLimiter.php';

$rateLimiter = new RateLimiter();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ipAddress = getRealUserIp();
        $rateLimiter->limit($ipAddress);
    } catch (LimitExceeded $exception) {
        http_response_code(429);
        die("Rate Limit Exceeded. Please wait before trying again.");
    }

    // Continue with your form processing or other logic here
}
?>

