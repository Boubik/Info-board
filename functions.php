<?php

/**
 * save text to .log and delete old
 * @param   String  $folder     what folder in logs
 * @param   String  $log_text   text that will be putet to log
 * @param   Int     $delete_log last log will be x days old (null will dostn delete anything)
 */
function save_to_log($log_text, $delete_log = null)
{
    $date = date("Y-m-d");
    $fa = fopen("logs/" . $date . ".log", "a");
    fwrite($fa, $log_text . "\n");
    fclose($fa);

    if ($delete_log != null) {
        $fileList = glob('logs/*.log');
        foreach ($fileList as $filename) {
            $date = substr($filename, 5, 10);
            if (strtotime($date) < strtotime('-' . ($delete_log + 1) . ' days')) {
                unlink($filename);
            }
        }
    }
}

/**
 * will save file in root folder of program
 * @param   String  $filename   filen name with end (.txt, etc)
 * @param   String  $text       text thaw will be write to file
 * @param   String  $mode       mode (w, a, etc)
 */
function save_to_file($filename, $text, $mode = "w")
{
    $fx = fopen($filename, $mode);
    fwrite($fx, $text);
    fclose($fx);
}

/**
 * will load file in root folder of program
 * @param   String  $filename   filen name with end (.txt, etc)
 * @param   String  $mode       mode (w, a, etc)
 * @return  String  text in file
 */
function load_file($filename, $mode = "r")
{

    $handle = fopen($filename, $mode);
    $text = "";
    while (($line = fgets($handle)) !== false) {
        $text = $text.$line;
    }
    return $text;
}



function to_xml($result)
{
    $lmao = explode('<TargetType>Classes</TargetType>', $result);
    $lmao = explode('</BakalariDataInterface>', $lmao[1]);
    $result = "<?xml version='1.0' encoding='UTF-8'?>" . $lmao[0];
    return $result;
}


function full_rozvrh(){
    ini_set('max_execution_time', 0);
    date_default_timezone_set("Europe/Prague");
    $configs = include('config.php');
    $login = $configs["username"];
    $password = $configs["password"];
    $url = $configs["rozvrh_url"];
    $log = $configs["log"];
    $delete_log = $configs["delete_log"];
    $stay = array();

    if ((date("w")) > 5 or (date("w")) == 0) {
        $den = 0;
    } else {
        $den = (date("w") - 1);
    }

    if (isset($configs["tridy"]) and $configs["tridy"] != "") {
        $stay = $configs["tridy"];
        $stay = explode(",", $stay);
    } else {
        $stay = "all";
    }

    $url .= "/if/2/timetable/permanent/classes";

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

    //echo $status_code . "<br>\n";

    if ($status_code == 200) {
        $result = to_xml($result);
        $xml = new SimpleXMLElement($result);
        save_to_file("new.xml", $result);

        $rozvrh = array();
        foreach ($xml->Timetable as $Timetable) {
            $trida = $Timetable->Entity->Abbrev;
            if ((@in_array($trida, $stay)) or $stay == "all") {
                foreach ($Timetable->Cells as $Cells) {
                    foreach ($Cells->TimetableCell as $TimetableCell) {
                        if ($TimetableCell->DayIndex == $den) {
                            foreach($TimetableCell->Atoms->TimetableAtom as $TimetableAtom){
                                $rozvrh[(String)$trida][(int)$TimetableCell->HourIndex]["Group"] = $TimetableAtom->Group->Abbrev;
                                $rozvrh[(String)$trida][(int)$TimetableCell->HourIndex]["Room"] = $TimetableAtom->Room->Abbrev;
                                $rozvrh[(String)$trida][(int)$TimetableCell->HourIndex]["Subject"] = $TimetableAtom->Subject->Abbrev;
                                $rozvrh[(String)$trida][(int)$TimetableCell->HourIndex]["Teacher"] = $TimetableAtom->Teacher->Abbrev;
                            }
                        }
                    }
                }
            }
        }
    }
    return $rozvrh;
}