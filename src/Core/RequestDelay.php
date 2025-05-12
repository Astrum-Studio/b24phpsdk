<?php

declare(strict_types=1);

namespace Bitrix24\SDK\Core;

/**
 * Class RequestDelay
 * Provides functionality to add delays between API requests
 */
class RequestDelay
{
    private static ?self $instance = null;
    private int $lastRequestTime = 0;
    private int $delayMs;

    /**
     * Initialize the delay with specified milliseconds
     * @param int $delayMs Delay in milliseconds between requests
     * @return self
     */
    public static function init(int $delayMs = 500): self
    {
        if (self::$instance === null) {
            self::$instance = new self($delayMs);
        }
        
        return self::$instance;
    }

    /**
     * Check if the instance is created
     * @return bool
     */
    public static function instanceIsCreated(): bool
    {
        return self::$instance !== null;
    }

    /**
     * @param int $delayMs Delay in milliseconds between requests
     */
    private function __construct(int $delayMs = 500)
    {
        $this->delayMs = $delayMs;
    }

    /**
     * Wait for the specified delay time if needed
     */
    public function wait(): void
    {
        $currentTime = (int)(microtime(true) * 1000);
        $timeSinceLastRequest = $currentTime - $this->lastRequestTime;

        if ($timeSinceLastRequest < $this->delayMs) {
            $sleepTime = $this->delayMs - $timeSinceLastRequest;
            usleep($sleepTime * 1000); // Convert milliseconds to microseconds
        }

        $this->lastRequestTime = (int)(microtime(true) * 1000);
    }

    /**
     * Set a new delay time
     * @param int $delayMs New delay in milliseconds
     */
    public function setDelay(int $delayMs): void
    {
        $this->delayMs = $delayMs;
    }

    /**
     * Get current delay time
     * @return int Current delay in milliseconds
     */
    public function getDelay(): int
    {
        return $this->delayMs;
    }

    /**
     * Prevent cloning of the instance
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserializing of the instance
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }
} 