<?php

$host='localhost';
$dbname="AlphaStore";
$username="root";
$password='';

$mysqli = new mysqli($host,$username,$password,$dbname);

if($mysqli->connect_errno)
    {
       die("connection error: " . $mysqli->connect_error);

    }

return $mysqli ;