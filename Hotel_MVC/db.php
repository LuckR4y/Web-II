<?php

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "hotel";

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_error) {
    die("Falha na conexão: " . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
