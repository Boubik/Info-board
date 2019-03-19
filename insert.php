<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/index.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <title>Insert news</title>
    <script src="https://cloud.tinymce.com/5/tinymce.min.js?apiKey=logspdcwzv4eydmuk9f8sxe6bjh4sfxny0uxkbr9btixvcxm"></script>
    <script>
        tinymce.init({
        selector: '#mytextarea',
        plugins: "lists",
        height: 400,
        width: 400,
        menubar: false,
        toolbar: "undo redo | bold italic | removeformat | numlist bullist"
    });
  </script>
</head>

<body>

<?php

$nadpis = "";
$text = "";
$date = "";

echo '<form method="POST" action="">Do kdy
<input type="date" name="date"  value="'.$date.'">Nadpis
<input type="text" name="Nadpis"  value="'.$nadpis.'">Text
<textarea name="text" id="mytextarea"></textarea>
<input type="submit" name="submit"  value="save">
</form>';

if(isset($_POST["submit"]) and isset($_POST["Nadpis"]) and isset($_POST["text"]) and isset($_POST["date"])){
    $nadpis = $_POST["Nadpis"];
    $text = $_POST["text"];
    $date = $_POST["date"];
    //$nadpis = htmlspecialchars($_POST["Nadpis"], ENT_QUOTES, 'UTF-8');
    //$text = htmlspecialchars($_POST["text"], ENT_QUOTES, 'UTF-8');
    //$date = htmlspecialchars($_POST["date"], ENT_QUOTES, 'UTF-8');
    
    if($nadpis != "" and $text != "" and $date != ""){
        //echo "<h1>".$nadpis."</h1>";
        $myfile = fopen("aktuality/".$nadpis.".md", "w");
        //echo $date."<br>";
        fwrite($myfile, $date."\n");
        $text = preg_replace('/<p>/', "", $text);
        $text = preg_replace('/<\/p>/', "", $text);
        $text = preg_replace('/<strong>/', "<b>", $text);
        $text = preg_replace('/<\/strong>/', "</b>", $text);
        //echo $text."<br>";
        fwrite($myfile, $text);
        fclose($myfile);
        echo "<br>vše bylo uloženo";
    }else{
        echo "vyplň vše";
    }
}

?>

</body>