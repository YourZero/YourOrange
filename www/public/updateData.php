<?php
require "../vendor/autoload.php";
$version = "8.24.1";
$staticData = new \YourOrange\StaticData($version);
$staticData->updateItems();
$staticData->updateChampions();