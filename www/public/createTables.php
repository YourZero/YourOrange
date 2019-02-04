<?php
require "../vendor/autoload.php";

$dir = new DirectoryIterator(__DIR__ . '/../sql');
$db = \YourOrange\DB::getInstance();
foreach ($dir as $fileInfo) {
    if ($fileInfo->isDot()) {
        continue;
    }

    $db->query(file_get_contents($fileInfo->getPathname()));
}