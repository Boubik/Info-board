<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <link href="styles/index.css" rel="stylesheet" type="text/css">
    <link rel="icon" href="images/logo.ico">
    <link rel="stylesheet" href="styles/insert.css">
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
$configs = include('config.php');
$servername = $configs["servername"];
$username = $configs["usr_db"];
$password = $configs["passwd_db"];
$dbname = $configs["dbname"];

try {
    $conn = new PDO("mysql:host=".$servername.";dbname=".$dbname.";charset=utf8", $username, $password, NULL);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo "Something goes worn give us time to fix it";
}

$character = $conn->prepare("SET character SET UTF8");
$character->execute();

session_start();
if(!(isset($_SESSION["login"]))){
    $_SESSION["login"] = false;
}

if(isset($_POST["usr"]) and isset($_POST["pass"]) and registred($conn, $_POST["usr"], $_POST["pass"])){
    $_SESSION["login"] = true;
}

if(isset($_SESSION["usr"]) and isset($_SESSION["pass"])){
    lastlogin($conn, $_SESSION["usr"], $_SESSION["pass"]);
}

if($_SESSION["login"]){
    $nadpis = "";
    $text = "";
    $date = "";

    echo '
    <header>
        <div class="header_left">
            <div class="logo_insert"><img src="images/logo.png" alt="logo"></div>
            <div class="page_name">Insert</div>

        </div><div class="header_right">
            
        </div>
    </header>
    <form method="POST" class="container" action="">
    <div class="main">
    <span class="datum_container">
        <p>Do kdy</p>
        <input type="date" name="date" class="datum"  value="'.$date.'">
    </span>
    <span class="nadpis_container"><p>Nadpis</p>
        <input type="text" name="Nadpis" class="nadpis"  value="'.$nadpis.'">
    </span>
    <textarea name="text" class="text" id="mytextarea">
    </textarea>
    <input type="submit" name="submit" class="submit_insert"  value="save">
    </div>
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
            echo '<div class="msg green">vše bylo uloženo</div>';
        }else{
            echo '<div class="msg red">vyplň vše</div>';
        }
    }


    echo "<div class=\"news\">";
    $fileList = glob('aktuality/*.md');
    foreach ($fileList as $file) {
        $handle = fopen($file, "r");
        $delete = true;
        $date = fgets($handle);
        echo "<div class=\"date\"".$date."</div>";

        echo "<h1>" . substr(str_replace(".md", "", $file), 10) . "</h1>";
        echo "<div class=\"date\"";
        while (($line = fgets($handle)) !== false) {
            $i = 0;
            str_replace('**', "<b>", $line, $count);
            while ($i != $count) {
                $line = preg_replace('/\*\*/', "<b>", $line, 1);
                $line = preg_replace('/\*\*/', "</b>", $line, 1);
                $i += 2;
            }
            echo $line . "<br>";
        }
        echo "</div>";
        fclose($handle);
    }
    echo "</div>";

}else{
    echo '<div class="login_container">
    <div class="logo">
    <img src="images/logo.png" alt="">
    </div>
    <form method="POST" action="">
    <div class="username">
        <input type="username" name="usr" placeholder="Jméno">
    </div>

    <div class="password">
        <input type="password" name="pass" placeholder="Heslo">
    </div>

    <div class="submit">
        <input type="submit" name="submit_login"  value="Přihlásit se">
    </div>

    </form>
    </div>';
}



function registred($conn, $usr, $pass){
    $hash = hash("sha3-512", $pass);

    $sql = "SELECT * FROM `users` WHERE `active` = true";
    $query = $conn->prepare($sql);
    $numrows = $query->execute();
    if ($numrows > 0) {
        while($row = $query->fetch()){
            if($hash == $row["pass"] and $usr == $row["usr"]){
                logedind($conn, $usr, $hash);
                $_SESSION["login"] = true;
                $_SESSION["usr"] = $usr;
                $_SESSION["pass"] = $hash;
            }
        }
    }
}


function logedind($conn, $usr, $hash){
    $sql = "UPDATE `users` SET `lastlogin`= CURDATE() WHERE `usr`= '".$usr."' and `pass`= '".$hash."'";
    $query = $conn->prepare($sql);
    $query->execute();
}

function lastlogin($conn, $usr, $hash){

    $sql = "SELECT * FROM `users` WHERE `lastlogin` < NOW() - INTERVAL 1 WEEK";
    $query = $conn->prepare($sql);
    $numrows = $query->execute();
    if ($numrows > 0) {
        while($row = $query->fetch()){
            if($hash == $row["pass"] and $usr == $row["usr"]){
                $_SESSION["login"] = false;
            }
        }
    }
}

?>

</body>