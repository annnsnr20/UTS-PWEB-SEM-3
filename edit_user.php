<?php
include "conn.php";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    echo "<h3>Akses ditolak ❌</h3>";
    exit;
}

$id = $_GET['id'];
$query = "SELECT * FROM users WHERE id='$id'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $conn->query("UPDATE users SET username='$username', email='$email' WHERE id='$id'");
    echo "<script>alert('User berhasil diupdate'); window.location='index.php?page=user';</script>";
}
?>

<form method="post" style="width:40%; margin:auto; margin-top:30px;">
    <h3>Edit User</h3>
    <label>Username:</label>
    <input type="text" name="username" value="<?= $user['username']; ?>" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" value="<?= $user['email']; ?>" required><br><br>

    <button type="submit">Update</button>
</form>
