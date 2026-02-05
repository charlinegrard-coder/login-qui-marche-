<!-- pour centramiser la bdd-->

<?php
$pdo = new PDO(
    "mysql:host=localhost;port=8889;dbname=blogart25;charset=utf8mb4",
    "root",
    "root",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);
