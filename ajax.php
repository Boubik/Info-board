<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tabbule</title>
    <script src="https://code.jquery.com/jquery-3.1.1.js"></script>
</head>

<body>
    <script>
        var script = document.createElement('script');
        script.src = 'jquery.js';
        script.type = 'text/javascript';
        document.getElementsByTagName('head')[0].appendChild(script);


        function nacitani(){
        var last = 0;
            if(last == 0){
                $('main').load('rozvrh.php').hide().fadeIn(300);
                last = 1;
                deleay(5000);
            }else{
                $('main').load('news_cantine.php').hide().fadeIn(300);
                last = 0;
                deleay(5000);
            }
        }

        nacitani();
    </script>
    <main>
    </main>
</body>