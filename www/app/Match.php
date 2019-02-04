<?php

namespace YourOrange;

class Match extends RiotAPI
{
    protected $table = "matches";
    protected $timeColumns = false;
    protected $jsonColumns = [];
    protected $endpoint = "";
}