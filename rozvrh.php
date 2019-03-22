<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/rozvrh.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Rozvrh</title>
    <script>//setTimeout(function(){document.location='news_cantine.php';}, 15000);</script>
</head>

<body>

<?php
$fr = fopen("rozvrh.txt", "r");

while (($line = fgets($fr)) !== false) {
    echo $line;
}
?>

</body>