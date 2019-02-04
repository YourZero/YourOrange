<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 29/12/2018
 * Time: 16:22
 */

namespace YourOrange;


class Champion extends Model
{
    protected $table = "champions";
    protected $jsonColumns = ['info', 'image', 'tags', 'stats'];
    protected $timeColumns = false;
}