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
        </div><div class="header_right">
            <div id="clock"></div>
        </div>
    </header>

    <?php

    echo "<div class=\"container\">";
    date_default_timezone_set("Europe/Prague");
    cantina();
    news();
    echo "</div>";


    function cantina()
    {
        $xml = @simplexml_load_file("jidelnicek.xml");
        if (!$xml) {
            echo "Nejsou data z jidelnicek.xml";
        }
        $jidelak = array();
        foreach ($xml->jidlo as $jidlo) {
            if((String)$jidlo->datum == date("Y-m-d")){
                $jidelak[strval($jidlo->datum)][strval($jidlo->druh)] = strval($jidlo->nazev);
            }
        }

        echo "<div class=\"cantina_container\"><div class=\"cantina\">";
        foreach($jidelak as $den){
            $datum = array_search ($den, $jidelak);
            echo "<div class=\"den\"><div class=\"datum\">".$datum."</div>";
            foreach($den as $jidlo){
                $key = array_search ($jidlo, $den);
                if(substr($key,0,4) != "bal."){
                    echo "<div class=\"jidlo\">".$key."</div> <div class=\"jidlo_popis\">".$jidlo."</div>";
                }
            }
            echo "</div><br>";
        }
        echo "</div></div>";
    }

    function news()
    {

        echo "<div class=\"news_container\"><div class=\"news\">";
        $fileList = glob('aktuality/*.md');
        foreach ($fileList as $file) {
            $handle = fopen($file, "r");
            $delete = true;
            $line = fgets($handle);
            $date = explode("-", $line);
            $year = $date[0];
            $mounth = $date[1];
            $day = $date[2];
            if ($year > date('Y', time())) {
                $delete = false;
            } else {
                if ($year = date('Y', time())) {
                    if ($mounth > date('m', time())) {
                        $delete = false;
                    }
                    if ($mounth = date('m', time())) {
                        if ($day >= date('d', time())) {
                            $delete = false;
                        }
                    }
                }
            }

            if ($delete == true) {
                unlink($file);
            } else {
                echo "<h1>" . substr(str_replace(".md", "", $file), 10) . "</h1>";
                while (($line = fgets($handle)) !== false) {
                    $i = 0;
                    str_replace('**', "<b>", $line, $count);
                    while ($i != $count) {
                        $line = preg_replace('/\*\*/', "<b>", $line, 1);
                        $line = preg_replace('/\*\*/', "</b>", $line, 1);
                        $i += 2;
                    }
                    echo $line . "<br>";
                }
            }
            fclose($handle);
        }
        echo "</div></div>";
    }
    