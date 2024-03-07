<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homeowner CSV Upload - Street Group</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form action="results.php" method="post" enctype="multipart/form-data">
        <h1>Homeowner CSV Upload</h1>
        <p>Please upload a CSV file containing homeowner data</p>
        <input type="file" name="file" id="file" accept=".csv">
        <button type="submit">Upload</button>
    </form>
</body>
</html>