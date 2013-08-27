<?php
session_start();
//error_reporting(0);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['host'] = $_POST['host'];
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['pass'] = $_POST['password'];
        $_SESSION['db'] = $_POST['db'];
}



