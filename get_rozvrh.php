<?php

ini_set('max_execution_time', 0);
date_default_timezone_set("Europe/Prague");
$configs = include('config.php');
include "functions.php";
$login = $configs["username"];
$password = $configs["password"];
$url = $configs["rozvrh_url"];
$sleep = $configs["sleep_s"] * 60;
$auto_restart = $configs["auto_restart"];
$log = $configs["log"];
$delete_log = $configs["delete_log"];
$counting_start = $configs["counting_start"];

$tr = false;
$th_width = 8.333333333;
$hodina_str = $configs["hours"];
$str_cislo = array("zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "eighteen", "nineteen");
$stay = array();
$stay2 = array();

$url .= "/if/2/timetable/actual/classes";

do {
    $rozvrh = "";
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

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $result = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
    curl_close($ch);

    if ($status_code == 200) {
        $result = to_xml($result);
        $xml = new SimpleXMLElement($result);

        //rozdělí na třídy
        $max_hodin = 0;
        foreach ($xml->Timetable as $Timetable) {
            $trida = $Timetable->Entity->Abbrev;
            if ((@in_array($trida, $stay)) or $stay == "all") {
                if (!(isset($Timetable->Cells->TimetableCell->HourIndex)) and (!($Timetable->Cells->TimetableCell->DayIndex == $den) or !(isset($Timetable->Cells->TimetableCell->DayIndex)))) { } else {
                    foreach ($Timetable->Cells->TimetableCell as $TimetableCell) {
                        if ($TimetableCell->DayIndex == $den) {
                            if (($TimetableCell->HourIndex - (2 + $counting_start)) > $max_hodin) {
                                $max_hodin = $TimetableCell->HourIndex - (2 + $counting_start);
                            }
                            $stay2[] = $trida;
                        }
                    }
                }
            }
        }
        $stay = $stay2;

        if ($max_hodin == 0) {
            echo date("H:i") . " get_rozvrh" . " Day:" . ($den + 1) . " Max hodin:" . $max_hodin . " Status code:" . $status_code . " něco je špatně načetlo se " . $max_hodin . " hodin\n\n";
            $rozvrh = "<div class=\"error_center_rozvrh\">něco je špatně načetlo se " . $max_hodin . " hodin</div>";
            $rozvrh = date("Y-m-d") . "\n" . $rozvrh;
            save_to_file("rozvrh.txt", $rozvrh);

            if ($log) {
                $log_text_rozvrh = date("H:i") . " get_rozvrh" . " Day:" . ($den + 1) . " Max hodin:" . $max_hodin . " Status code:" . $status_code . " něco je špatně načetlo se " . $max_hodin . " hoďin\n\n";
                save_to_log($log_text_rozvrh, $delete_log);
            }
            break;
        } else {
            $rozvrh = "<table class=\"table\" style=\"width:100%\"\">\n<tbody>\n<tr>";
            $th_width = (100 / ($max_hodin + 2)) + (1.5 / ($max_hodin + 2));
            $i = 0;
            if (date('W') % 2 == 0) {
                $tiden = "Sudý";
            } else {
                $tiden = "Lichý";
            }
            $rozvrh .= "<th class=\"trida_main\"style=\"width:" . ($th_width / 1.5) . "%\";>\n" . $tiden . "</th>\n";
            while ($i != $max_hodin + 1) {
                $rozvrh .= "<th class=\"hodina " . $str_cislo[$i] . "\" style=\"width:$th_width%\";>\n" . ($i + $counting_start) . "<div class=\"hodina_cislo\">" . $hodina_str[$i] . "</div></th>\n";
                $i++;
            }
            $rozvrh .= "</tr>";


            //rozdělí na třídy
            foreach ($xml->Timetable as $Timetable) {
                $trida = $Timetable->Entity->Abbrev;
                if ((@in_array($trida, $stay)) or $stay == "all") {
                    $rozvrh .= "<tr>\n";
                    $tr = true;
                    $rozvrh .= "<td class=\"trida\">\n" . $trida . "</td>\n";
                    $i = 0;
                    //vypsání jedné třídy
                    foreach ($Timetable->Cells->TimetableCell as $TimetableCell) {
                        if ($TimetableCell->DayIndex == $den) {
                            while (($TimetableCell->HourIndex - (2 + $counting_start)) != $i and $i < $max_hodin + 1) {
                                $rozvrh .= "<td class=\"prazdnej " . $str_cislo[$i] . "\">\n</td>\n";
                                $i++;
                            }
                            $i++;
                            if ($TimetableCell->Atoms->TimetableAtom->Group->Abbrev == "celá") {
                                $rozvrh .= "<td>\n";
                                $rozvrh .= "<div class=\"cell " . $str_cislo[$i - 1] . "\">\n";
                                $rozvrh .= "<div class=\"ucebna\">" . $TimetableCell->Atoms->TimetableAtom->Room->Abbrev . "</div\n<br>";
                                $rozvrh .= "<div class=\"predmet\">" . $TimetableCell->Atoms->TimetableAtom->Subject->Abbrev . "</div\n<br>";
                                $rozvrh .= "<div class=\"ucitel\">" . $TimetableCell->Atoms->TimetableAtom->Teacher->Abbrev . "</div\n<br>";
                                $rozvrh .= "</div>\n";
                                $rozvrh .= "</td>\n";
                            } else {
                                $rozvrh .= "<td>\n";
                                $rozvrh .= "<div class=\"cell " . $str_cislo[$i - 1] . "\">\n";
                                $k = 0;
                                if (@count($TimetableCell->Atoms->children()) != 1) {
                                    $cell = 0;
                                    foreach ($TimetableCell->Atoms->TimetableAtom as $TimetableAtom) {
                                        if ($cell == 2) {
                                            break;
                                        }
                                        $rozvrh .= "<div class=\"incell$k\">\n";
                                        $rozvrh .= "<div class=\"ucebna\">" . $TimetableAtom->Room->Abbrev . "</div\n<br>";
                                        $rozvrh .= "<div class=\"skupina\">" . $TimetableAtom->Group->Abbrev . "</div\n<br>";
                                        $rozvrh .= "<div class=\"predmet\">" . $TimetableAtom->Subject->Abbrev . "</div\n<br>";
                                        $rozvrh .= "<div class=\"ucitel\">" . $TimetableAtom->Teacher->Abbrev . "</div\n<br>";
                                        $rozvrh .= "</div>\n";
                                        $k++;
                                        $cell++;
                                    }
                                    $rozvrh .= "</div>\n";
                                    $rozvrh .= "</td>\n";
                                } else {
                                    $rozvrh .= "<div class=\"ucebna\">" . $TimetableCell->Atoms->TimetableAtom->Room->Abbrev . "</div\n<br>";
                                    $rozvrh .= "<div class=\"skupina\">" . $TimetableCell->Atoms->TimetableAtom->Group->Abbrev . "</div\n<br>";
                                    $rozvrh .= "<div class=\"predmet\">" . $TimetableCell->Atoms->TimetableAtom->Subject->Abbrev . "</div\n<br>";
                                    $rozvrh .= "<div class=\"ucitel\">" . $TimetableCell->Atoms->TimetableAtom->Teacher->Abbrev . "</div\n<br>";
                                    $rozvrh .= "</div>\n";
                                    $rozvrh .= "</td>\n";
                                }
                            }
                        }
                    }
                }
                //doplnění na konci
                while ($i != $max_hodin + 1) {
                    $rozvrh .= "<td class=\"prazdnej " . $str_cislo[$i] . "\">\n</td>\n";
                    $i++;
                }

                if ($tr == true) {
                    $rozvrh .= "</tr>\n";
                    $tr = false;
                }
            }
            $rozvrh .= "</tbody>\n</table>";

            $rozvrh = date("Y-m-d") . "\n" . $rozvrh;
            save_to_file("rozvrh.txt", $rozvrh);

            echo date("H:i") . " get_rozvrh" . " Day:" . ($den + 1) . " Max hodin:" . $max_hodin . " Status code:" . $status_code . "\n\n";

            if ($log) {
                $log_text_rozvrh = date("H:i") . " get_rozvrh" . " Day:" . ($den + 1) . " Max hodin:" . $max_hodin . " Status code:" . $status_code;
                save_to_log($log_text_rozvrh, $delete_log);
            }
        }
    } else {
        echo date("H:i") . " get_rozvrh" . " Day:" . ($den + 1) . " Status code:" . $status_code . "\n\n";

        if ($log) {
            $log_text_rozvrh = "\n" . date("H:i") . " get_rozvrh" . " Day:" . ($den + 1) . " Status code:" . $status_code . "\n";
            save_to_log($log_text_rozvrh, $delete_log);
        }
    }

    if ($auto_restart == true) {
        sleep($sleep);
    }
} while ($auto_restart == true);
