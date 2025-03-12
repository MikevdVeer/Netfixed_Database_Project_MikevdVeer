<?php 
try {
    $db = new PDO("mysql:host=localhost;dbname=dbp_data", "root", "");
} catch (PDOException $e) {
    die("w Error");
}
