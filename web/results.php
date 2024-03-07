<?php
require __DIR__ . '/../vendor/autoload.php';

use Martinshaw\StreetGroupInterviewTechTest\Exceptions\FileNotFoundException;
use Martinshaw\StreetGroupInterviewTechTest\Exceptions\PathOutsideOfPermittedRootException;
use Martinshaw\StreetGroupInterviewTechTest\HomeownerNameCsvLoader;

/**
 * @var false | string
 */
$errorMessage = false;

if (empty($_FILES["file"]["tmp_name"])) {
    $errorMessage = "No file was uploaded. Please return to <a href='index.php'>the upload page</a> and upload a valid CSV file.";
}

$loader = new HomeownerNameCsvLoader();
try {
    if ($errorMessage === false) $loader->load($_FILES["file"]["tmp_name"]);
} catch (PathOutsideOfPermittedRootException $e) {
    $errorMessage = $e->getMessage();
} catch (FileNotFoundException $e) {
    $errorMessage = $e->getMessage();
} catch (Exception $e) {
    $errorMessage = "An unexpected error has occurred: " . $e->getMessage();
}

$successfulNameRows = $errorMessage === false ? $loader->getSuccessfulNameRows() : [];
$failedNameRows = $errorMessage === false ? $loader->getFailedNameRows() : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result - Homeowner CSV Upload - Street Group</title>
</head>

<body>
    <?php
    if ($errorMessage !== false) {
        echo "<p class='error-message'>$errorMessage</p>";
    }
    ?>

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
            <?php foreach ($successfulNameRows as $row) { ?>
                <tr>
                    <td><?php echo $row->getTitle(); ?></td>
                    <td><?php echo $row->getInitial(); ?></td>
                    <td><?php echo $row->getFirstName(); ?></td>
                    <td><?php echo $row->getLastName(); ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <br />

    <?php if (!empty($failedNameRows)) { ?>
        <p class="error-message">The following rows failed to parse successfully:
        <ul>
            <?php foreach ($failedNameRows as $row) { ?>
                <li>Row <?php echo $row; ?></li>
            <?php } ?>
        </ul>
        </p>
    <?php } ?>

    <br />

    <a href="index.php">&larr; Return to the upload page </a>
</body>

</html>