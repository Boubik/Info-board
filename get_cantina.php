<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
</head>

<body>
    <?php
    $configs = include('config.php');
    include "functions.php";
    $auto_refresh = $configs["auto_refresh"];
    $delete_log = $configs["delete_log"];
    $log = $configs["log"];
    $sleep = 2 * 12 * 60 * 60;

    do {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $address = gethostbyname('www.strava.cz');
        $port = getservbyname('www', 'tcp');
        $result = socket_connect($socket, $address, $port);
        $buffer = "GET /foxisapi/foxisapi.dll/istravne.istravne.process?xmljidelnicky&zarizeni=0595 HTTP/1.1\r\nHost: www.strava.cz\r\nConnection: Close\r\n\r\n";
        socket_write($socket, $buffer);
        $data = '';
        while ($out = socket_read($socket, 2048, PHP_BINARY_READ)) {
            $data .= $out;
        }
        socket_close($socket);
        if (!$data) {
            echo "Nejsou data ze strava.cz";

            echo date("H:i") . " Status code: Fail" . "\n\n";
        
            if($log){
                $log_text_rozvrh = "\n" . date("H:i") . " Status code: Fail" . "\n";
                save_to_log($log_text_rozvrh, $delete_log);
            }

        }else{
            $data = iconv('cp1250', 'utf-8', $data);
            $data = strip_tags($data, '<pomjidelnic_xmljidelnic><datum><druh><nazev><popis>');
            $data = str_replace('pomjidelnic_xmljidelnic', 'jidlo', preg_replace('~\s+~', ' ', $data));
            $data = str_replace('> <', '><', $data);
            $data = "<jidelnicek>" . trim($data) . "</jidelnicek>";

            save_to_file("jidelnicek.xml", $data);

            echo date("H:i") . " Status code: OK" . "\n\n";
        
            if($log){
                $log_text_rozvrh = date("H:i") . " Status code: OK";
                save_to_log($log_text_rozvrh, $delete_log);
            }

        }
        if ($auto_refresh == true) {
            sleep($sleep);
        }
    } while ($auto_refresh == true);
