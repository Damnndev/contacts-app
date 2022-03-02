<?php
  $host = "localhost";
  $database = "contacts_app";
  $user = "root";
  $password = "";

  try {
    $conn = new PDO(
      "mysql:host=$host; dbname=$database",
      $user,
      $password,
      array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
    // foreach ($conn->query("SHOW DATABASES") as $row) {
    //   print_r($row);
    // } die();
  } catch (PDOException $e) {
    die("PDO Connection Error: " . $e->getMessage());
  }
?>