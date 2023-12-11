<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'PapaShokshat7879_#!');
define('DB_NAME', 'devices');
$mysql = null;
try{
    $mysql = new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
}catch(Exception $e){
    echo "<p>Ошибка в базе данных</p>";
}
?>