<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    echo "<h3>Access Denied! Kamu belum login.</h3>";
    exit;
}

$nama = $_SESSION['user']; 
$nim  = isset($_SESSION['nim']) ? $_SESSION['nim'] : "NIM tidak tersedia";

$foto = "https://avatars.githubusercontent.com/u/9919?s=200&v=4";
?>

<style>

.profile-card {
    width: 350px;
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    text-align: center;
}

.profile-card img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #4CAF50;
    margin-bottom: 15px;
}

.profile-card h2 {
    margin: 10px 0 5px;
}

.profile-card p {
    margin: 6px 0;
    font-size: 16px;
}
</style>

<div class="container">
    <div class="profile-card">
        <img src="<?= $foto; ?>" alt="Foto Profil">
        <h2><?= htmlspecialchars($nama); ?></h2>
    </div>
</div>
