
### RateLimiter.php

```php
<?php

class LimitExceeded extends Exception {
    public function __construct($message = "Rate limit exceeded", $code = 429, $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

class RateLimiter {
    private $redis;

    public function __construct() {
        $this->redis = new Redis();
        try {
            $this->redis->connect('127.0.0.1', 6379);
        } catch (Exception $e) {
            error_log($e->getMessage());
            throw new Exception("Failed to connect to Redis", 0, $e);
        }
    }

    public function limit($ipAddress) {
        $key = "rate_limit:" . $ipAddress;
        $current = $this->redis->incr($key);
        $this->redis->expire($key, 60);  // Set the limit period to one minute
        if ($current > 3) {
            throw new LimitExceeded();
        }
    }
}

function getRealUserIp() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        return trim($ipList[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
?>

