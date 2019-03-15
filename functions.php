<?php

/**
 * will print news from files in folder "aktuality"
 */
function news(){

    $fileList = glob('aktuality/*.md');
    foreach($fileList as $file){
        $handle = fopen($file, "r");
        $delete = TRUE;
        $line = fgets($handle);
        $date = explode("-", $line);
        $year = $date[0];
        $mounth = $date[1];
        $day = $date[2];
        if($year > date('Y', time())){
            $delete = FALSE;
        }else{
            if($year = date('Y', time())){
                if($mounth > date('m', time())){
                    $delete = FALSE;
                }
                if($mounth = date('m', time())){
                    if($day >= date('d', time())){
                        $delete = FALSE;
                    }
                }
            }
        }

        if($delete == TRUE){
            unlink($file);
        }else{
            echo "<h1>".substr(str_replace(".md", "", $file), 10)."</h1><br>";
            while(($line = fgets($handle)) !== false){
                $i = 0;
                str_replace('**', "<b>", $line, $count);
                while($i != $count){
                    $line = preg_replace('/\*\*/', "<b>", $line, 1);
                    $line = preg_replace('/\*\*/', "</b>", $line, 1);
                    $i+=2;
                }
                echo $line."<br>";
            }
        }
        fclose($handle);
    }

}

/**
 * form for news 
 * it will take input and save it in folder aktuality
 */
function get_news(){
    $nadpis = "";
    $text = "";
    $date = "";

    echo '<form method="POST" action="">Do kdy
    <input type="date" name="date"  value="'.$date.'">Nadpis
    <input type="text" name="Nadpis"  value="'.$nadpis.'">Text
    <textarea name="text" column="10" row="10"></textarea>
    <input type="submit" name="submit"  value="save">
    </form>';

    if(isset($_POST["submit"]) and isset($_POST["Nadpis"]) and isset($_POST["text"]) and isset($_POST["date"])){
        $nadpis = $_POST["Nadpis"];
        $text = $_POST["text"];
        $date = $_POST["date"];
        
        if($nadpis != "" and $text != "" and $date != ""){
            //echo "<h1>".$nadpis."</h1>";
            $myfile = fopen("aktuality/".$nadpis.".md", "w");
            //echo $date."<br>";
            fwrite($myfile, $date."\n");
            //echo $text."<br>";
            fwrite($myfile, $text);
            fclose($myfile);
            echo "<br>vše bylo uloženo";
        }else{
            echo "vyplň vše";
        }
    }
}