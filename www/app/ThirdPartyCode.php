<?php

namespace YourOrange;

/**
 * Class ThirdPartyCode
 * @package YourOrange
 */
class ThirdPartyCode extends RiotAPI
{
    protected $endpoint = "lol/platform/v4/third-party-code/by-summoner/";

    /**
     * @param Summoner $summoner
     * @return mixed
     * @throws \Exception
     */
    public function bySummoner(Summoner $summoner)
    {
        return $this->queryRiot($this->formatURL($summoner->id));
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateCode()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < random_int(20, 256); $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}