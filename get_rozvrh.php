<?php

ini_set('max_execution_time', 0);
date_default_timezone_set("Europe/Prague");
$configs = include('config.php');
$login = $configs["username"];
$password = $configs["password"];
$url = $configs["rozvrh_url"];
$sleep = $configs["sleep_s"]*60;
$max_hodin = $configs["max_hodin"];
$auto_restart = $configs["auto_restart"];

$tr = FALSE;

$url .= "/if/2/timetable/actual/classes";
//echo $url . "<br>";

do{
    $rozvrh = "";
    if((date("w")) > 5 or (date("w")) == 0){
        $den = 0;
    }else{
        $den = (date("w")-1);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $result = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
    curl_close($ch);

    $result = to_xml($result);
    //echo $result;

    if ($status_code == 200) {
        $xml = new SimpleXMLElement($result);
        //echo $status_code . "<br>";

        $rozvrh .= "<table class=\"table\" style=\"width:100%\" border=\"1\">\n<tbody>\n<tr>";                    //tabble
        $i = 0;
        $rozvrh .= "<th>\nTřída</th>\n";
        while($i != $max_hodin+1){
            $rozvrh .= "<th class=\"hodina\">\n$i</th>\n";
            $i++;
        }
        $rozvrh .= "</tr>";

        //rozdělí na třídy
        foreach($xml->Timetable as $Timetable){
            $trida = $Timetable->Entity->Abbrev;
            if("V" != substr($trida, 0, 1) and "M" != substr($trida, 1, 1)){
                $rozvrh .= "<tr>\n";
                $tr = true;
                $rozvrh .= "<td class=\"trida\">\n".$trida."</td>\n";
                $i = 0;
                //vypsání jedné třídy
                foreach($Timetable->Cells->TimetableCell as $TimetableCell){
                    if($TimetableCell->DayIndex == $den){
                        while(($TimetableCell->HourIndex-3) != $i and $i < $max_hodin+1){
                            $rozvrh .= "<td class=\"prazdnej\">\n</td>\n";
                            $i++;
                        }
                        $i++;
                        if($TimetableCell->Atoms->TimetableAtom->Group->Abbrev == "celá"){
                            $rozvrh .= "<td>\n";
                            $rozvrh .= "<div class=\"cell\">\n";
                            $rozvrh .= "<div class=\"ucebna\">".$TimetableCell->Atoms->TimetableAtom->Room->Abbrev."</div\n<br>";
                            $rozvrh .= "<div class=\"predmet\">".$TimetableCell->Atoms->TimetableAtom->Subject->Abbrev."</div\n<br>";
                            $rozvrh .= "<div class=\"ucitel\">".$TimetableCell->Atoms->TimetableAtom->Teacher->Abbrev."</div\n<br>";
                            $rozvrh .= "</div>\n";
                            $rozvrh .= "</td>\n";
                        }else{
                            $rozvrh .= "<td>\n";
                            $rozvrh .= "<div class=\"cell\">\n";
                            $k = 0;
                            foreach($TimetableCell->Atoms->TimetableAtom as $TimetableAtom){
                                $rozvrh .= "<div class=\"incell$k\">\n";
                                $rozvrh .= "<div class=\"ucebna\">".$TimetableAtom->Room->Abbrev."</div\n<br>";
                                $rozvrh .= "<div class=\"skupina\">".$TimetableAtom->Group->Abbrev."</div\n<br>";
                                $rozvrh .= "<div class=\"predmet\">".$TimetableAtom->Subject->Abbrev."</div\n<br>";
                                $rozvrh .= "<div class=\"ucitel\">".$TimetableAtom->Teacher->Abbrev."</div\n<br>";
                                $rozvrh .= "</div>\n";
                                //$rozvrh .= "<div style=\"border-style: none;\"></div>";                                                             //"dělá" enter v tb
                                $k++;
                            }
                            $rozvrh .= "</div>\n";
                            $rozvrh .= "</td>\n";
                        }
                    }
                }
                //doplnění na konci
                while($i != $max_hodin+1){
                    $rozvrh .= "<td class=\"prazdnej\">\n</td>\n";
                    $i++;
                }
            }
            if($tr == TRUE){
                $rozvrh .= "</tr>\n";
                $tr = false;
            }
        }
        $rozvrh .= "</tbody>\n</table>";

        save_to_file($rozvrh, $den, $status_code);
    }

    if($auto_restart == TRUE){
        sleep($sleep);
    }
}while($auto_restart == TRUE);


function to_xml($result){
    //$lmao = explode("<BakalariDataInterface xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">", $result);
    $lmao = explode('<TargetType>Classes</TargetType>', $result);
    $lmao = explode('</BakalariDataInterface>', $lmao[1]);
    $result = "<?xml version='1.0' encoding='UTF-8'?>".$lmao[0];
    return $result;
}

function save_to_file($rozvrh, $den, $status_code){
    $fw = fopen("rozvrh.txt", "w");
    fwrite($fw, $rozvrh);
    fclose($fw);
    echo date("H:i")." day: ".($den+1)." status code: $status_code\n\n";
}