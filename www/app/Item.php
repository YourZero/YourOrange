<?php
namespace YourOrange;

class Item extends Model
{
    protected $table = "items";
    protected $jsonColumns = ['into', 'image', 'gold', 'tags', 'maps', 'stats', 'from', 'effect'];
    protected $timeColumns = false;
}