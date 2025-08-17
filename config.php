<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'videodb';

$BASE_URL = rtrim((isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/\\');

function db() {
    static $conn;
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;
    if (!$conn) {
        $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
        if ($conn->connect_error) die('DB Error: ' . $conn->connect_error);
        $conn->set_charset('utf8mb4');
    }
    return $conn;
}