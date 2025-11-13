<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: index.php?page=login");
    exit;
}

$username = htmlspecialchars($_SESSION['user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #e8f4ff; 
        margin: 0;
        padding: 0;
    }

    .container {
        text-align: center;
        padding-top: 100x; 
    }

    h3{
        font-size: 26px;
        font-weight: bold;
        color: #333;
        text-transform: capitalize;
    }
</style>
</head>
<body>

<div class="container">
    <h2>Selamat Datang, <?= $username ?></h2>
</div>

</body>
</html>
