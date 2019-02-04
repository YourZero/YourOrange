<?php

namespace YourOrange;

class RateLimit
{
    /**
     * @var string
     */
    protected $appMax = 'x-app-rate-limit';
    /**
     * @var string
     */
    protected $methodMax = 'x-method-rate-limit';
    /**
     * @var string
     */
    protected $appCurrent = 'x-app-rate-limit-count';
    /**
     * @var string
     */
    protected $methodCurrent = 'x-method-rate-limit-count';
    /**
     * @var string
     */
    protected $limitType = 'x-rate-limit-type';
    /**
     * @var string
     */
    protected $retryAfter = "retry-after";
    /**
     * @var array
     */
    protected $headers;
    /**
     * @var bool
     */
    protected $exceeded = false;
    /**
     * @var int
     */
    protected $sleep = 0;

    public function __construct($headers)
    {
        $this->headers = $headers;
    }

    /**
     * Evaluates the response headers to dynamically read and process the App limit and Method limit.
     */
    protected function processHeaders()
    {
        $appMax = [];
        foreach(explode(',', $this->headers[$this->appMax]) as $v) {
            $values = explode(':', $v);
            $appMax[$values[1]] = $values[0];
        }

        $appCurrent = [];
        foreach(explode(',', $this->headers[$this->appCurrent]) as $v) {
            $values = explode(':', $v);
            $appCurrent[$values[1]] = $values[0];
        }

        foreach($appMax as $k => $v) {
            if($v < $appCurrent[$k]) {
                $this->exceeded = true;
                $this->sleep = (int)$this->headers[$this->retryAfter];
                return;
            }
        }

        $methodMax = [];
        foreach(explode(',', $this->headers[$this->methodMax]) as $v) {
            $values = explode(':', $v);
            $methodMax[$values[1]] = $values[0];
        }

        $methodCurrent = [];
        foreach(explode(',', $this->headers[$this->methodCurrent]) as $v) {
            $values = explode(':', $v);
            $methodCurrent[$values[1]] = $values[0];
        }

        foreach($methodMax as $k => $v) {
            if($v <= $methodCurrent[$k]) {
                $this->exceeded = true;
                $this->sleep = (int)$this->headers[$this->retryAfter];
                return;
            }
        }
    }

    /**
     * @return bool
     */
    public function hasExceeded()
    {
        $this->processHeaders();
        return $this->exceeded;
    }

    /**
     * Wait for the rate limit to reset
     */
    public function wait()
    {
        sleep($this->sleep);
    }

    /**
     * @return int
     */
    public function getSleep()
    {
        return $this->sleep;
    }
}