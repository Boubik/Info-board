<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/rozvrh.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Rozvrh</title>
    <script>
    //setTimeout(function(){document.location='news_cantine.php';}, 30000);
    setTimeout(function(){document.location='rozvrh.php';}, 600000);
    </script>
</head>

<body>
    <header>
        <div class="header_left">
            <div class="logo"><img src="images/logo.png" alt="logo"></div>
            <div class="page_name">Rozvrh</div>
        </div><div class="header_right">
        <div id="cur_date">
                <?php
                    echo date("d.m. Y");
                ?>
            </div>
            <div id="clock"></div>
        </div>
    </header>

<?php
$fr = @fopen("rozvrh.txt", "r") or die("Rozvrh nelze načíst");

while (($line = fgets($fr)) !== false) {
    echo $line;
}
?>

<script>
function getDateTime() {
        var time = new Date(); 
        var hour = time.getHours();
        var minute = time.getMinutes();
        var second = time.getSeconds(); 
          
        if(hour.toString().length == 1) {
             hour = '0'+hour;
        }
        if(minute.toString().length == 1) {
             minute = '0'+minute;
        }
        if(second.toString().length == 1) {
             second = '0'+second;
        }   
        var displayTime = hour+':'+minute+':'+second;   
         return displayTime;
    }
    setInterval(function(){
        currentTime = getDateTime();
        document.getElementById("clock").innerHTML = currentTime;
    }, 1000);
</script>

</body>