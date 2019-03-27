<?php

ini_set('max_execution_time', 0);
date_default_timezone_set("Europe/Prague");
$configs = include('config.php');
$login = $configs["username"];
$password = $configs["password"];
$url = $configs["rozvrh_url"];
$sleep = $configs["sleep_s"]*60;
$max_hodin = 0;
$auto_restart = $configs["auto_restart"];

$tr = FALSE;

$th_width = 8.333333333;
$hodina_str = array("7:00 - 7:45", "8:05 - 8:50", "9:00 - 9:45", "10:05 - 10:50", "11:00 - 11:45", "11:55 - 12:40", "12:45 - 13:30", "13:35 - 14:20", "14:20 - 15:10", "15:15 - 16:00", "16:05 - 16:50");

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

    //echo $result;

    if ($status_code == 200) {
        $result = to_xml($result);
        $xml = new SimpleXMLElement($result);
        //echo $status_code . "<br>";


        //rozdělí na třídy
        foreach($xml->Timetable as $Timetable){
            $trida = $Timetable->Entity->Abbrev;
            if("V" != substr($trida, 0, 1) and "M" != substr($trida, 1, 1)){
                foreach($Timetable->Cells->TimetableCell as $TimetableCell){
                    if($TimetableCell->DayIndex == $den){
                        if(($TimetableCell->HourIndex-2) > $max_hodin){
                            $max_hodin = $TimetableCell->HourIndex-2;
                        }
                    }
                }
            }
        }

        $rozvrh = "<table class=\"table\" style=\"width:100%\" border=\"1\">\n<tbody>\n<tr>";
        $th_width = 100/($max_hodin+2);
        $i = 0;
        $rozvrh .= "<th style=\"width:$th_width%\";>\nTřída</th>\n";
        while($i != $max_hodin+1){
            $rozvrh .= "<th class=\"hodina\" style=\"width:$th_width%\";>\n$i<div class=\"hodina_cislo\">".$hodina_str[$i]."</div></th>\n";
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
                        while(($TimetableCell->HourIndex-2) != $i and $i < $max_hodin+1){
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
                            if(@count($TimetableCell->Atoms->children()) != 1){
                                foreach($TimetableCell->Atoms->TimetableAtom as $TimetableAtom){
                                    $rozvrh .= "<div class=\"incell$k\">\n";
                                    $rozvrh .= "<div class=\"ucebna\">".$TimetableAtom->Room->Abbrev."</div\n<br>";
                                    $rozvrh .= "<div class=\"skupina\">".$TimetableAtom->Group->Abbrev."</div\n<br>";
                                    $rozvrh .= "<div class=\"predmet\">".$TimetableAtom->Subject->Abbrev."</div\n<br>";
                                    $rozvrh .= "<div class=\"ucitel\">".$TimetableAtom->Teacher->Abbrev."</div\n<br>";
                                    $rozvrh .= "</div>\n";
                                    $k++;
                                }
                                $rozvrh .= "</div>\n";
                                $rozvrh .= "</td>\n";
                            }else{
                                $rozvrh .= "<div class=\"ucebna\">".$TimetableCell->Atoms->TimetableAtom->Room->Abbrev."</div\n<br>";
                                $rozvrh .= "<div class=\"skupina\">".$TimetableCell->Atoms->TimetableAtom->Group->Abbrev."</div\n<br>";
                                $rozvrh .= "<div class=\"predmet\">".$TimetableCell->Atoms->TimetableAtom->Subject->Abbrev."</div\n<br>";
                                $rozvrh .= "<div class=\"ucitel\">".$TimetableCell->Atoms->TimetableAtom->Teacher->Abbrev."</div\n<br>";
                                $rozvrh .= "</div>\n";
                                $rozvrh .= "</td>\n";
                            }
                        }
                    }
                }
            }
            //doplnění na konci
            while($i != $max_hodin+1){
                $rozvrh .= "<td class=\"prazdnej\">\n</td>\n";
                $i++;
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
    $lmao = explode('<TargetType>Classes</TargetType>', $result);
    $lmao = explode('</BakalariDataInterface>', $lmao[1]);
    $result = "<?xml version='1.0' encoding='UTF-8'?>".$lmao[0];
    return $result;
}

function save_to_file($rozvrh, $den, $status_code){
    $fw = fopen("rozvrh.txt", "w");
    fwrite($fw, $rozvrh);
    fclose($fw);
    echo date("H:i")." Day:".($den+1)." Status code:$status_code \n\n";
}