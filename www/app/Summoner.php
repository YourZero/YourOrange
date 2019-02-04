<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 28/12/2018
 * Time: 22:31
 */

namespace YourOrange;
/**
 * Class Summoner
 * @package YourOrange
 * @property string id
 * @property string accountId
 * @property string puuid
 * @property string name
 * @property string profileIconId
 * @property int revisionDate
 * @property int summonerLevel
 * @property string nameKey
 * @property int updatedAt
 * @property int matchListUpdate
 */
class Summoner extends RiotAPI
{
    protected $endpoint = "lol/summoner/v4/summoners/";

    protected $table = "summoners";

    protected $cacheLimit = 60;

    /**
     * @param $name
     * @return Summoner
     * @throws \Exception
     */
    public function bySummonerName($name)
    {
        $name = $this->formatNameKey($name);
        /**
         * @var Summoner $summoner
         */
        $summoner = static::where($this->db, ['nameKey' => $name])->first();
        if ($this->validCache($summoner)) {
            return $summoner;
        }

        if(!isset($summoner->oldKey)) {
            $summoner = clone $this;
        }

        $summoner->fill(array_merge($this->queryRiot($this->formatURL("by-name/{$name}")), ['nameKey' => $name]));
        $summoner->save();
        return $summoner;
    }

    /**
     * @param $id
     * @return Summoner
     * @throws \Exception
     */
    public function bySummonerId($id)
    {
        $summoner = static::where($this->db, ['id' => $id])->first();
        if ($this->validCache($summoner)) {
            return $summoner;
        }

        if(!isset($summoner->oldKey)) {
            $summoner = clone $this;
        }

        $summoner->fill($this->queryRiot($this->formatURL($id)));
        $summoner->save();
        return $summoner;
    }

    /**
     * @param $id
     * @return Summoner
     * @throws \Exception
     */
    public function byAccountId($id)
    {
        $summoner = static::where($this->db, ['accountId' => $id])->first();
        if ($this->validCache($summoner)) {
            return $summoner;
        }

        if(!isset($summoner->oldKey)) {
            $summoner = clone $this;
        }

        $summoner->fill($this->queryRiot($this->formatURL("by-account/{$id}")));
        $summoner->save();
        return $summoner;
    }

    /**
     * @param $id
     * @return Summoner
     * @throws \Exception
     */
    public function byPUUID($id)
    {
        $summoner = static::where($this->db, ['puuid' => $id])->first();
        if ($this->validCache($summoner)) {
            return $summoner;
        }
        if(!isset($summoner->oldKey)) {
            $summoner = clone $this;
        }

        $summoner->fill($this->queryRiot($this->formatURL("by-puuid/{$id}")));
        $summoner->save();
        return $summoner;
    }

    protected function _save()
    {
        $this->nameKey = $this->formatNameKey();
    }

    protected function formatNameKey($name = null)
    {
        return str_replace(' ', '', strtolower($name ? $name : $this->name));
    }

}