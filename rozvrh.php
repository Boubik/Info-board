<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/rozvrh.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Rozvrh</title>
    <script>
    //setTimeout(function(){document.location='news_cantine.php';}, 30000);
    setTimeout(function(){document.location='rozvrh.php';}, 300000);
    </script>
</head>

<body>
    <header>
        <div class="header_left">
            <div class="logo"><img src="images/logo.png" alt="logo"></div>
            <div class="page_name">Rozvrh</div>
            <div id="cur_date">
                <?php
                    echo date("d.m. Y");
                ?>
            </div>
        </div><div class="header_right">
            <div id="clock"></div>
        </div>
    </header>

<?php
$fr = @fopen("rozvrh.txt", "r") or die("Rozvrh nelze načíst");

while (($line = fgets($fr)) !== false) {
    echo $line;
}
?>

<script src="jquery.js"></script>

<script>
var script = document.createElement('script');
script.src = 'jquery.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);
function getTime() {
        var time = new Date(); 
        var hour = time.getHours();
        var minute = time.getMinutes();
        var second = time.getSeconds(); 
        

        
        if(hour == 7 && minute < 45 ) {
        $( ".zero" ).addClass( "active" ); 
        }else{
            $(".zero").removeClass("active");
        }
          
        if(hour == 7 && minute >= 45 || hour == 8 && minute < 50 ) {
        $( ".one" ).addClass( "active" ); 
        }else{
            $(".one").removeClass("active");
        }
          
        if(hour == 8 && minute >= 50 || hour == 9 && minute < 45 ) {
        $( ".two" ).addClass( "active" ); 
        }else{
            $(".two").removeClass("active");
        }
          
        if(hour == 9 && minute >= 45 || hour == 10 && minute < 50 ) {
        $( ".three" ).addClass( "active" ); 
        }else{
            $(".three").removeClass("active");
        }
          
        if(hour == 10 && minute >= 50 || hour == 11 && minute < 45 ) {
        $( ".four" ).addClass( "active" ); 
        }else{
            $(".four").removeClass("active");
        }
          
        if(hour == 11 && minute >= 45 || hour == 12 && minute < 40 ) {
        $( ".five" ).addClass( "active" ); 
        }else{
            $(".five").removeClass("active");
        }
          
        if(hour == 12 && minute >= 40 || hour == 13 && minute < 30 ) {
        $( ".six" ).addClass( "active" ); 
        }else{
            $(".six").removeClass("active");
        }
          
        if(hour == 13 && minute >= 30 || hour == 14 && minute < 20 ) {
        $( ".seven" ).addClass( "active" );
        }else{
            $(".seven").removeClass("active");
        }
          
        if(hour == 14 && minute >= 20 || hour == 15 && minute < 15 ) {
        $( ".eight" ).addClass( "active" ); 
        }else{
            $(".eight").removeClass("active");
        }
          
        if(hour == 15 && minute >= 15 || hour == 16 && minute < 60 ) {
        $( ".nine" ).addClass( "active" ); 
        }else{
            $(".nine").removeClass("active");
        }
          
        if(hour == 17 && minute <= 45 ) {
        $( ".ten" ).addClass( "active" ); 
        }else{
            $(".ten").removeClass("active");
        }
          
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
        currentTime = getTime();
        document.getElementById("clock").innerHTML = currentTime;
    }, 1000);  
    

</script>

</body>