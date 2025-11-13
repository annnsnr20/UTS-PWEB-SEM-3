<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "conn.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $role = $_POST['role']; // ✅ ambil role
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users(nim, email, username, password, role) VALUES(?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $nim, $email, $username, $password, $role);

    if ($stmt->execute()) {
        echo "<p style='color:green; text-align:center;'>Registrasi berhasil! Silakan login.</p>";
        echo "<meta http-equiv='refresh' content='2;url=index.php?page=login'>";
    } else {
        echo "<p style='color:red; text-align:center;'>Gagal mendaftar!</p>";
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f3f4f6;
        margin: 0;
        padding: 0;
    }

    .register-wrapper {
        width: 100%;
        background: #fff;
        padding: 40px 80px;
        box-sizing: border-box;
    }

    h2 {
        color: #333;
        margin-bottom: 20px;
        text-align: center;
    }

    input, select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        margin-bottom: 15px;
    }

    button {
        width: 100%;
        background-color: #007bff;
        color: white;
        padding: 14px 0;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 5px;
    }

    button:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        .register-wrapper {
            padding: 30px 20px;
        }
    }
</style>

<div class="register-wrapper">
    <h2>Register</h2>

    <form method="post">
        <label>NIM:</label>
        <input type="text" name="nim" placeholder="Masukkan NIM" required>

        <label>Email:</label>
        <input type="email" name="email" placeholder="Masukkan Email" required>

        <label>Username:</label>
        <input type="text" name="username" placeholder="Masukkan Username" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="Masukkan Password" required>

        <label>Pilih Role:</label> <!-- ✅ Role selector -->
        <select name="role" required>
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit">Daftar</button>
    </form>
</div>
