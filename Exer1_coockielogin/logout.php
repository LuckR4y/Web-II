<?php
require __DIR__ . '/apoio.php';
unset($_SESSION['usuario']);
session_destroy();
header('Location: login.php');
