<?php

//DB properties
$servername = "localhost";
$dbName = "blog";
$username = "root";
$password = "2dw3";

try 
{
  $conn = new PDO("mysql:host=$servername;dbname=$dbName", $username, $password);

  //Connection config / properties
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "OK";
} 
catch(PDOException $e) 
{
  echo "Connection failed: " . $e->getMessage();
  die();
}
 