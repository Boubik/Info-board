<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tabbule</title>
    <script src="jquery-3.3.1.min.js" type="text/javascript"></script>        
    <script src="jquery.anoslide.js" type="text/javascript"></script>
</head>

<body>
        <div class="main">
            <div class="content">
                <div class="carousel">
                    <ul style="height: 100%">
                        <li>
                            <div class="content1">Loading...</div>
                        </li>
                        <li>
                            <div class="content2">Loading...</div>
                        </li>                    
                    </ul>                
                </div>
            </div>
            <script>
                $('.carousel > ul').anoSlide({
                    items: 1,
                    speed: 500,
                    prev: 'a.prev',
                    next: 'a.next',
                    lazy: true,
                    auto: 4000
                });
                var auto_refresh = setInterval(function () {
                    $('.content1').load('rozvrh.php').fadeIn("400");
                    $('.content2').load('news_cantine.php').fadeIn("400");
                }, 10000);
            </script>
        </div>
</body>