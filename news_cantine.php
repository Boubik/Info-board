<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/news_cantine.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Info</title>
    <script src="https://cloud.tinymce.com/5/tinymce.min.js?apiKey=logspdcwzv4eydmuk9f8sxe6bjh4sfxny0uxkbr9btixvcxm"></script>
    <script>
        tinymce.init({
            selector: '#mytextarea'
        });
    </script>
    <script>
        //setTimeout(function(){document.location='rozvrh.php';}, 15000);
    </script>
</head>

<body>

    <header>
        <div class="header_left">
            <div class="logo"><img src="images/logo.png" alt="logo"></div>
            <div class="page_name">Jídelní lístek, Novinky</div>
            <div id="cur_date">
                <?php
                echo date("d.m. Y");
                ?>
            </div>
        </div>
        <div class="header_right">
            <div id="clock"></div>
        </div>
    </header>

    <?php
    include "functions.php";

    echo "<div class=\"container\">";
    date_default_timezone_set("Europe/Prague");
    cantina();
    news();
    echo "</div>";
