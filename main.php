<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/main.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Rozvrh</title>
</head>

<body>

    <header>
        <div class="header_left">
            <div class="logo"><img src="images/logo.png" alt="logo"></div>
            <div class="page_name"></div>
            <div id="cur_date">
                <?php
                    echo date("d.m. Y");
                ?>
            </div>
        </div><div class="header_right">
            <div id="clock"></div>
        </div>
    </header>
    <div id="rozvrh">
<?php
$fr = @fopen("rozvrh.txt", "r") or die("Rozvrh nelze načíst");

while (($line = fgets($fr)) !== false) {
    echo $line;
}
?>
</div><div id="cantine">
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
            if ((String)$jidlo->datum == date("Y-m-d")) {
                $jidelak[strval($jidlo->datum)][strval($jidlo->druh)] = strval($jidlo->nazev);
            }
        }

        echo "<div class=\"cantina_container\"><div class=\"cantina\">";
        foreach ($jidelak as $den) {
            $datum = array_search($den, $jidelak);
            echo "<div class=\"den\"><div class=\"datum\">" . $datum . "</div>";
            foreach ($den as $jidlo) {
                $key = array_search($jidlo, $den);
                if (substr($key, 0, 4) != "bal.") {
                    echo "<div class=\"jidlo\">" . $key . "</div> <div class=\"jidlo_popis\">" . $jidlo . "</div>";
                }
            }
            echo "</div><br>";
        }
        echo "</div></div>";
    }

    function news()
    {
        $configs = include('config.php');
        $url = $configs["news_url"];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);

        if ($status_code == 200) {
            $xml = new SimpleXMLElement($result);
            echo "<div class=\"news_container\"><div class=\"news\">";
            $x = 0;
            foreach ($xml->channel->item as $item) { 
                echo "<div class=\"new\">";
                echo "<div class=\"title\">".$item->title."</div>";
                echo "<div class=\"pubDate\">".substr((String)$item->pubDate, 0, 16)."</div>";
                echo "<div>";
                $x++;
                if($x == 10){
                    break;
                }
            }
            echo "</div></div>";
        }
    }
    function news2()
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
    ?>
</div>

<script src="jquery.js"></script>

<script>
var script = document.createElement('script');
script.src = 'jquery.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);
function getTime() {
        var time = new Date(); 
        var hour = time.getHours();
        var minute = time.getMinutes();
        var second = time.getSeconds(); 
        

        
        if(hour == 7 && minute < 45 ) {
        $( ".zero" ).addClass( "active" ); 
        }else{
            $(".zero").removeClass("active");
        }
          
        if(hour == 7 && minute >= 45 || hour == 8 && minute < 50 ) {
        $( ".one" ).addClass( "active" ); 
        }else{
            $(".one").removeClass("active");
        }
          
        if(hour == 8 && minute >= 50 || hour == 9 && minute < 45 ) {
        $( ".two" ).addClass( "active" ); 
        }else{
            $(".two").removeClass("active");
        }
          
        if(hour == 9 && minute >= 45 || hour == 10 && minute < 50 ) {
        $( ".three" ).addClass( "active" ); 
        }else{
            $(".three").removeClass("active");
        }
          
        if(hour == 10 && minute >= 50 || hour == 11 && minute < 45 ) {
        $( ".four" ).addClass( "active" ); 
        }else{
            $(".four").removeClass("active");
        }
          
        if(hour == 11 && minute >= 45 || hour == 12 && minute < 40 ) {
        $( ".five" ).addClass( "active" ); 
        }else{
            $(".five").removeClass("active");
        }
          
        if(hour == 12 && minute >= 40 || hour == 13 && minute < 30 ) {
        $( ".six" ).addClass( "active" ); 
        }else{
            $(".six").removeClass("active");
        }
          
        if(hour == 13 && minute >= 30 || hour == 14 && minute < 20 ) {
        $( ".seven" ).addClass( "active" );
        }else{
            $(".seven").removeClass("active");
        }
          
        if(hour == 14 && minute >= 20 || hour == 15 && minute < 15 ) {
        $( ".eight" ).addClass( "active" ); 
        }else{
            $(".eight").removeClass("active");
        }
          
        if(hour == 15 && minute >= 15 || hour == 16 && minute < 60 ) {
        $( ".nine" ).addClass( "active" ); 
        }else{
            $(".nine").removeClass("active");
        }
          
        if(hour == 17 && minute <= 45 ) {
        $( ".ten" ).addClass( "active" ); 
        }else{
            $(".ten").removeClass("active");
        }
          
        if(hour.toString().length == 1) {
             hour = '0'+hour;
        }
        if(minute.toString().length == 1) {
             minute = '0'+minute;
        }
        if(second.toString().length == 1) {
             second = '0'+second;
        }   
        var displayTime = hour+':'+minute+':'+second;   
         return displayTime;
        
    }
    setInterval(function(){
        currentTime = getTime();
        document.getElementById("clock").innerHTML = currentTime;
    }, 1000);  
    

</script>

</body>