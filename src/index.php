<?php
require __DIR__ . '/../vendor/autoload.php';

use Martinshaw\StreetGroupInterviewTechTest\HomeownerName;
use Martinshaw\StreetGroupInterviewTechTest\HomeownerNameCsvLoader;

$loader = new HomeownerNameCsvLoader();

var_dump($loader->load(__DIR__ . '/../data/examples-4-.csv'));

exit;