<?php

$host = "localhost";

$username = "root";

$password = "";

$dbname = "assignment";

try{
    $conn = new mysqli($host, $username, $password, $dbname);

if(!$conn) {

die("Connection Failed: mysqli_connect_error()");

}else{
    echo 'done';
}
}catch(mysqli_sql_exception $e){
    echo 'Error: ' . $e->getMessage();
}

?>