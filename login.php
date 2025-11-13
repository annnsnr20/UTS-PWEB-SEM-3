<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = $_POST['login_input'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE nim=? OR email=? OR username=? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $input, $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        // ✅ Simpan session user & role
        $_SESSION['user'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // ✅ Redirect sesuai role
        if ($user['role'] == 'admin') {
            header("Location: index.php?page=dashboard");
        } else {
            header("Location: index.php");
        }
        exit;

    } else {
        echo "<p style='color:red; text-align:center;'>Login gagal! Periksa kembali data anda.</p>";
    }
}
?>

<style>
body { 
    font-family: Arial, sans-serif; 
    background: #e8f4ff;
    margin: 0;
}
.login-wrapper { 
    max-width: 400px;
    margin: 100px auto;
    background: #fff; 
    padding: 40px; 
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.2);
}
h2 { text-align: center; margin-bottom: 20px; }
input, button { 
    width: 100%; 
    padding: 12px; 
    margin-bottom: 15px; 
    border-radius: 5px;
    border: 1px solid #ccc;
}
button { 
    background:#007bff; 
    color:#fff; 
    border:none; 
    cursor:pointer;
    font-size: 16px;
}
button:hover { background:#0056b3; }
</style>

<div class="login-wrapper">
<h2>Login</h2>

<form method="post">
    <label>Masukkan NIM / Email / Username:</label>
    <input type="text" name="login_input" placeholder="Contoh: 12345 / email@mail.com / username" required>

    <label>Password:</label>
    <input type="password" name="password" placeholder="Masukkan Password" required>

    <button type="submit">Login</button>
</form>
</div>
