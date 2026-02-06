<?php
try {
    $conn = new PDO(
        "mysql:host=" . getenv('MYSQL_HOST') . ";dbname=" . getenv('MYSQL_DATABASE'),
        getenv('MYSQL_USER'),
        getenv('MYSQL_PASSWORD')
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connected using PDO!";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
