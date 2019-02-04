<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 29/12/2018
 * Time: 14:52
 */

namespace YourOrange;


class Spectator extends RiotAPI
{
    protected $endpoint = "api.riotgames.com/lol/spectator/v4/";

    /**
     * @param Summoner $summoner
     * @return Spectator
     * @throws \Exception
     */
    public function bySummoner(Summoner $summoner)
    {
        $spectator = clone $this;
        $spectator->fill($this->queryRiot($this->formatURL("active-games/by-summoner/{$summoner->id}")));
        return $spectator;
    }

    /**
     * @return Spectator
     * @throws \Exception
     */
    public function featuredGames()
    {
        $spectator = clone $this;
        $spectator->fill($this->queryRiot($this->formatURL("featured-games")));
        return $spectator;
    }

}
