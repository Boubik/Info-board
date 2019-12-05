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

        Date.prototype.getUnixTime = function() { return this.getTime()/1000|0 };
        if(!Date.now) Date.now = function() { return new Date(); }
        Date.time = function() { return Date.now().getUnixTime(); }

        var currentUnixTime = Math.round((new Date()).getTime() / 1000);

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        if(mm == 12){mm = "Dec";}
        if(mm == 11){mm = "Nov";}
        if(mm == 10){mm = "Oct";}
        if(mm == 9){mm = "Sep";}
        if(mm == 8){mm = "Aug";}
        if(mm == 7){mm = "Jul";}
        if(mm == 6){mm = "Jun";}
        if(mm == 5){mm = "May";}
        if(mm == 4){mm = "Apr";}
        if(mm == 3){mm = "Mar";}
        if(mm == 2){mm = "Feb";}
        if(mm == 1){mm = "Jan";}

        

         


        
        var h0 = $('.zero .hodina_cislo');
        var h0t = h0.text();
        var h0s = h0t.split(/\D/);
        var h0_ut_1 = new Date(dd + " " + mm + " " + yyyy + " " + h0s[0] + ":" + h0s[1] + ":00 GMT").getUnixTime();
        var h0_ut = new Date(dd + " " + mm + " " + yyyy + " " + h0s[4] + ":" + h0s[5] + ":00 GMT").getUnixTime();
         

        var h1 = $('.one .hodina_cislo');
        var h1t = h1.text();
        var h1s = h1t.split(/\D/);
        var h1_ut = new Date(dd + " " + mm + " " + yyyy + " " + h1s[4] + ":" + h1s[5] + ":00 GMT").getUnixTime();

        var h2 = $('.two .hodina_cislo');
        var h2t = h2.text();
        var h2s = h2t.split(/\D/);
        var h2_ut = new Date(dd + " " + mm + " " + yyyy + " " + h2s[4] + ":" + h2s[5] + ":00 GMT").getUnixTime();

        var h3 = $('.three .hodina_cislo');
        var h3t = h3.text();
        var h3s = h3t.split(/\D/);
        var h3_ut = new Date(dd + " " + mm + " " + yyyy + " " + h3s[4] + ":" + h3s[5] + ":00 GMT").getUnixTime();

        var h4 = $('.four .hodina_cislo');
        var h4t = h4.text();
        var h4s = h4t.split(/\D/);
        var h4_ut = new Date(dd + " " + mm + " " + yyyy + " " + h4s[4] + ":" + h4s[5] + ":00 GMT").getUnixTime();

        var h5 = $('.five .hodina_cislo');
        var h5t = h5.text();
        var h5s = h5t.split(/\D/);
        var h5_ut = new Date(dd + " " + mm + " " + yyyy + " " + h5s[4] + ":" + h5s[5] + ":00 GMT").getUnixTime();

        var h6 = $('.six .hodina_cislo');
        var h6t = h6.text();
        var h6s = h6t.split(/\D/);
        var h6_ut = new Date(dd + " " + mm + " " + yyyy + " " + h6s[4] + ":" + h6s[5] + ":00 GMT").getUnixTime();

        var h7 = $('.seven .hodina_cislo');
        var h7t = h7.text();
        var h7s = h7t.split(/\D/);
        var h7_ut = new Date(dd + " " + mm + " " + yyyy + " " + h7s[4] + ":" + h7s[5] + ":00 GMT").getUnixTime();

        var h8 = $('.eight .hodina_cislo');
        var h8t = h8.text();
        var h8s = h8t.split(/\D/);
        var h8_ut = new Date(dd + " " + mm + " " + yyyy + " " + h8s[4] + ":" + h8s[5] + ":00 GMT").getUnixTime();

        var h9 = $('.nine .hodina_cislo');
        var h9t = h9.text();
        var h9s = h9t.split(/\D/);
        var h9_ut = new Date(dd + " " + mm + " " + yyyy + " " + h9s[4] + ":" + h9s[5] + ":00 GMT").getUnixTime();
        
        var h10 = $('.ten .hodina_cislo');
        var h10t = h10.text();
        var h10s = h10t.split(/\D/);
        var h10_ut = new Date(dd + " " + mm + " " + yyyy + " " + h10s[4] + ":" + h10s[5] + ":00 GMT").getUnixTime();

        
        if(currentUnixTime >= h0_ut_1 && currentUnixTime <= h0_ut) {
        $( ".zero" ).addClass( "active" ); 
        }else{
            $(".zero").removeClass("active");
        }
          
        if(currentUnixTime >= h0_ut && currentUnixTime <= h1_ut) {        
            $( ".one" ).addClass( "active" ); 
        }else{
            $(".one").removeClass("active");
        }
          
        if(currentUnixTime >= h1_ut && currentUnixTime <= h2_ut) {        
            $( ".two" ).addClass( "active" ); 
        }else{
            $(".two").removeClass("active");
        }
          
        if(currentUnixTime >= h2_ut && currentUnixTime <= h3_ut) {        
            $( ".three" ).addClass( "active" ); 
        }else{
            $(".three").removeClass("active");
        }
          
        if(currentUnixTime >= h3_ut && currentUnixTime <= h4_ut) {        
            $( ".four" ).addClass( "active" ); 
        }else{
            $(".four").removeClass("active");
        }
          
        if(currentUnixTime >= h4_ut && currentUnixTime <= h5_ut) {        
            $( ".five" ).addClass( "active" ); 
        }else{
            $(".five").removeClass("active");
        }
          
        if(currentUnixTime >= h5_ut && currentUnixTime <= h6_ut) {        
            $( ".six" ).addClass( "active" ); 
        }else{
            $(".six").removeClass("active");
        }
          
        if(currentUnixTime >= h6_ut && currentUnixTime <= h7_ut) {        
            $( ".seven" ).addClass( "active" );
        }else{
            $(".seven").removeClass("active");
        }
          
        if(currentUnixTime >= h7_ut && currentUnixTime <= h8_ut) {        
            $( ".eight" ).addClass( "active" ); 
        }else{
            $(".eight").removeClass("active");
        }
          
        if(currentUnixTime >= h8_ut && currentUnixTime <= h9_ut) {        
            $( ".nine" ).addClass( "active" ); 
        }else{
            $(".nine").removeClass("active");
        }
          
        if(currentUnixTime >= h9_ut && currentUnixTime <= h10_ut) {
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