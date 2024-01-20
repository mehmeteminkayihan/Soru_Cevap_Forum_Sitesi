<?php 
    try {
        $host="localhost";
        $kullanici="root";
        $parola="1234";
        $vt="AcDatabase";

        $baglanti= mysqli_connect($host,$kullanici,$parola,$vt);
        mysqli_set_charset($baglanti,"UTF8");

        
        

    } catch (PDOException $e) {
     
        
    }
?>
