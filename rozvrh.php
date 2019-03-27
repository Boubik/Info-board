<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/rozvrh.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Rozvrh</title>
    <script>//setTimeout(function(){document.location='news_cantine.php';}, 30000);</script>
</head>

<body>
    <header>
        <div class="header_left">
            <div class="logo"><img src="images/logo.png" alt="logo"></div>
            <div class="school_name">Vyšší odborná škola, Střední průmyslová škola a Střední odborná škola služeb a cestovního ruchu, Varnsdorf, Bratislavská 2166, příspěvková organizace</div>
        </div><div class="header_right">
            <div id="cur_date">
                <?php
                    echo date("d.m. Y");
                ?>
            </div>

        </div>
    </header>

<?php
$fr = @fopen("rozvrh.txt", "r") or die("Rozvrh nelze načíst");

while (($line = fgets($fr)) !== false) {
    echo $line;
}
?>

</body>