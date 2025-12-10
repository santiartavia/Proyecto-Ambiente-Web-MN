<?php
require_once "php/init.php";

$_SESSION = [];
session_unset();
session_destroy();

header("Location: index.html");
exit;
