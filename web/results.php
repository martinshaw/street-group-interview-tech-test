<?php
require __DIR__ . '/../vendor/autoload.php';

use Martinshaw\StreetGroupInterviewTechTest\HomeownerNameCsvLoader;

$loader = new HomeownerNameCsvLoader();

$loader->load(__DIR__ . '/../data/examples-4-.csv');

$successfulNameRows = $loader->getSuccessfulNameRows();
$failedNameRows = $loader->getFailedNameRows();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result - Homeowner CSV Upload - Street Group</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Initials</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($successfulNameRows as $row): ?>
                <tr>
                    <td><?= $row->getTitle() ?></td>
                    <td><?= $row->getInitial() ?></td>
                    <td><?= $row->getFirstName() ?></td>
                    <td><?= $row->getLastName() ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>