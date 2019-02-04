<?php

namespace YourOrange;

class MatchList extends RiotAPI
{
    protected $table = "match_lists";
    protected $key = "gameId";
    protected $endpoint = "lol/match/v4/matchlists/by-account/";
    protected $timeColumns = false;
    protected $cacheColumn = 'timestamp';
    protected $cacheLimit = 3600;

    /**
     * @param Summoner $summoner
     * @param array $params
     * @return Collection
     * @throws \Exception
     */
    public static function bySummoner(Summoner $summoner, $params = [])
    {
        $ml = new self;
        if (in_array('limit', $params)) {
            $limit = $params['limit'];
            unset($params['limit']);
        } else {
            $limit = [];
        }

        $params['summonerId'] = $summoner->id;
        $match = self::where($ml->db, $params, $limit);
        if ($ml->validCache($match)) {
            return $match;
        }

        $collection = new Collection;
        foreach ($ml->queryRiot($ml->formatURL("{$summoner->accountId}"))['matches'] as $row) {
            /**
             * @var Match $match
             */
            $match = Match::where($ml->db, ['gameId' => $row['gameId'], 'summonerId' => $summoner->id])->first();
            var_dump($match);
            if(empty($match)) {
                $match = new self;
            }
            $match->fill(array_merge($row, ['summonerId' => $summoner->id]));
            $match->save();
            $collection->push($match);
        }

        $summoner->matchListUpdate = time();
        $summoner->save();
        return $collection;
    }
}