<?php
session_start();
session_destroy();
header("Location: login.php");
exit;
require_once "db.php";