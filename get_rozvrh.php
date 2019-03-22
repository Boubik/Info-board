<?php

ini_set('max_execution_time', 0);
$configs = include('config.php');
$login = $configs["username"];
$password = $configs["password"];
$url = $configs["servername"];
$sleep = $configs["sleep"];
$max_hodin = $configs["max_hodin"];

$tr = FALSE;
$rozvrh = "";

$url .= "/if/2/timetable/actual/classes";
//echo $url . "<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
$result = curl_exec($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code

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
        if("VS" != substr($trida, 0, 2) and "VC" != substr($trida, 0, 2) and "VB" != substr($trida, 0, 2) and "VT" != substr($trida, 0, 2) and "M" != substr($trida, 1, 1)){
            $rozvrh .= "<tr>\n";
            $tr = true;
            $rozvrh .= "<td class=\"trida\">\n".$trida."</td>\n";
            $i = 0;
            //vypsání jedné třídy
            foreach($Timetable->Cells->TimetableCell as $TimetableCell){
                if($TimetableCell->DayIndex == (date("w")-1)){
                    while(($TimetableCell->HourIndex-3) != $i and $i < $max_hodin+1){
                        $rozvrh .= "<td class=\"prazdnej\">\n</td>\n";
                        $i++;
                    }
                    $i++;
                    if($TimetableCell->Atoms->TimetableAtom->Group->Abbrev == "celá"){
                        $rozvrh .= "<td>\n";
                        $rozvrh .= "<div class=\"predmet\">".$TimetableCell->Atoms->TimetableAtom->Subject->Abbrev."</div\n<br>";
                        $rozvrh .= "<div class=\"ucitel\">".$TimetableCell->Atoms->TimetableAtom->Teacher->Abbrev."</div\n<br>";
                        $rozvrh .= "</td>\n";
                    }else{
                        $rozvrh .= "<td>\n";
                        foreach($TimetableCell->Atoms->TimetableAtom as $TimetableAtom){
                            $rozvrh .= "<div class=\"skupina\">".$TimetableAtom->Group->Abbrev."</div\n<br>";
                            $rozvrh .= "<div class=\"predmet\">".$TimetableAtom->Subject->Abbrev."</div\n<br>";
                            $rozvrh .= "<div class=\"ucitel\">".$TimetableAtom->Teacher->Abbrev."</div\n<br>";
                            $rozvrh .= "<div style=\"border-style: none;\"></div>";                                                             //"dělá" enter v tb
                        }
                        $rozvrh .= "</td>\n";
                    }
                }
            }
            //doplnění na konci
            while($i != $max_hodin+1){
                $rozvrh .= "<td>\n</td>\n";
                $i++;
            }
        }
        if($tr == TRUE){
            $rozvrh .= "</tr>\n";
            $tr = false;
        }
    }
    $rozvrh .= "</tbody>\n</table>";

    save_to_file($rozvrh);
}


curl_close($ch);

//sleep($sleep);


function to_xml($result){
    //$lmao = explode("<BakalariDataInterface xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\">", $result);
    $lmao = explode('<TargetType>Classes</TargetType>', $result);
    $lmao = explode('</BakalariDataInterface>', $lmao[1]);
    $result = "<?xml version='1.0' encoding='UTF-8'?>".$lmao[0];
    return $result;
}

function save_to_file($rozvrh){
$fw = fopen("rozvrh.txt", "w");
fwrite($fw, $rozvrh);
fclose($fw);
}