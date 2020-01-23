<?php
function Connect()
{
    $password = "pPaNqGXh0h>#";
    $host = "localhost";
    $user = "root";
    $conn = new mysqli($host,$user,$password);
    if($conn->connect_error)
    {
        die("Connect failed");
    }
    else {
        return $conn;
    }
}