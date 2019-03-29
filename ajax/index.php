<!DOCTYPE html>
<html lang="cs">
    <head>
        <title>Ajax</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="style.css" rel="stylesheet" type="text/css"/>        
        <script src="jquery-3.3.1.min.js" type="text/javascript"></script>        
        <script src="jquery.anoslide.js" type="text/javascript"></script>
    </head>
    <body>
        <div class="main">
            <div class="content">
                <div class="carousel">
                    <ul>
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
                    $('.content1').load('content.php?p=rozvrh').fadeIn("400");
                    $('.content2').load('content.php?p=aktuality').fadeIn("400");
                }, 1000);
            </script>
        </div>
    </body>
</html>
