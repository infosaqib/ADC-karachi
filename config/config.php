<?php
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);

    global $pdo;
    $servername = "localhost";
    $username = "u311137911_wasimakramkrh";
    $password = "Akram140@g";
    $dbname = "u311137911_armykarachi";
    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>