<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html" ; charset="UTF-8">
    <link href="styles/index.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Search Engine</title>
</head>

<body>

<?php

$fileList = glob('aktuality/*.md');
foreach($fileList as $file){
    $handle = fopen($file, "r");
    $delete = TRUE;
    echo "<h1>".substr(str_replace(".md", "", $file), 10)."</h1><br>";
    $line = fgets($handle);
    $date = explode("-", $line);
    $year = $date[0];
    $mounth = $date[1];
    $day = $date[2];
    $date = date('Y-m-d', time());
    if($year >= date('Y', time())){
        if($mounth >= date('m', time())){
            if($day >= date('d', time())){
                $delete = FALSE;
            }
        }
    }
    if($delete == TRUE){
        unlink($file);
    }else{
        while(($line = fgets($handle)) !== false){
            $i = 0;
            str_replace('**', "<b>", $line, $count);
            while($i != $count){
                $line = preg_replace('/\*\*/', "<b>", $line, 1);
                $line = preg_replace('/\*\*/', "</b>", $line, 1);
                $i+=2;
            }
            echo $line."<br>";
        }
    }
    fclose($handle);
}

?>

</body>