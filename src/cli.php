<?php
require __DIR__ . '/../vendor/autoload.php';

use Martinshaw\StreetGroupInterviewTechTest\HomeownerNameCsvLoader;

$filePathToLoad = empty($argv[1]) ? __DIR__ . '/../data/examples.csv' : $argv[1];

$loader = new HomeownerNameCsvLoader();
$loader->load($filePathToLoad);

var_dump($loader->getSuccessfulNameRows());
var_dump($loader->getFailedNameRows());

exit(0);