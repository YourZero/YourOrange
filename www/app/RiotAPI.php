<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 28/12/2018
 * Time: 22:32
 */

namespace YourOrange;


use Dotenv\Dotenv;

abstract class RiotAPI extends Model
{
    /**
     * @var string
     */
    protected $apiKey;
    /**
     * @var string
     */
    protected $region;
    /**
     * @var string
     */
    protected $endpoint;
    /**
     * @var int
     */
    protected $cacheLimit;
    /**
     * @var bool
     */
    protected $autoWait = true;
    /**
     * @var string
     */
    protected $cacheColumn;

    /**
     * RiotAPI constructor.
     * @param string $region
     */
    public function __construct($region = 'euw1')
    {
        parent::__construct();
        $dotenv = new Dotenv('../');
        $dotenv->load();
        $this->region = $region;
        $this->apiKey = $_ENV['RIOT_API_KEY'];
    }

    /**
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    protected function queryRiot($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['X-Riot-Token: ' . $this->apiKey]);
        $headers = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($curl, $header) use (&$headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2) {
                return $len;
            }

            $name = strtolower(trim($header[0]));
            $headers[$name] = trim($header[1]);
            return $len;
        });
        $result = json_decode(curl_exec($ch), true);
        $rateLimit = new RateLimit($headers);
        if ($rateLimit->hasExceeded()) {
            if (!$this->autoWait) {
                throw new \RateLimitException($rateLimit->getSleep());
            }
            $rateLimit->wait();
            $result = json_decode(curl_exec($ch), true);
        }
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($responseCode !== 200) {
            throw new \Exception("There was a problem with: {$url}. Message: {$result}. Code: {$responseCode}");
        }

        return $result;
    }

    /**
     * @param $method
     * @return string
     */
    protected function formatURL($method)
    {
        return "https://{$this->region}.api.riotgames.com/{$this->endpoint}{$method}";
    }

    /**
     * @param $class
     * @return bool
     */
    protected function validCache($class)
    {
        if (!$class instanceof static || !isset($class->updatedAt)) {
            return false;
        }

        return (isset($class->cacheColumn) ? $class->{$class->cacheColumn} : $class->updatedAt) > (time() - $this->cacheLimit);
    }


}