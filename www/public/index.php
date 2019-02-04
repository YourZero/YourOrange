<?php
require "../vendor/autoload.php";
$db = new \YourOrange\DB('your-orange-dev-mysql', 'your_orange', 'your_orange', 'Archer!01', 'mysql');
$summoner = new \YourOrange\Summoner;
$summoner = $summoner->bySummonerName('project zero');
var_dump(\YourOrange\MatchList::bySummoner($summoner));

?>
<!doctype>
<html lang="en">
<head>
    <title>Your Orange</title>
</head>
<body>
<h1>Your Orange</h1>
</body>
</html>