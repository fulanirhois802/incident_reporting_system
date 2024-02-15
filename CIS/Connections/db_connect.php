<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_db_connect = "localhost";
$database_db_connect = "cis";
$username_db_connect = "root";
$password_db_connect = "";
$db_connect = mysqli_connect($hostname_db_connect, $username_db_connect, $password_db_connect) or trigger_error(mysqli_error(),E_USER_ERROR); 
?>