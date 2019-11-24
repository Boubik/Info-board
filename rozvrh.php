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
include "functions.php";
read_rozvrh();
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
        

        
        var h0 = $('.zero .hodina_cislo');
        var h0t = h0.text();
        var h0s = h0t.split(/\D/);

        var h1 = $('.one .hodina_cislo');
        var h1t = h1.text();
        var h1s = h1t.split(/\D/);

        var h2 = $('.two .hodina_cislo');
        var h2t = h2.text();
        var h2s = h2t.split(/\D/);

        var h3 = $('.three .hodina_cislo');
        var h3t = h3.text();
        var h3s = h3t.split(/\D/);

        var h4 = $('.four .hodina_cislo');
        var h4t = h4.text();
        var h4s = h4t.split(/\D/);

        var h5 = $('.five .hodina_cislo');
        var h5t = h5.text();
        var h5s = h5t.split(/\D/);

        var h6 = $('.six .hodina_cislo');
        var h6t = h6.text();
        var h6s = h6t.split(/\D/);

        var h7 = $('.seven .hodina_cislo');
        var h7t = h7.text();
        var h7s = h7t.split(/\D/);

        var h8 = $('.eight .hodina_cislo');
        var h8t = h8.text();
        var h8s = h8t.split(/\D/);

        var h9 = $('.nine .hodina_cislo');
        var h9t = h9.text();
        var h9s = h9t.split(/\D/);
        
        var h10 = $('.ten .hodina_cislo');
        var h10t = h10.text();
        var h10s = h10t.split(/\D/);

        
        

        
        if(hour == h0s[0] && minute >= h0s[2]) {
        $( ".zero" ).addClass( "active" ); 
        }else{
            $(".zero").removeClass("active");
        }
          
        if(hour == h1s[0] && minute >=  h1s[2] || hour == h1s[4] && minute <  h1s[5] ) {
        $( ".one" ).addClass( "active" ); 
        }else{
            $(".one").removeClass("active");
        }
          
        if(hour == h2s[0] && minute >=  h2s[2] || hour == h2s[4] && minute <  h2s[5] ) {
        $( ".two" ).addClass( "active" ); 
        }else{
            $(".two").removeClass("active");
        }
          
        if(hour == h3s[0] && minute >= h3s[2] || hour == h3s[4] && minute < h3s[5] ) {
        $( ".three" ).addClass( "active" ); 
        }else{
            $(".three").removeClass("active");
        }
          
        if(hour == h4s[0] && minute >= h4s[2] || hour == h4s[4] && minute < h4s[5] ) {
        $( ".four" ).addClass( "active" ); 
        }else{
            $(".four").removeClass("active");
        }
          
        if(hour == h5s[0] && minute >= h5s[2] || hour == h5s[4] && minute < h5s[5] ) {
        $( ".five" ).addClass( "active" ); 
        }else{
            $(".five").removeClass("active");
        }
          
        if(hour == h6s[0] && minute >= h6s[2] || hour == h6s[4] && minute < h6s[5] ) {
        $( ".six" ).addClass( "active" ); 
        }else{
            $(".six").removeClass("active");
        }
          
        if(hour == h7s[0] && minute >= h7s[2] || hour == h7s[4] && minute < h7s[5] ) {
        $( ".seven" ).addClass( "active" );
        }else{
            $(".seven").removeClass("active");
        }
          
        if(hour == h8s[0] && minute >= h8s[2] || hour == h8s[4] && minute < h8s[5] ) {
        $( ".eight" ).addClass( "active" ); 
        }else{
            $(".eight").removeClass("active");
        }
          
        if(hour == h9s[0] && minute >= h9s[2] || hour == h9s[4] && minute < h9s[5] ) {
        $( ".nine" ).addClass( "active" ); 
        }else{
            $(".nine").removeClass("active");
        }
          
        if(hour == h10s[0] && minute <= h10s[2] ) {
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