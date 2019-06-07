<?php
return array(
    //db
    'servername' => '127.0.0.1',                                                // servername
    'usr_db' => 'root',                                                         // user name
    'passwd_db' => '',                                                          // password
    'dbname' => 'tabule',                                                       // dbname


    //rozvrh
    'rozvrh_url' => 'https://marvdf.bakalari.cz:444/bakaweb',                   // address for baklaře
    'username' => '',                                                       // user name
    'password' => '',                                              // password

    "hours" => array("7:15 - 8:00", "8:05 - 8:50", "9:00 - 9:45", "10:05 - 10:50", "11:00 - 11:45", "11:55 - 12:40", "12:45 - 13:30", "13:35 - 14:20", "14:20 - 15:10", "15:15 - 16:00", "16:05 - 16:50"),      //that will be showed on top of rozvrh
    'auto_restart' => false,                                                    // auto restart
    'sleep_s' => 10,                                                            // time to wait for new refresh of school schedule (min)

    'tridy' => "4ITE,4G,3ITE,3PGD,2IT,2G,1G,1IT",                               // class that will be showed ("4ITE,4G" separator is ",")

    //cantina
    'auto_refresh' => false,                                                    // auto restart
    'code' => "0595",                                                           // code for cantina

    //news
    'news_url' => "http://www.skolavdf.cz/rss.xml",                             // news url

    //logs
    'log' => true,                                                              // if true it will create logs in folder logs
    'delete_log' => 7,                                                          // after x (default 7) day will delete logs (last log will be x days old)
);
 