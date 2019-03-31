<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <?php

    ini_set('max_execution_time', 0);
    header('Content-Type: text/html; charset=utf-8');
    date_default_timezone_set("Europe/Prague");
    $configs = include('config.php');
    $url = $configs["cantina_url"];
    $refresh = $configs["auto_refresh"];
    $wait = false;


    $dnes = date("w");
    if ($wait == false) {
        $result = get_cantina($url);
        if ($result != "") {
            /*$doc = new DOMDocument('1.0', 'utf-8');
        @$doc->loadHTML($result);
        $jidelnicek = $doc->getElementById("jidelnicky_jidelnicek_obalka");*/
            //preg_match('/<div id=\"jidelnicky_jidelnicek_obalka\">(.*?)<\/div>/s', $result, $jidelnicek);
            //echo $jidelnicek[0];
            echo $result;
            echo "lmao";
            $wait == true;
        }
    }
    if ($refresh == true) {
        if ($dnes > 1 or $dnes == 0) {
            //fail to load
            if ($wait == false) {
                //sleep(3600);
                $dnes = 12;
            }
            //neděle
            if ($dnes == 0) {
                echo 3600;
                //sleep(3600);
                $wait = false;
            }
            //uterý
            if ($dnes == 2) {
                echo 432000;
                //sleep(432000);
            }
            //středa
            if ($dnes == 3) {
                echo 345600;
                //sleep(345600);
            }
            //čtvrtek
            if ($dnes == 4) {
                echo 259200;
                //sleep(259200);
            }
            //pátek
            if ($dnes == 5) {
                echo 172800;
                //sleep(172800);
            }
            //sobota
            if ($dnes == 6) {
                echo 86400;
                //sleep(86400);
            }
        }
    }

    function get_cantina($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close($ch);
        if ($status_code != 200) {
            $result = "";
        }

        return $result;
    }

    function save_to_file($rozvrh, $den)
    {
        $fw = fopen("rozvrh.txt", "w");
        fwrite($fw, $rozvrh);
        fclose($fw);
        echo date("H:i") . " day: " . ($den + 1) . "\n\n";
    }
    