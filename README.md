# PHP Rate Limiter

This project implements a basic rate limiting feature in PHP using Redis. It prevents users from making more than 3 requests per minute from the same IP address.

## Summary of Code Block

This PHP code implements a rate limiting feature to prevent users from making too many requests in a short period of time. The main components are:

1. **Custom Exception (`LimitExceeded`)**: Defines a custom exception class for handling rate limit exceedance.
2. **Rate Limiter Class (`RateLimiter`)**: 
    - Connects to a Redis server.
    - Increments a counter for each IP address and sets a one-minute expiration on the counter.
    - Throws a `LimitExceeded` exception if the counter exceeds the limit of 3 requests per minute.
3. **IP Address Retrieval (`getRealUserIp` function)**: Retrieves the user's real IP address, accounting for possible proxies.
4. **Rate Limiting Logic**: 
    - Instantiates the `RateLimiter` class.
    - Applies rate limiting for POST requests, responding with an HTTP 429 status code and an error message if the rate limit is exceeded.

## Requirements

- PHP 7.0 or higher
- Redis server
- phpredis extension

## Installation

1. **Clone the repository:**

    ```sh
    git clone https://github.com/wpuprise/php-redis-rate-limiter.git
    cd php-redis-rate-limiter
    ```

2. **Install Redis:**

    Follow the instructions on the [Redis website](https://redis.io/download) to install Redis.

3. **Install phpredis:**

    Follow the instructions on the [phpredis GitHub page](https://github.com/phpredis/phpredis#installation) to install the phpredis extension.

## Usage

Include the `RateLimiter` class in your project and use it to limit the rate of POST requests.

Example usage:

```php
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

