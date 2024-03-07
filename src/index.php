<?php
require __DIR__ . '/../vendor/autoload.php';

use Martinshaw\StreetGroupInterviewTechTest\HomeownerName;
use Martinshaw\StreetGroupInterviewTechTest\HomeownerNameCsvLoader;

$loader = new HomeownerNameCsvLoader();

$loader->load(__DIR__ . '/../data/examples-4-.csv');

var_dump($loader->getSuccessfulNameRows());
var_dump($loader->getFailedNameRows());

exit;