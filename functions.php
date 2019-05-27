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
            $date = substr($filename, 12, 10);
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
