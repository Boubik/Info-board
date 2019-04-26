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

    'auto_restart' => false,                                                    // auto restart
    'sleep_s' => 10,                                                            // time to wait for new refresh of school schedule (min)

    'tridy' => "4ITE,4G,3ITE,3PGD,2IT,2G,1G,1IT",                               // class that will be showed ("4ITE,4G" separator is ",")

    //cantina
    'auto_refresh' => false,                                                    // auto restart

    //logs
    'log' => true,                                                              // if true it will create logs in folder logs
    'delete_log' => 7,                                                          // after x (default 7) day will delete logs (last log will be x days old)
);
 