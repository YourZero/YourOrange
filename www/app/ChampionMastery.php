<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 29/12/2018
 * Time: 22:15
 */

namespace YourOrange;


class ChampionMastery extends RiotAPI
{
    protected $endpoint = "/lol/champion-mastery/v4/";

    /**
     * @param Summoner $summoner
     * @return ChampionMastery
     * @throws \Exception
     */
    public function bySummoner(Summoner $summoner, $championId = null)
    {
        $championMastery = clone $this;
        $championMastery->fill($this->queryRiot($this->formatURL("champion-masteries/by-summoner/{$summoner->id}")));
        return $championMastery;
    }

    public function total(Summoner $summoner)
    {
        $championMastery = clone $this;
        $championMastery->fill($this->queryRiot($this->formatURL("scores/by-summoner/{$summoner->id}")));
        return $championMastery;
    }


}