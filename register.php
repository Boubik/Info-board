<h1>Registration Form</h1>
<form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
	<input type="username" name="usr" value="" placeholder="Uživatel">
	<input type="password" name="pass" value="" placeholder="Heslo">
    <input type="submit" name="submit" value="Zaregistrovat">
</form>

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

if(isset($_POST['submit']) and isset($_POST['usr']) and isset($_POST['pass'])){
    $usr = $_POST['usr'];
    $pass = $_POST['pass'];
    
    $hash = hash("sha3-512", $pass);

    $sql = "INSERT INTO users (`usr`, `pass`, `created`, `lastlogin`, `active`) VALUES ('".$usr."','".$hash."', CURDATE(), CURDATE(), false)";
    $query= $conn->prepare($sql);
    if($query->execute()){
        echo "Registrace je dokončená už jen stačí aby vám admin dal práva";
    }else{
        echo "něco se nepovedlo :( oznamte chybu adminu";
    }
}
?>